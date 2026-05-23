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
		.querySelectorAll(".nav-link:not(.nav-link--has-dropdown)")
		.forEach((link) =>
			link.addEventListener("click", () => setOpen(false)),
		);
}

// Stories dropdown
function initNavDropdown() {
	const wraps = document.querySelectorAll(".nav-dropdown-wrap");
	if (!wraps.length) return;

	const isMobile = () => window.innerWidth <= 980;

	wraps.forEach((wrap) => {
		const btn = wrap.querySelector(".nav-link--has-dropdown");
		if (!btn) return;

		btn.addEventListener("click", (e) => {
			if (isMobile()) {
				e.stopPropagation();
				const opening = !wrap.classList.contains("mobile-open");
				wrap.classList.toggle("mobile-open", opening);
				btn.setAttribute("aria-expanded", String(opening));
			} else {
				const opening = !wrap.classList.contains("open");
				wrap.classList.toggle("open", opening);
				btn.setAttribute("aria-expanded", String(opening));
			}
		});
	});

	document.addEventListener("click", (e) => {
		if (!isMobile()) {
			wraps.forEach((wrap) => {
				if (!wrap.contains(e.target)) {
					wrap.classList.remove("open");
					const btn = wrap.querySelector(".nav-link--has-dropdown");
					if (btn) btn.setAttribute("aria-expanded", "false");
				}
			});
		}
	});

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape") {
			wraps.forEach((wrap) => {
				const wasOpen = wrap.classList.contains("open");
				wrap.classList.remove("open");
				if (wasOpen) {
					const btn = wrap.querySelector(".nav-link--has-dropdown");
					if (btn) {
						btn.setAttribute("aria-expanded", "false");
						btn.focus();
					}
				}
			});
		}
	});
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initNavDropdown);
} else {
	initNavDropdown();
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

// Testimonials slider
function initTestimonialSlider() {
	const track = document.getElementById("testiTrack");
	if (!track) return;

	const slides = Array.from(track.querySelectorAll(".testi-slide"));
	const dots = Array.from(document.querySelectorAll("[data-testi-dot]"));
	const prevBtn = document.getElementById("testiPrev");
	const nextBtn = document.getElementById("testiNext");
	const sliderEl = document.getElementById("testiSlider");
	const total = slides.length;

	if (total < 2) {
		if (slides[0]) {
			slides[0].style.opacity = "1";
			slides[0].style.pointerEvents = "auto";
		}
		return;
	}

	let current = 0;
	let busy = false;
	let autoTimer;
	const AUTOPLAY_MS = 5500;
	const EASING = "cubic-bezier(.4,0,.2,1)";

	// Init — show first slide immediately
	slides.forEach((s, i) => {
		s.style.opacity = i === 0 ? "1" : "0";
		s.style.transform = "translateX(0)";
		s.style.pointerEvents = i === 0 ? "auto" : "none";
	});
	// Animate first slide's quote icon in
	const firstIcon = slides[0].querySelector(".testi-quote-icon");
	if (firstIcon) {
		firstIcon.style.opacity = "0";
		firstIcon.style.transform = "translateY(14px) scale(0.85)";
		setTimeout(() => {
			firstIcon.style.transition =
				"opacity .6s ease, transform .6s cubic-bezier(.2,.7,.2,1)";
			firstIcon.style.opacity = "0.22";
			firstIcon.style.transform = "none";
		}, 200);
	}

	function syncHeight() {
		const maxH = Math.max(...slides.map((s) => s.offsetHeight));
		if (maxH > 0) track.style.minHeight = maxH + "px";
	}
	syncHeight();
	setTimeout(syncHeight, 500);
	window.addEventListener("resize", syncHeight, { passive: true });

	function goTo(next, dir) {
		if (next === current || busy) return;
		busy = true;

		const from = slides[current];
		const to = slides[next];

		// Snap entering slide off-screen with no transition
		to.style.transition = "none";
		to.style.opacity = "0";
		to.style.transform = `translateX(${dir > 0 ? "60px" : "-60px"})`;
		to.style.pointerEvents = "none";

		// Force repaint so the snap registers before transitioning
		to.getBoundingClientRect();

		// Slide out the current
		from.style.transition = `opacity .48s ${EASING}, transform .52s ${EASING}`;
		from.style.opacity = "0";
		from.style.transform = `translateX(${dir > 0 ? "-60px" : "60px"})`;
		from.style.pointerEvents = "none";

		// Slide in the next
		to.style.transition = `opacity .48s ${EASING}, transform .52s ${EASING}`;
		to.style.opacity = "1";
		to.style.transform = "translateX(0)";
		to.style.pointerEvents = "auto";

		// Animate quote icon in incoming slide
		const qIcon = to.querySelector(".testi-quote-icon");
		if (qIcon) {
			qIcon.style.transition = "none";
			qIcon.style.opacity = "0";
			qIcon.style.transform = "translateY(14px) scale(0.85)";
			setTimeout(() => {
				qIcon.style.transition =
					"opacity .6s ease, transform .6s cubic-bezier(.2,.7,.2,1)";
				qIcon.style.opacity = "0.22";
				qIcon.style.transform = "none";
			}, 120);
		}

		// Update dots
		dots[current].classList.remove("active");
		dots[current].setAttribute("aria-selected", "false");
		dots[next].classList.add("active");
		dots[next].setAttribute("aria-selected", "true");

		// Update ARIA on slides
		from.setAttribute("aria-hidden", "true");
		to.removeAttribute("aria-hidden");

		current = next;
		setTimeout(() => {
			busy = false;
		}, 600);
	}

	const next = () => goTo((current + 1) % total, 1);
	const prev = () => goTo((current - 1 + total) % total, -1);

	const startAuto = () => {
		autoTimer = setInterval(next, AUTOPLAY_MS);
	};
	const stopAuto = () => clearInterval(autoTimer);

	startAuto();

	if (sliderEl) {
		sliderEl.addEventListener("mouseenter", stopAuto);
		sliderEl.addEventListener("mouseleave", startAuto);
		sliderEl.addEventListener("focusin", stopAuto);
		sliderEl.addEventListener("focusout", startAuto);
		sliderEl.addEventListener("keydown", (e) => {
			if (e.key === "ArrowRight") {
				stopAuto();
				next();
				startAuto();
			}
			if (e.key === "ArrowLeft") {
				stopAuto();
				prev();
				startAuto();
			}
		});
	}

	if (nextBtn) {
		nextBtn.addEventListener("click", () => {
			stopAuto();
			next();
			startAuto();
		});
	}
	if (prevBtn) {
		prevBtn.addEventListener("click", () => {
			stopAuto();
			prev();
			startAuto();
		});
	}

	dots.forEach((dot, i) => {
		dot.addEventListener("click", () => {
			stopAuto();
			goTo(i, i > current ? 1 : -1);
			startAuto();
		});
	});

	// Touch/swipe
	let touchStartX = 0;
	track.addEventListener(
		"touchstart",
		(e) => {
			touchStartX = e.touches[0].clientX;
		},
		{ passive: true },
	);
	track.addEventListener(
		"touchend",
		(e) => {
			const dx = e.changedTouches[0].clientX - touchStartX;
			if (Math.abs(dx) > 40) {
				stopAuto();
				dx < 0 ? next() : prev();
				startAuto();
			}
		},
		{ passive: true },
	);
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initTestimonialSlider);
} else {
	initTestimonialSlider();
}

