/* ============================================================
   Planetario — front-end interactions
   ============================================================ */

// Dark / light theme toggle.
// Effective mode resolution: localStorage > admin default > OS preference.
// The preload script in <head> already applies stored localStorage to <html data-theme>
// before paint, so this just handles the click and keeps storage in sync.
const themeToggle = document.getElementById("theme-toggle");
if (themeToggle) {
	const html = document.documentElement;
	const preload = document.getElementById("planetario-theme-preload");
	const adminDefault = (preload && preload.dataset.default) || "auto";

	const osLight = () =>
		window.matchMedia &&
		window.matchMedia("(prefers-color-scheme: light)").matches;

	const currentMode = () => {
		const explicit = html.getAttribute("data-theme");
		if (explicit === "dark" || explicit === "light") return explicit;
		if (adminDefault === "dark" || adminDefault === "light")
			return adminDefault;
		return osLight() ? "light" : "dark";
	};

	themeToggle.addEventListener("click", () => {
		const next = currentMode() === "dark" ? "light" : "dark";
		html.setAttribute("data-theme", next);
		try {
			localStorage.setItem("planetarioTheme", next);
		} catch (e) {}
	});
}

// Nav scroll state
const nav = document.querySelector(".nav");
if (nav) {
	const tick = () => nav.classList.toggle("scrolled", window.scrollY > 30);
	tick();
	window.addEventListener("scroll", tick, { passive: true });
}

// Mobile menu toggle
const menuToggle = document.querySelector(".menu-toggle");
const navLinks = document.querySelector(".nav-links");
if (menuToggle && navLinks) {
	const iconMenu = menuToggle.querySelector(".icon-menu");
	const iconClose = menuToggle.querySelector(".icon-close");

	const setOpen = (open) => {
		navLinks.classList.toggle("open", open);
		if (iconMenu) iconMenu.style.display = open ? "none" : "";
		if (iconClose) iconClose.style.display = open ? "" : "none";
		menuToggle.setAttribute("aria-expanded", String(open));
	};

	menuToggle.addEventListener("click", () =>
		setOpen(!navLinks.classList.contains("open")),
	);
	navLinks
		.querySelectorAll(".nav-link")
		.forEach((link) =>
			link.addEventListener("click", () => setOpen(false)),
		);
}

// Scroll reveal (IntersectionObserver)
function initReveal() {
	const targets = document.querySelectorAll(".reveal, .stagger-children");
	if (!targets.length) return;

	// Fallback: if IntersectionObserver isn't supported, show everything.
	if (!("IntersectionObserver" in window)) {
		targets.forEach((el) => el.classList.add("in"));
		return;
	}

	const io = new IntersectionObserver(
		(entries, obs) =>
			entries.forEach((e) => {
				if (e.isIntersecting) {
					e.target.classList.add("in");
					obs.unobserve(e.target);
				}
			}),
		{ rootMargin: "0px 0px -10% 0px", threshold: 0.1 },
	);

	targets.forEach((el) => io.observe(el));
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initReveal);
} else {
	initReveal();
}

// CountUp — triggered when element enters viewport
document.querySelectorAll("[data-countup]").forEach((el) => {
	const target = parseFloat(el.dataset.countup);
	const decimals = parseInt(el.dataset.decimals ?? "0", 10);
	const suffix = el.dataset.suffix ?? "";
	const prefix = el.dataset.prefix ?? "";
	const duration = 5000;

	const io = new IntersectionObserver(
		(entries) => {
			entries.forEach((e) => {
				if (!e.isIntersecting) return;
				io.disconnect();
				const start = performance.now();
				const tick = (t) => {
					const p = Math.min(1, (t - start) / duration);
					const eased = 1 - Math.pow(1 - p, 3);
					const n = target * eased;
					el.textContent =
						prefix +
						(decimals > 0
							? n.toFixed(decimals)
							: String(Math.round(n))) +
						suffix;
					if (p < 1) requestAnimationFrame(tick);
				};
				requestAnimationFrame(tick);
			});
		},
		{ threshold: 0.4 },
	);

	io.observe(el);
});
