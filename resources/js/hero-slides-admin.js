/**
 * Hero Slides Admin UI
 * Manages the hero slideshow images on the front-page edit screen.
 */
(function () {
	"use strict";

	const TRANSITIONS = [
		{ value: "crossfade", label: "Crossfade" },
		{ value: "slide",     label: "Slide" },
		{ value: "zoom",      label: "Zoom" },
	];

	const CSS = `
#hero-slides-root {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}
.hsm-grid {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 12px;
  min-height: 40px;
}
.hsm-empty {
  color: #999;
  font-size: 13px;
  padding: 12px 0;
}
.hsm-item {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 8px 10px;
  cursor: default;
}
.hsm-item.hsm-drag-over {
  border-color: #2271b1;
  background: #f0f6fc;
}
.hsm-handle {
  cursor: grab;
  color: #bbb;
  font-size: 18px;
  line-height: 1;
  user-select: none;
  flex-shrink: 0;
}
.hsm-handle:active { cursor: grabbing; }
.hsm-thumb {
  width: 72px;
  height: 54px;
  object-fit: cover;
  border-radius: 4px;
  flex-shrink: 0;
  background: #eee;
}
.hsm-meta {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 8px;
}
.hsm-label {
  font-size: 12px;
  color: #555;
  white-space: nowrap;
}
.hsm-select {
  font-size: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 3px 6px;
  background: #fff;
  cursor: pointer;
}
.hsm-remove {
  background: none;
  border: none;
  color: #999;
  font-size: 18px;
  line-height: 1;
  cursor: pointer;
  padding: 0 4px;
  flex-shrink: 0;
  transition: color 0.12s;
}
.hsm-remove:hover { color: #d63638; }
.hsm-add-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #2271b1;
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 7px 14px;
  font-size: 13px;
  cursor: pointer;
  transition: background 0.15s;
}
.hsm-add-btn:hover { background: #135e96; }
`;

	function injectStyles() {
		if (document.getElementById("hsm-styles")) return;
		const style = document.createElement("style");
		style.id = "hsm-styles";
		style.textContent = CSS;
		document.head.appendChild(style);
	}

	let slides = [];
	let dragSrcIndex = null;

	function getInput() {
		return document.getElementById("hero-slides-data");
	}

	function syncInput() {
		const input = getInput();
		if (input) input.value = JSON.stringify(slides);
	}

	function buildSelect(current) {
		const sel = document.createElement("select");
		sel.className = "hsm-select";
		TRANSITIONS.forEach(({ value, label }) => {
			const opt = document.createElement("option");
			opt.value = value;
			opt.textContent = label;
			opt.selected = value === current;
			sel.appendChild(opt);
		});
		return sel;
	}

	function render() {
		const root = document.getElementById("hero-slides-root");
		if (!root) return;
		root.innerHTML = "";

		const grid = document.createElement("div");
		grid.className = "hsm-grid";

		if (slides.length === 0) {
			const empty = document.createElement("p");
			empty.className = "hsm-empty";
			empty.textContent = "No slides yet — click Add Photos to get started.";
			grid.appendChild(empty);
		} else {
			slides.forEach((slide, index) => {
				const item = document.createElement("div");
				item.className = "hsm-item";
				item.draggable = true;

				// Drag handle
				const handle = document.createElement("span");
				handle.className = "hsm-handle";
				handle.textContent = "⠿";
				handle.title = "Drag to reorder";

				// Thumbnail
				const thumb = document.createElement("img");
				thumb.className = "hsm-thumb";
				thumb.src = slide.url;
				thumb.alt = slide.alt || "";

				// Meta (label + select)
				const meta = document.createElement("div");
				meta.className = "hsm-meta";

				const label = document.createElement("span");
				label.className = "hsm-label";
				label.textContent = `Slide ${index + 1}`;

				const sel = buildSelect(slide.transition || "crossfade");
				sel.addEventListener("change", () => {
					slides[index].transition = sel.value;
					syncInput();
				});

				meta.appendChild(label);
				meta.appendChild(sel);

				// Remove button
				const rm = document.createElement("button");
				rm.type = "button";
				rm.className = "hsm-remove";
				rm.textContent = "✕";
				rm.title = "Remove slide";
				rm.addEventListener("click", () => {
					slides.splice(index, 1);
					render();
					syncInput();
				});

				// Drag events
				item.addEventListener("dragstart", (e) => {
					dragSrcIndex = index;
					e.dataTransfer.effectAllowed = "move";
				});
				item.addEventListener("dragover", (e) => {
					e.preventDefault();
					e.dataTransfer.dropEffect = "move";
					item.classList.add("hsm-drag-over");
				});
				item.addEventListener("dragleave", () => {
					item.classList.remove("hsm-drag-over");
				});
				item.addEventListener("drop", (e) => {
					e.preventDefault();
					item.classList.remove("hsm-drag-over");
					if (dragSrcIndex === null || dragSrcIndex === index) return;
					const moved = slides.splice(dragSrcIndex, 1)[0];
					slides.splice(index, 0, moved);
					dragSrcIndex = null;
					render();
					syncInput();
				});
				item.addEventListener("dragend", () => {
					dragSrcIndex = null;
					document.querySelectorAll(".hsm-item").forEach((el) =>
						el.classList.remove("hsm-drag-over"),
					);
				});

				item.appendChild(handle);
				item.appendChild(thumb);
				item.appendChild(meta);
				item.appendChild(rm);
				grid.appendChild(item);
			});
		}

		// Add Photos button
		const addBtn = document.createElement("button");
		addBtn.type = "button";
		addBtn.className = "hsm-add-btn";
		addBtn.innerHTML = "+ Add Photos";
		addBtn.addEventListener("click", openMediaPicker);

		root.appendChild(grid);
		root.appendChild(addBtn);
	}

	function openMediaPicker() {
		if (typeof wp === "undefined" || !wp.media) {
			alert("Media library unavailable. Please refresh and try again.");
			return;
		}

		const frame = wp.media({
			title: "Select hero background images",
			button: { text: "Add to slideshow" },
			multiple: true,
			library: { type: "image" },
		});

		frame.on("select", () => {
			const existing = new Set(slides.map((s) => String(s.id)));
			const attachments = frame.state().get("selection").toJSON();

			attachments.forEach((att) => {
				if (existing.has(String(att.id))) return;
				const url =
					att.sizes?.large?.url ||
					att.sizes?.medium_large?.url ||
					att.sizes?.medium?.url ||
					att.url;
				slides.push({
					id:         att.id,
					url:        url,
					alt:        att.alt || att.title || "",
					transition: "crossfade",
				});
			});

			render();
			syncInput();
		});

		frame.open();
	}

	function init() {
		const root = document.getElementById("hero-slides-root");
		if (!root) return;

		injectStyles();

		const raw = root.dataset.slides || "[]";
		try {
			slides = JSON.parse(raw) || [];
		} catch {
			slides = [];
		}

		render();
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})();
