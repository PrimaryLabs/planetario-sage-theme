<?php

namespace App\Fields;

class SiteSettings
{
    public const GROUP_KEY        = 'group_site_settings';
    public const OPTIONS_PAGE_KEY = 'site-settings';

    public static function register(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        if (function_exists('acf_add_options_page')) {
            \acf_add_options_page([
                'page_title'  => 'Site Settings',
                'menu_title'  => 'Site Settings',
                'menu_slug'   => self::OPTIONS_PAGE_KEY,
                'capability'  => 'edit_theme_options',
                'redirect'    => false,
                'icon_url'    => 'dashicons-admin-site-alt3',
                'position'    => 80,
                'autoload'    => true,
            ]);
        }

        \acf_add_local_field_group([
            'key'      => self::GROUP_KEY,
            'title'    => 'Site Settings',
            'location' => [[
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => self::OPTIONS_PAGE_KEY,
                ],
            ]],
            'position' => 'normal',
            'style'    => 'default',
            'active'   => true,
            'fields'   => self::fields(),
        ]);
    }

    private static function fields(): array
    {
        return array_merge(
            [['key' => 'field_ss_tab_brand', 'label' => 'Brand', 'type' => 'tab']],
            self::brandFields(),
            [['key' => 'field_ss_tab_contact', 'label' => 'Contact', 'type' => 'tab']],
            self::contactFields(),
            [['key' => 'field_ss_tab_socials', 'label' => 'Socials', 'type' => 'tab']],
            self::socialFields(),
            [['key' => 'field_ss_tab_services', 'label' => 'Services', 'type' => 'tab']],
            self::servicesFields(),
            [['key' => 'field_ss_tab_footer', 'label' => 'Footer', 'type' => 'tab']],
            self::footerFields(),
        );
    }

    private static function servicesFields(): array
    {
        return [
            [
                'key'           => 'field_ss_services_eyebrow',
                'label'         => 'Section eyebrow',
                'name'          => 'services_eyebrow',
                'type'          => 'text',
                'default_value' => 'What we do',
            ],
            [
                'key'           => 'field_ss_services_headline_lead',
                'label'         => 'Headline — lead',
                'name'          => 'services_headline_lead',
                'type'          => 'text',
                'default_value' => 'Six services.',
            ],
            [
                'key'           => 'field_ss_services_headline_em',
                'label'         => 'Headline — emphasis',
                'name'          => 'services_headline_emphasis',
                'type'          => 'text',
                'default_value' => 'One promise.',
            ],
            [
                'key'           => 'field_ss_services_intro',
                'label'         => 'Intro paragraph',
                'name'          => 'services_intro',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'default_value' => "Property goals are personal. Our services are built around the actual decisions you'll need to make not the listings we want to push.",
            ],
            [
                'key'          => 'field_ss_services_items',
                'label'        => 'Service items',
                'name'         => 'services_items',
                'type'         => 'repeater_field',
                'min'          => 0,
                'max'          => 12,
                'layout'       => 'block',
                'button_label' => 'Add service',
                'sub_fields'   => [
                    [
                        'key'      => 'field_ss_service_num',
                        'label'    => 'Number',
                        'name'     => 'number',
                        'type'     => 'text',
                        'wrapper'  => ['width' => '15'],
                        'instructions' => 'e.g. 01, 02 — used to look up icon glyph.',
                    ],
                    [
                        'key'      => 'field_ss_service_title',
                        'label'    => 'Title',
                        'name'     => 'title',
                        'type'     => 'text',
                        'wrapper'  => ['width' => '35'],
                        'required' => 1,
                    ],
                    [
                        'key'       => 'field_ss_service_desc',
                        'label'     => 'Description',
                        'name'      => 'description',
                        'type'      => 'textarea',
                        'rows'      => 3,
                        'new_lines' => '',
                        'wrapper'   => ['width' => '50'],
                    ],
                ],
            ],
        ];
    }

    private static function brandFields(): array
    {
        return [
            [
                'key'           => 'field_ss_brand_name',
                'label'         => 'Brand name',
                'name'          => 'brand_name',
                'type'          => 'text',
                'default_value' => 'Planetario Realty',
            ],
            [
                'key'           => 'field_ss_brand_legal',
                'label'         => 'Legal name',
                'name'          => 'brand_legal',
                'type'          => 'text',
                'default_value' => 'Planetario Realty & Brokerage Services Inc.',
            ],
            [
                'key'           => 'field_ss_brand_tagline',
                'label'         => 'Tagline',
                'name'          => 'brand_tagline',
                'type'          => 'text',
                'default_value' => 'Turning Property Dreams into Reality',
            ],
            [
                'key'           => 'field_ss_brand_short',
                'label'         => 'Short description',
                'name'          => 'brand_short',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'instructions'  => 'Used in the footer brand column.',
                'default_value' => 'A Bohol-rooted realty house, brokering homes and investments across the Visayas with care and clarity.',
            ],
            [
                'key'           => 'field_ss_brand_founded',
                'label'         => 'Founded year',
                'name'          => 'brand_founded',
                'type'          => 'text',
                'default_value' => '2018',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_ss_brand_license',
                'label'         => 'License label',
                'name'          => 'brand_license',
                'type'          => 'text',
                'default_value' => 'PRC Licensed Brokerage',
                'wrapper'       => ['width' => '50'],
            ],
        ];
    }

    private static function contactFields(): array
    {
        return [
            [
                'key'           => 'field_ss_contact_phone',
                'label'         => 'Phone',
                'name'          => 'contact_phone',
                'type'          => 'text',
                'default_value' => '0910 267 1424',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_ss_contact_phone_link',
                'label'         => 'Phone (tel: link)',
                'name'          => 'contact_phone_link',
                'type'          => 'text',
                'instructions'  => 'Digits only, no spaces. Used in tel: links.',
                'default_value' => '09102671424',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_ss_contact_email',
                'label'         => 'Email',
                'name'          => 'contact_email',
                'type'          => 'email',
                'default_value' => 'planetariorealtyandbrokerage@gmail.com',
            ],
            [
                'key'           => 'field_ss_contact_address_line_1',
                'label'         => 'Address line 1',
                'name'          => 'contact_address_line_1',
                'type'          => 'text',
                'default_value' => '66 Remolador Ext., Brgy. Cogon,',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_ss_contact_address_line_2',
                'label'         => 'Address line 2',
                'name'          => 'contact_address_line_2',
                'type'          => 'text',
                'default_value' => 'Tagbilaran City, Bohol',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_ss_contact_hours_weekday',
                'label'         => 'Office hours — weekday',
                'name'          => 'contact_hours_weekday',
                'type'          => 'text',
                'default_value' => 'Monday – Saturday · 8:00 to 18:00',
            ],
            [
                'key'           => 'field_ss_contact_hours_weekend',
                'label'         => 'Office hours — weekend',
                'name'          => 'contact_hours_weekend',
                'type'          => 'text',
                'default_value' => 'Sunday · by appointment',
            ],
            [
                'key'           => 'field_ss_contact_note',
                'label'         => 'Sidebar note',
                'name'          => 'contact_note',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => '',
                'default_value' => 'Walk-ins welcome at our Tagbilaran office. For Cebu meetings, our senior team coordinates a private appointment at a venue convenient to you.',
            ],
        ];
    }

    private static function socialFields(): array
    {
        return [
            [
                'key'     => 'field_ss_social_facebook',
                'label'   => 'Facebook URL',
                'name'    => 'social_facebook',
                'type'    => 'url',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key'     => 'field_ss_social_instagram',
                'label'   => 'Instagram URL',
                'name'    => 'social_instagram',
                'type'    => 'url',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key'     => 'field_ss_social_linkedin',
                'label'   => 'LinkedIn URL',
                'name'    => 'social_linkedin',
                'type'    => 'url',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key'     => 'field_ss_social_youtube',
                'label'   => 'YouTube URL',
                'name'    => 'social_youtube',
                'type'    => 'url',
                'wrapper' => ['width' => '50'],
            ],
        ];
    }

    private static function footerFields(): array
    {
        return [
            [
                'key'           => 'field_ss_footer_copyright',
                'label'         => 'Copyright owner',
                'name'          => 'footer_copyright_owner',
                'type'          => 'text',
                'instructions'  => 'Rendered after "© {year}". Leave the legal name.',
                'default_value' => 'Planetario Realty & Brokerage Services Inc.',
            ],
            [
                'key'           => 'field_ss_footer_sigil_left',
                'label'         => 'Sigil — left',
                'name'          => 'footer_sigil_left',
                'type'          => 'text',
                'default_value' => 'PRC Lic. No. ████-██',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_ss_footer_sigil_right',
                'label'         => 'Sigil — right',
                'name'          => 'footer_sigil_right',
                'type'          => 'text',
                'default_value' => 'Tagbilaran · Cebu',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'          => 'field_ss_footer_explore',
                'label'        => 'Explore links',
                'name'         => 'footer_explore',
                'type'         => 'repeater_field',
                'min'          => 0,
                'layout'       => 'table',
                'button_label' => 'Add link',
                'sub_fields'   => [
                    [
                        'key'      => 'field_ss_footer_explore_label',
                        'label'    => 'Label',
                        'name'     => 'label',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                    [
                        'key'      => 'field_ss_footer_explore_url',
                        'label'    => 'URL',
                        'name'     => 'url',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                ],
            ],
            [
                'key'          => 'field_ss_footer_company',
                'label'        => 'Company links',
                'name'         => 'footer_company',
                'type'         => 'repeater_field',
                'min'          => 0,
                'layout'       => 'table',
                'button_label' => 'Add link',
                'sub_fields'   => [
                    [
                        'key'      => 'field_ss_footer_company_label',
                        'label'    => 'Label',
                        'name'     => 'label',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                    [
                        'key'      => 'field_ss_footer_company_url',
                        'label'    => 'URL',
                        'name'     => 'url',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                ],
            ],
        ];
    }
}
