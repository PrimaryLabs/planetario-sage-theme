/**
 * Planetario Inline Editor
 * Activates only when #wpadminbar is present (WP admin logged in on front-end).
 * Click any highlighted element to edit its ACF field content.
 */

const PLE_CSS = `
[data-edit-field],
[data-edit-type="button"] {
  cursor: pointer;
}
[data-edit-field]:hover,
[data-edit-type="button"]:hover {
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

const ICON_CHOICES = {
	"": "None",
	arrow: "Arrow →",
	phone: "Phone",
	mail: "Email",
	"map-pin": "Map pin",
	external: "External link",
};

const ICON_SVGS = {
	arrow: `<svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
	phone: `<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
	mail: `<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
	"map-pin": `<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
	external: `<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><polyline points="15,3 21,3 21,9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><line x1="10" y1="14" x2="21" y2="3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
};

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

		if (type === "button") {
			const btnLabel =
				el.querySelector(".btn-text")?.textContent.trim() ?? "";
			const btnUrl = el.getAttribute("href") ?? "";
			const btnIcon = el.dataset.editIconValue ?? "";

			const row = (id, labelText, inputEl) => {
				const lbl = document.createElement("label");
				lbl.htmlFor = id;
				lbl.className = "ple-modal-label";
				lbl.style.display = "block";
				lbl.style.marginBottom = "4px";
				lbl.textContent = labelText;
				const wrap = document.createElement("div");
				wrap.style.marginBottom = "10px";
				wrap.appendChild(lbl);
				wrap.appendChild(inputEl);
				return wrap;
			};

			const txtInp = document.createElement("input");
			txtInp.id = "ple-btn-label";
			txtInp.className = "ple-input";
			txtInp.type = "text";
			txtInp.value = btnLabel;

			const urlInp = document.createElement("input");
			urlInp.id = "ple-btn-url";
			urlInp.className = "ple-input";
			urlInp.type = "url";
			urlInp.value = btnUrl;

			const iconSel = document.createElement("select");
			iconSel.id = "ple-btn-icon";
			iconSel.className = "ple-select";
			Object.entries(ICON_CHOICES).forEach(([val, lbl]) => {
				const opt = document.createElement("option");
				opt.value = val;
				opt.textContent = lbl;
				opt.selected = val === btnIcon;
				iconSel.appendChild(opt);
			});

			fieldWrap.appendChild(row("ple-btn-label", "Button text", txtInp));
			fieldWrap.appendChild(row("ple-btn-url", "URL", urlInp));
			fieldWrap.appendChild(
				row("ple-btn-icon", "Icon (optional)", iconSel),
			);
		} else if (type === "select") {
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
		requestAnimationFrame(() => {
			const focus =
				type === "button"
					? document.getElementById("ple-btn-label")
					: document.getElementById("ple-field");
			focus?.focus();
		});
	}

	async function saveField(postId, fieldName, fieldType, value) {
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
		if (!json.success) throw new Error(json.message || "Save failed.");
		return json.value ?? value;
	}

	async function save() {
		if (!activeEl) return;

		const fieldType = activeEl.dataset.editType || "text";
		const postIdRaw =
			activeEl.dataset.editPost ||
			String(window.planetarioEditor?.postId || "0");
		const postId =
			postIdRaw === "option" ? "option" : parseInt(postIdRaw, 10);

		saveBtn.disabled = true;
		cancelBtn.disabled = true;
		statusEl.textContent = "Saving…";
		statusEl.className = "ple-status";

		try {
			if (fieldType === "button") {
				const newLabel =
					document.getElementById("ple-btn-label")?.value ?? "";
				const newUrl =
					document.getElementById("ple-btn-url")?.value ?? "";
				const newIcon =
					document.getElementById("ple-btn-icon")?.value ?? "";

				const labelField = activeEl.dataset.editLabelField;
				const urlField = activeEl.dataset.editUrlField;
				const iconField = activeEl.dataset.editIconField;

				if ((!postId && postId !== "option") || !labelField) {
					throw new Error("Missing field or post ID.");
				}

				await saveField(postId, labelField, "text", newLabel);
				await saveField(postId, urlField, "url", newUrl);
				await saveField(postId, iconField, "select", newIcon);

				const textSpan = activeEl.querySelector(".btn-text");
				if (textSpan) textSpan.textContent = newLabel;
				activeEl.href = newUrl;
				activeEl.dataset.editIconValue = newIcon;
				const oldSvg = activeEl.querySelector("svg.arr");
				if (oldSvg) oldSvg.remove();
				if (ICON_SVGS[newIcon]) {
					activeEl.insertAdjacentHTML(
						"beforeend",
						ICON_SVGS[newIcon],
					);
				}

				document.dispatchEvent(
					new CustomEvent("ple:saved", {
						detail: { field: labelField, value: newLabel },
					}),
				);
				statusEl.textContent = "Saved!";
				statusEl.className = "ple-status ok";
				setTimeout(closeModal, 800);
				return;
			}

			const fieldEl = document.getElementById("ple-field");
			const fieldName = activeEl.dataset.editField;
			const value =
				fieldEl.contentEditable === "true"
					? fieldEl.innerHTML
					: fieldEl.value;

			if ((!postId && postId !== "option") || !fieldName) {
				throw new Error("Missing field or post ID.");
			}

			const saved = await saveField(postId, fieldName, fieldType, value);

			document.dispatchEvent(
				new CustomEvent("ple:saved", {
					detail: { field: fieldName, value: saved },
				}),
			);

			if (fieldType === "select") {
				const choices = JSON.parse(
					activeEl.dataset.editChoices || "{}",
				);
				activeEl.dataset.editValue = saved;
				const labelSpan = activeEl.querySelector(
					".hero-admin-btn-label",
				);
				if (labelSpan) {
					labelSpan.textContent = choices[saved] || saved;
				}
				statusEl.textContent = "Saved!";
				statusEl.className = "ple-status ok";
				setTimeout(closeModal, 800);
			} else if (fieldType === "wysiwyg") {
				activeEl.innerHTML = saved;
				statusEl.textContent = "Saved!";
				statusEl.className = "ple-status ok";
				setTimeout(closeModal, 800);
			} else {
				if (activeEl.dataset.editHrefPrefix !== undefined) {
					activeEl.href = activeEl.dataset.editHrefPrefix + saved;
				}
				activeEl.textContent = saved;
				statusEl.textContent = "Saved!";
				statusEl.className = "ple-status ok";
				setTimeout(closeModal, 800);
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
			e.preventDefault();
			openModal(el);
		});
	});

	// Wire up button edit elements (prevent navigation, open multi-field modal)
	document.querySelectorAll("[data-edit-type='button']").forEach((el) => {
		el.addEventListener("click", (e) => {
			e.preventDefault();
			e.stopPropagation();
			openModal(el);
		});
	});

	// Admin-link sections — open WP Admin on click
	document.querySelectorAll("[data-edit-admin]").forEach((el) => {
		el.addEventListener("click", (e) => {
			e.stopPropagation();
			e.preventDefault();
			const path = el.dataset.editAdmin;
			const base = window.planetarioEditor?.adminUrl || "/wp-admin/";
			window.open(base + path, "_blank");
		});
	});
}

document.addEventListener("DOMContentLoaded", init);