// Managers / Staff — tabbed scroll strip with snap + arrow buttons
// Each tablist is scoped to its own <section> so Managers and Staff tabs
// never interfere with each other's panels.
function initManagerTabs() {
	document
		.querySelectorAll(".managers-tabs[role='tablist']")
		.forEach((tablist) => {
			const section = tablist.closest("section");
			if (!section) return;

			const tabs = tablist.querySelectorAll("[data-managers-tab]");
			const panels = section.querySelectorAll("[data-managers-panel]");

			tabs.forEach((tab) => {
				tab.addEventListener("click", () => {
					const target = tab.dataset.managersTab;

					tabs.forEach((t) => {
						t.classList.remove("is-active");
						t.setAttribute("aria-selected", "false");
					});
					panels.forEach((p) => p.classList.remove("is-active"));

					tab.classList.add("is-active");
					tab.setAttribute("aria-selected", "true");

					const panel = section.querySelector(
						`[data-managers-panel="${target}"]`,
					);
					if (panel) panel.classList.add("is-active");
				});
			});

			panels.forEach((panel) => {
				const strip = panel.querySelector("[data-managers-strip]");
				const prevBtn = panel.querySelector(".managers-arrow--prev");
				const nextBtn = panel.querySelector(".managers-arrow--next");
				if (!strip) return;

				const CARD_W = 210 + 24; // card width + gap

				const syncArrows = () => {
					const atStart = strip.scrollLeft <= 2;
					const atEnd =
						strip.scrollLeft >=
						strip.scrollWidth - strip.clientWidth - 2;
					prevBtn?.classList.toggle("is-hidden", atStart);
					nextBtn?.classList.toggle("is-hidden", atEnd);
				};

				strip.addEventListener("scroll", syncArrows, { passive: true });
				syncArrows();

				prevBtn?.addEventListener("click", () => {
					strip.scrollBy({ left: -CARD_W * 2, behavior: "smooth" });
				});
				nextBtn?.addEventListener("click", () => {
					strip.scrollBy({ left: CARD_W * 2, behavior: "smooth" });
				});
			});
		});
}

