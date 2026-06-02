/**
 * Planetario Inline Editor
 * Activates only when #wpadminbar is present (WP admin logged in on front-end).
 * Click any highlighted element to edit its ACF field content.
 */

const PLE_CSS = `
[data-edit-field] {
  cursor: pointer;
}
[data-edit-field]:hover {
  outline: 2px dashed #3b82f6;
  outline-offset: 4px;
  border-radius: 2px;
}
.ple-select {
  width: 100%;
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
  background: #fff;
  cursor: pointer;
  transition: border-color .15s, box-shadow .15s;
}
.ple-select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-chip {
  position: fixed;
  bottom: 72px;
  right: 16px;
  z-index: 99998;
  display: flex;
  align-items: center;
  gap: 6px;
  background: #3b82f6;
  color: #fff;
  font: 600 10px/1 system-ui, sans-serif;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 7px 11px;
  border-radius: 100px;
  box-shadow: 0 2px 12px rgba(0,0,0,.25);
  pointer-events: none;
  user-select: none;
}
.ple-backdrop {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 99999;
  background: rgba(0,0,0,.55);
  backdrop-filter: blur(3px);
  -webkit-backdrop-filter: blur(3px);
  align-items: center;
  justify-content: center;
}
.ple-backdrop.open {
  display: flex;
}
.ple-modal {
  background: #fff;
  color: #111;
  border-radius: 14px;
  padding: 28px;
  width: min(620px, calc(100vw - 32px));
  box-shadow: 0 24px 64px rgba(0,0,0,.4);
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.ple-modal-label {
  font: 600 10px/1 system-ui, sans-serif;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #6b7280;
}
.ple-input, .ple-textarea {
  width: 100%;
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
  resize: vertical;
  transition: border-color .15s, box-shadow .15s;
}
.ple-input:focus, .ple-textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-hint {
  font: 400 11px/1 system-ui, sans-serif;
  color: #9ca3af;
  margin-top: -6px;
}
.ple-rte-wrap {
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  overflow: hidden;
  transition: border-color .15s, box-shadow .15s;
}
.ple-rte-wrap:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-rte-toolbar {
  display: flex;
  gap: 2px;
  padding: 6px 8px;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}
.ple-rte-toolbar button {
  border: none;
  background: transparent;
  border-radius: 5px;
  padding: 4px 8px;
  font: 600 13px/1 system-ui, sans-serif;
  color: #374151;
  cursor: pointer;
  transition: background .12s;
  min-width: 28px;
}
.ple-rte-toolbar button:hover { background: #e5e7eb; }
.ple-rte-toolbar button.active { background: #dbeafe; color: #1d4ed8; }
.ple-rte-toolbar .sep {
  width: 1px;
  background: #e5e7eb;
  margin: 3px 4px;
}
.ple-rte {
  min-height: 140px;
  max-height: 320px;
  overflow-y: auto;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
}
.ple-rte em, .ple-rte i { font-style: italic; }
.ple-rte strong, .ple-rte b { font-weight: 700; }
.ple-rte a { color: #2563eb; text-decoration: underline; }
.ple-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: flex-end;
  margin-top: 4px;
}
.ple-status {
  font: 400 12px/1 system-ui, sans-serif;
  color: #9ca3af;
  margin-right: auto;
  min-height: 14px;
}
.ple-status.ok    { color: #10b981; }
.ple-status.error { color: #ef4444; }
.ple-btn {
  border: none;
  border-radius: 8px;
  padding: 9px 18px;
  font: 600 13px/1 system-ui, sans-serif;
  cursor: pointer;
  transition: opacity .15s, background .15s;
  white-space: nowrap;
}
.ple-btn:disabled { opacity: .45; cursor: not-allowed; }
.ple-btn-cancel { background: #f3f4f6; color: #374151; }
.ple-btn-cancel:hover:not(:disabled) { background: #e5e7eb; }
.ple-btn-save { background: #3b82f6; color: #fff; }
.ple-btn-save:hover:not(:disabled) { background: #2563eb; }
[data-edit-admin] {
  cursor: pointer;
  position: relative;
}
[data-edit-admin]:hover {
  outline: 2px dashed #f59e0b;
  outline-offset: 4px;
  border-radius: 2px;
}
[data-edit-admin]::after {
  content: 'Manage in WP Admin →';
  position: absolute;
  top: 0;
  right: 0;
  background: #f59e0b;
  color: #fff;
  font: 600 9px/1 system-ui, sans-serif;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 4px 9px;
  border-radius: 4px;
  pointer-events: none;
  opacity: 0;
  transition: opacity .15s;
  white-space: nowrap;
  z-index: 10;
}
[data-edit-admin]:hover::after {
  opacity: 1;
}
`;

