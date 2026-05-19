<?php

namespace App\Admin;

class TeamImportHandler
{
    private const IMAGE_EXTS   = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    private const CATEGORY_MAP = ['BOD' => 'Board of Directors'];

    // -------------------------------------------------------------------------
    // Extraction + scanning
    // -------------------------------------------------------------------------

    public static function extractAndScan(string $zipPath): array
    {
        $uploadDir = \wp_upload_dir();
        $tempDir   = $uploadDir['basedir'] . '/planetario-team-import/' . \uniqid('import_', true);

        if (! \wp_mkdir_p($tempDir)) {
            return ['error' => 'Could not create temporary directory.'];
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['error' => 'Could not open ZIP archive.'];
        }
        $zip->extractTo($tempDir);
        $zip->close();

        // Ensure extracted files are web-readable (ZipArchive may produce 0600)
        self::fixPermissions($tempDir);

        // Descend past any single wrapper folder (e.g. created by macOS "Compress folder")
        $scanRoot = self::resolveScanRoot($tempDir);
        $members  = self::scanMembers($scanRoot);

        if (empty($members)) {
            self::cleanupTempDir($tempDir);
            return ['error' => 'No valid image files found inside the ZIP. Make sure the ZIP contains at least one folder with images inside (e.g. BOD/NAME - POSITION.png).'];
        }

        // extract_dir is always the raw extraction root (for cleanup)
        // temp_dir is the resolved scan root (for image path resolution)
        return ['extract_dir' => $tempDir, 'temp_dir' => $scanRoot, 'members' => $members];
    }

    /**
     * If the extraction root contains a single non-system directory and no image
     * files, treat that directory as the real root (common when the user zips a
     * parent folder rather than selecting the category folders directly).
     */
    private static function resolveScanRoot(string $dir): string
    {
        $entries = self::listVisibleEntries($dir);

        $hasImages = false;
        $dirs      = [];
        foreach ($entries as $entry) {
            $path = $dir . '/' . $entry;
            if (is_dir($path)) {
                $dirs[] = $entry;
            } elseif (in_array(strtolower(pathinfo($entry, PATHINFO_EXTENSION)), self::IMAGE_EXTS, true)) {
                $hasImages = true;
            }
        }

        if (! $hasImages && count($dirs) === 1) {
            return $dir . '/' . $dirs[0];
        }

        return $dir;
    }

    private static function scanMembers(string $tempDir): array
    {
        $members = [];

        foreach (self::listVisibleEntries($tempDir) as $folderName) {
            $folderPath = $tempDir . '/' . $folderName;

            if (! is_dir($folderPath)) {
                continue;
            }

            $category = self::formatCategory($folderName);
            $region   = self::detectRegion($folderName);

            foreach (self::listVisibleEntries($folderPath) as $entry) {
                $entryPath = $folderPath . '/' . $entry;

                if (is_dir($entryPath)) {
                    // Sub-folder mode: sub-folder name = position, filename = member name only
                    $position = self::formatPosition($entry);

                    foreach (self::listVisibleEntries($entryPath) as $fileName) {
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (! in_array($ext, self::IMAGE_EXTS, true)) {
                            continue;
                        }

                        $stem = pathinfo($fileName, PATHINFO_FILENAME);

                        $members[] = [
                            'name'       => self::parseFileName($stem)['name'],
                            'position'   => $position,
                            'category'   => $category,
                            'region'     => $region,
                            'folder'     => $folderName,
                            'image_path' => $folderName . '/' . $entry . '/' . $fileName,
                        ];
                    }
                } else {
                    // Flat mode: NAME - POSITION encoded in the filename
                    $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                    if (! in_array($ext, self::IMAGE_EXTS, true)) {
                        continue;
                    }

                    $stem   = pathinfo($entry, PATHINFO_FILENAME);
                    $parsed = self::parseFileName($stem);

                    $members[] = [
                        'name'       => $parsed['name'],
                        'position'   => $parsed['position'],
                        'category'   => $category,
                        'region'     => $region,
                        'folder'     => $folderName,
                        'image_path' => $folderName . '/' . $entry,
                    ];
                }
            }
        }

        return $members;
    }

    /** Returns scandir entries minus dots, hidden files, and macOS metadata. */
    private static function listVisibleEntries(string $dir): array
    {
        return array_values(array_filter(
            \scandir($dir) ?: [],
            fn(string $e) => $e !== '.' && $e !== '..' && $e !== '__MACOSX' && ! str_starts_with($e, '.')
        ));
    }

    // -------------------------------------------------------------------------
    // Parsing helpers
    // -------------------------------------------------------------------------