function initDevFilter() {
	document.querySelectorAll("[data-dev-filter-bar]").forEach((bar) => {
		const section = bar.closest("section");
		if (!section) return;

		const buttons = bar.querySelectorAll("[data-dev-filter]");
		const items = section.querySelectorAll("[data-dev-region]");

		buttons.forEach((btn) => {
			btn.addEventListener("click", () => {
				const filter = btn.dataset.devFilter; // "all" | "bohol" | "cebu"

				buttons.forEach((b) => {
					b.classList.remove("is-active");
					b.setAttribute("aria-selected", "false");
				});
				btn.classList.add("is-active");
				btn.setAttribute("aria-selected", "true");

				items.forEach((item) => {
					const show = filter === "all" || item.dataset.devRegion === filter;
					item.style.display = show ? "" : "none";
				});
			});
		});
	});
}

function initContentTabs() {
	document
		.querySelectorAll(".content-tabs[role='tablist']")
		.forEach((tablist) => {
			const section = tablist.closest("section");
			if (!section) return;

			const tabs   = tablist.querySelectorAll("[data-content-tab]");
			const panels = section.querySelectorAll("[data-content-panel]");

			tabs.forEach((tab) => {
				tab.addEventListener("click", () => {
					const target = tab.dataset.contentTab;

					tabs.forEach((t) => {
						t.classList.remove("is-active");
						t.setAttribute("aria-selected", "false");
					});
					panels.forEach((p) => p.classList.remove("is-active"));

					tab.classList.add("is-active");
					tab.setAttribute("aria-selected", "true");

					const panel = section.querySelector(
						`[data-content-panel="${target}"]`,
					);
					if (panel) panel.classList.add("is-active");
				});
			});
		});
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", () => {
		initManagerTabs();
		initDevFilter();
		initContentTabs();
	});
} else {
	initManagerTabs();
	initDevFilter();
	initContentTabs();
}

// Hero parallax — background scrolls at 28% of page scroll speed
function initParallax() {
	const heroBg = document.querySelector(".hero-bg");
	if (!heroBg) return;

	const hero = heroBg.closest(".hero");
	let ticking = false;

	window.addEventListener(
		"scroll",
		() => {
			if (ticking) return;
			ticking = true;
			requestAnimationFrame(() => {
				const scrollY = window.scrollY;
				// No-op once hero is fully off screen
				if (hero && scrollY > hero.offsetHeight) {
					ticking = false;
					return;
				}
				heroBg.style.transform = `translateY(${scrollY * 0.28}px)`;
				ticking = false;
			});
		},
		{ passive: true },
	);
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initParallax);
} else {
	initParallax();
}