let activeEl = null;

function init() {
	if (!document.getElementById("wpadminbar")) return;

	const style = document.createElement("style");
	style.textContent = PLE_CSS;
	document.head.appendChild(style);

	// "Edit Mode" chip
	const chip = document.createElement("div");
	chip.className = "ple-chip";
	chip.setAttribute("aria-hidden", "true");
	chip.innerHTML = `
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
    </svg>
    Edit Mode`;
	document.body.appendChild(chip);

	// Modal backdrop + dialog
	const backdrop = document.createElement("div");
	backdrop.className = "ple-backdrop";
	backdrop.setAttribute("role", "presentation");
	backdrop.innerHTML = `
    <div class="ple-modal" role="dialog" aria-modal="true" aria-labelledby="ple-label">
      <div id="ple-label" class="ple-modal-label"></div>
      <div id="ple-field-wrap"></div>
      <p class="ple-hint" id="ple-hint" hidden>HTML markup is supported</p>
      <div class="ple-actions">
        <span class="ple-status" id="ple-status"></span>
        <button class="ple-btn ple-btn-cancel" id="ple-cancel" type="button">Cancel</button>
        <button class="ple-btn ple-btn-save" id="ple-save" type="button">Save</button>
      </div>
    </div>`;
	document.body.appendChild(backdrop);

	const labelEl = document.getElementById("ple-label");
	const fieldWrap = document.getElementById("ple-field-wrap");
	const hintEl = document.getElementById("ple-hint");
	const statusEl = document.getElementById("ple-status");
	const saveBtn = document.getElementById("ple-save");
	const cancelBtn = document.getElementById("ple-cancel");

	function closeModal() {
		backdrop.classList.remove("open");
		activeEl = null;
		statusEl.textContent = "";
		statusEl.className = "ple-status";
		fieldWrap.innerHTML = "";
	}

	function openModal(el) {
		activeEl = el;
		const type = el.dataset.editType || "text";
		const label =
			el.dataset.editLabel || el.dataset.editField || "Edit field";
		const isHtml = type === "wysiwyg";
		const isNl2br = type === "nl2br";
		const current = isHtml
			? el.innerHTML.trim()
			: isNl2br
				? (el.innerText ?? el.textContent).trim()
				: el.textContent.trim();

		labelEl.textContent = label;
		hintEl.hidden = true;
		fieldWrap.innerHTML = "";
		statusEl.textContent = "";
		statusEl.className = "ple-status";

		if (type === "select") {
			const choices = JSON.parse(el.dataset.editChoices || "{}");
			const currentVal = el.dataset.editValue || current;
			const sel = document.createElement("select");
			sel.id = "ple-field";
			sel.className = "ple-select";
			Object.entries(choices).forEach(([val, lbl]) => {
				const opt = document.createElement("option");
				opt.value = val;
				opt.textContent = lbl;
				opt.selected = val === currentVal;
				sel.appendChild(opt);
			});
			fieldWrap.appendChild(sel);
		} else if (isHtml) {
			// Rich text editor: toolbar + contenteditable div
			const wrap = document.createElement("div");
			wrap.className = "ple-rte-wrap";

			const toolbar = document.createElement("div");
			toolbar.className = "ple-rte-toolbar";

			function toolBtn(label, title, cmd, arg) {
				const btn = document.createElement("button");
				btn.type = "button";
				btn.textContent = label;
				btn.title = title;
				btn.addEventListener("mousedown", (e) => {
					e.preventDefault(); // keep focus in editor
					if (cmd === "createLink") {
						const url = prompt("Enter URL:");
						if (url) document.execCommand("createLink", false, url);
					} else if (cmd === "removeLink") {
						document.execCommand("unlink", false, null);
					} else {
						document.execCommand(cmd, false, arg ?? null);
					}
				});
				return btn;
			}

			toolbar.appendChild(toolBtn("B", "Bold", "bold"));
			toolbar.appendChild(toolBtn("I", "Italic", "italic"));
			toolbar.appendChild(toolBtn("U", "Underline", "underline"));

			const sep = document.createElement("div");
			sep.className = "sep";

			const editor = document.createElement("div");
			editor.id = "ple-field";
			editor.className = "ple-rte";
			editor.contentEditable = "true";
			editor.innerHTML = current;

			wrap.appendChild(toolbar);
			wrap.appendChild(editor);
			fieldWrap.appendChild(wrap);
		} else if (type === "textarea" || type === "nl2br") {
			const ta = document.createElement("textarea");
			ta.id = "ple-field";
			ta.className = "ple-textarea";
			ta.rows = 6;
			ta.value = current;
			fieldWrap.appendChild(ta);
		} else {
			const inp = document.createElement("input");
			inp.id = "ple-field";
			inp.className = "ple-input";
			inp.type = type === "url" ? "url" : "text";
			inp.value = current;
			fieldWrap.appendChild(inp);
		}

		backdrop.classList.add("open");
		requestAnimationFrame(() =>
			document.getElementById("ple-field")?.focus(),
		);
	}

	async function save() {
		if (!activeEl) return;

		const fieldEl = document.getElementById("ple-field");
		const fieldName = activeEl.dataset.editField;
		const fieldType = activeEl.dataset.editType || "text";
		const postId = parseInt(
			activeEl.dataset.editPost || window.planetarioEditor?.postId || "0",
			10,
		);
		const value =
			fieldEl.contentEditable === "true"
				? fieldEl.innerHTML
				: fieldEl.value;

		if (!postId || !fieldName) {
			statusEl.textContent = "Missing field or post ID.";
			statusEl.className = "ple-status error";
			return;
		}

		saveBtn.disabled = true;
		cancelBtn.disabled = true;
		statusEl.textContent = "Saving…";
		statusEl.className = "ple-status";

		try {
			const res = await fetch(window.planetarioEditor.apiUrl, {
				method: "POST",
				credentials: "same-origin",
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": window.planetarioEditor.nonce,
				},
				body: JSON.stringify({
					post_id: postId,
					field_name: fieldName,
					field_type: fieldType,
					value,
				}),
			});

			const json = await res.json();

			if (json.success) {
				document.dispatchEvent(
					new CustomEvent("ple:saved", {
						detail: { field: fieldName, value: json.value },
					}),
				);

				if (fieldType === "select") {
					const choices = JSON.parse(
						activeEl.dataset.editChoices || "{}",
					);
					activeEl.dataset.editValue = json.value;
					const labelSpan = activeEl.querySelector(
						".hero-admin-btn-label",
					);
					if (labelSpan) {
						labelSpan.textContent =
							choices[json.value] || json.value;
					}
					statusEl.textContent = "Saved!";
					statusEl.className = "ple-status ok";
					setTimeout(closeModal, 800);
				} else if (fieldType === "wysiwyg") {
					activeEl.innerHTML = json.value ?? value;
					statusEl.textContent = "Saved!";
					statusEl.className = "ple-status ok";
					setTimeout(closeModal, 800);
				} else {
					activeEl.textContent = json.value ?? value;
					statusEl.textContent = "Saved!";
					statusEl.className = "ple-status ok";
					setTimeout(closeModal, 800);
				}
			} else {
				throw new Error(json.message || "Save failed.");
			}
		} catch (err) {
			statusEl.textContent = err.message || "An error occurred.";
			statusEl.className = "ple-status error";
		} finally {
			saveBtn.disabled = false;
			cancelBtn.disabled = false;
		}
	}

	saveBtn.addEventListener("click", save);
	cancelBtn.addEventListener("click", closeModal);

	// Close on backdrop click (outside modal)
	backdrop.addEventListener("click", (e) => {
		if (e.target === backdrop) closeModal();
	});

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape" && backdrop.classList.contains("open"))
			closeModal();
	});

	// Wire up all editable elements
	document.querySelectorAll("[data-edit-field]").forEach((el) => {
		el.addEventListener("click", (e) => {
			e.stopPropagation();
			openModal(el);
		});
	});

	// Admin-link sections — open WP Admin on click
	document.querySelectorAll("[data-edit-admin]").forEach((el) => {
		el.addEventListener("click", (e) => {
			e.stopPropagation();
			const path = el.dataset.editAdmin;
			const base = window.planetarioEditor?.adminUrl || "/wp-admin/";
			window.open(base + path, "_blank");
		});
	});
}

document.addEventListener("DOMContentLoaded", init);