    public static function parseFileName(string $stem): array
    {
        // Normalise underscores to spaces first
        $stem = str_replace('_', ' ', $stem);

        // Primary split: " - " (space-dash-space)
        if (str_contains($stem, ' - ')) {
            $pos      = strpos($stem, ' - ');
            $namePart = substr($stem, 0, $pos);
            $posPart  = substr($stem, $pos + 3);
        } elseif (str_contains($stem, '-')) {
            // Fallback: split on the last plain dash
            $lastDash = strrpos($stem, '-');
            $namePart = substr($stem, 0, $lastDash);
            $posPart  = substr($stem, $lastDash + 1);
        } else {
            $namePart = $stem;
            $posPart  = '';
        }

        return [
            'name'     => self::titleCase(trim($namePart)),
            'position' => self::titleCase(trim($posPart)),
        ];
    }

    private static function formatPosition(string $subfolder): string
    {
        $name = trim($subfolder);
        // Keep pure-uppercase abbreviations as-is (DM, SDM, SM, etc.)
        if (preg_match('/^[A-Z]+$/', $name)) {
            return $name;
        }

        return self::titleCase($name);
    }

    private static function formatCategory(string $folder): string
    {
        $upper = strtoupper(trim($folder));

        return self::CATEGORY_MAP[$upper] ?? self::titleCase($folder);
    }

    private static function detectRegion(string $folder): string
    {
        $upper = strtoupper($folder);

        if (str_contains($upper, 'BOHOL')) {
            return 'bohol';
        }
        if (str_contains($upper, 'CEBU')) {
            return 'cebu';
        }

        return 'all';
    }

    private static function titleCase(string $str): string
    {
        return ucwords(strtolower($str));
    }

    // -------------------------------------------------------------------------
    // Bulk creation
    // -------------------------------------------------------------------------

    public static function bulkCreate(array $members, string $tempDir): int
    {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $created = 0;

        foreach ($members as $i => $data) {
            $name      = \sanitize_text_field($data['name']     ?? '');
            $position  = \sanitize_text_field($data['position'] ?? '');
            $category  = \sanitize_text_field($data['category'] ?? '');
            $region    = \sanitize_key($data['region']          ?? 'all');
            $imagePath = $data['image_path']                    ?? '';

            if ($name === '') {
                continue;
            }

            $postId = \wp_insert_post([
                'post_type'   => 'team_member',
                'post_status' => 'publish',
                'post_title'  => $name,
                'post_name'   => \sanitize_title($name),
                'menu_order'  => (int) ($data['order'] ?? $i),
            ]);

            if (\is_wp_error($postId) || ! $postId) {
                continue;
            }

            if ($category !== '') {
                \wp_set_object_terms($postId, $category, 'team_role');
            }

            \update_post_meta($postId, 'team_title',  $position);
            \update_post_meta($postId, 'team_region', $region);

            $email = \sanitize_email($data['email'] ?? '');
            $phone = \sanitize_text_field($data['phone'] ?? '');
            if ($email !== '') \update_post_meta($postId, 'team_email', $email);
            if ($phone !== '') \update_post_meta($postId, 'team_phone', $phone);

            if ($imagePath !== '') {
                $attachId = self::sideloadImage($tempDir . '/' . $imagePath, $name);
                if ($attachId && ! \is_wp_error($attachId)) {
                    \update_field('field_team_photo', $attachId, $postId);
                }
            }

            $created++;
        }

        return $created;
    }

    private static function sideloadImage(string $fullPath, string $title): int|\WP_Error
    {
        if (! file_exists($fullPath)) {
            return new \WP_Error('file_missing', "Image not found: $fullPath");
        }

        // Copy to a true temp file so WP can safely move it without destroying our extracted copy
        $tmpPath = \wp_tempnam(basename($fullPath));
        if (! copy($fullPath, $tmpPath)) {
            return new \WP_Error('copy_failed', "Could not copy image for sideload: $fullPath");
        }

        $file = [
            'name'     => basename($fullPath),
            'tmp_name' => $tmpPath,
            'type'     => \wp_check_filetype(basename($fullPath))['type'] ?? 'image/jpeg',
            'error'    => UPLOAD_ERR_OK,
            'size'     => filesize($tmpPath),
        ];

        return \media_handle_sideload($file, 0, $title);
    }

    // -------------------------------------------------------------------------
    // Cleanup
    // -------------------------------------------------------------------------

    private static function fixPermissions(string $dir): void
    {
        foreach (new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        ) as $item) {
            chmod((string) $item, $item->isDir() ? 0755 : 0644);
        }
    }

    public static function cleanupTempDir(string $dir): void
    {
        // Safety: only delete paths that live inside our designated import scratch space
        $uploadBase = \wp_upload_dir()['basedir'] . '/planetario-team-import/';
        if (! str_starts_with(realpath($dir) ?: $dir, realpath($uploadBase) ?: $uploadBase)) {
            return;
        }

        self::recursiveDelete($dir);
    }

    private static function recursiveDelete(string $path): void
    {
        if (is_dir($path)) {
            foreach (\scandir($path) as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                self::recursiveDelete($path . '/' . $entry);
            }
            rmdir($path);
        } elseif (is_file($path)) {
            unlink($path);
        }
    }
}