// Office gallery — main preview + thumbnail rail
function initOfficeGallery() {
	document.querySelectorAll("[data-og]").forEach((gallery) => {
		const mainImg = gallery.querySelector(".og__main");
		const thumbs = Array.from(gallery.querySelectorAll("[data-og-thumb]"));

		if (!mainImg || thumbs.length < 2) return;

		let current = 0;
		const FADE_MS = 150;
		const AUTOPLAY_MS = 3000;
		const INTERACT_RESUME_MS = 10000;

		let intervalId = null;
		let resumeId = null;

		function goTo(index) {
			if (index === current) return;

			mainImg.classList.add("is-switching");

			setTimeout(() => {
				mainImg.src = thumbs[index].dataset.ogSrc;
				mainImg.alt = thumbs[index].dataset.ogAlt;
				mainImg.classList.remove("is-switching");
			}, FADE_MS);

			thumbs[current].classList.remove("is-active");
			thumbs[current].setAttribute("aria-pressed", "false");
			thumbs[index].classList.add("is-active");
			thumbs[index].setAttribute("aria-pressed", "true");

			thumbs[index].scrollIntoView({
				block: "nearest",
				inline: "nearest",
				behavior: "smooth",
			});

			current = index;
		}

		const next = () => goTo((current + 1) % thumbs.length);

		// Always clears both the running interval and any pending resume timeout
		// before (re)starting — prevents timer stacking on rapid events.
		const stopAuto = () => {
			clearInterval(intervalId);
			clearTimeout(resumeId);
			intervalId = resumeId = null;
		};

		const startAuto = (delay = 0) => {
			stopAuto();
			const kick = () => {
				intervalId = setInterval(next, AUTOPLAY_MS);
			};
			if (delay > 0) {
				resumeId = setTimeout(kick, delay);
			} else {
				kick();
			}
		};

		startAuto();

		// Hover pauses immediately and resumes immediately on leave.
		// focusin/focusout are intentionally omitted — they fire during clicks
		// and would cancel the manual-interaction pause window.
		gallery.addEventListener("mouseenter", stopAuto);
		gallery.addEventListener("mouseleave", () => startAuto(0));

		thumbs.forEach((thumb, i) => {
			thumb.addEventListener("click", () => {
				goTo(i);
				startAuto(INTERACT_RESUME_MS);
			});
		});

		gallery.addEventListener("keydown", (e) => {
			if (!gallery.contains(document.activeElement)) return;
			if (e.key === "ArrowRight") {
				goTo((current + 1) % thumbs.length);
				startAuto(INTERACT_RESUME_MS);
			}
			if (e.key === "ArrowLeft") {
				goTo((current - 1 + thumbs.length) % thumbs.length);
				startAuto(INTERACT_RESUME_MS);
			}
		});
	});
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initOfficeGallery);
} else {
	initOfficeGallery();
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

// Blog reader — master-detail interaction
function initBlogReader() {
	const posts = window.__blogPosts || [];
	if (!posts.length) return;

	const elImg       = document.getElementById("blog-detail-img");
	const elMeta      = document.getElementById("blog-detail-meta");
	const elTitle     = document.getElementById("blog-detail-title");
	const elTitleLink = document.getElementById("blog-detail-title-link");
	const elBody      = document.getElementById("blog-detail-body");
	const elLink      = document.getElementById("blog-detail-link");

	function showPost(id) {
		const post = posts.find((p) => p.id === id);
		if (!post) return;

		if (elImg) {
			elImg.src = post.thumbnail || "";
			elImg.alt = post.title;
			elImg.style.display = post.thumbnail ? "" : "none";
		}

		if (elTitle) elTitle.textContent = post.title;
		if (elTitleLink) elTitleLink.href = post.permalink;
		if (elBody) elBody.textContent = post.bodyPreview || post.excerpt || "";
		if (elLink) elLink.href = post.permalink;

		if (elMeta) {
			const sep = '<span class="blog-detail__sep">·</span>';
			const cats = post.categories
				.map((c) => `<a href="${c.url}" class="blog-detail__cat">${c.name}</a>`)
				.join(sep);
			elMeta.innerHTML =
				(cats ? cats + sep : "") +
				`<time datetime="${post.date}">${post.dateFormatted}</time>` +
				`${sep}${post.readTime} min read`;
		}

		document.querySelectorAll(".blog-list-item").forEach((el) => {
			const active = Number(el.dataset.id) === id;
			el.classList.toggle("is-active", active);
			el.setAttribute("aria-pressed", active ? "true" : "false");
		});
	}

	document.querySelectorAll(".blog-list-item").forEach((el) => {
		el.addEventListener("click", () => showPost(Number(el.dataset.id)));
		el.addEventListener("keydown", (e) => {
			if (e.key === "Enter" || e.key === " ") {
				e.preventDefault();
				showPost(Number(el.dataset.id));
			}
		});
	});
}

if (document.querySelector(".blog-reader")) initBlogReader();
