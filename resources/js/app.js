/* ============================================================
   Planetario — front-end interactions
   ============================================================ */

// Nav scroll state
const nav = document.querySelector('.nav');
if (nav) {
  const tick = () => nav.classList.toggle('scrolled', window.scrollY > 30);
  tick();
  window.addEventListener('scroll', tick, { passive: true });
}

// Mobile menu toggle
const menuToggle = document.querySelector('.menu-toggle');
const navLinks   = document.querySelector('.nav-links');
if (menuToggle && navLinks) {
  const iconMenu  = menuToggle.querySelector('.icon-menu');
  const iconClose = menuToggle.querySelector('.icon-close');

  const setOpen = (open) => {
    navLinks.classList.toggle('open', open);
    if (iconMenu)  iconMenu.style.display  = open ? 'none' : '';
    if (iconClose) iconClose.style.display = open ? ''     : 'none';
    menuToggle.setAttribute('aria-expanded', String(open));
  };

  menuToggle.addEventListener('click', () => setOpen(!navLinks.classList.contains('open')));
  navLinks.querySelectorAll('.nav-link').forEach(link =>
    link.addEventListener('click', () => setOpen(false))
  );
}

// Scroll reveal (IntersectionObserver)
const revealTargets = document.querySelectorAll('.reveal, .stagger-children');
if (revealTargets.length) {
  const io = new IntersectionObserver(
    entries => entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    }),
    { rootMargin: '-8% 0px -8% 0px', threshold: 0.05 }
  );
  revealTargets.forEach(el => io.observe(el));
}

// CountUp — triggered when element enters viewport
document.querySelectorAll('[data-countup]').forEach(el => {
  const target   = parseFloat(el.dataset.countup);
  const decimals = parseInt(el.dataset.decimals ?? '0', 10);
  const suffix   = el.dataset.suffix ?? '';
  const prefix   = el.dataset.prefix ?? '';
  const duration = 1600;

  const io = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      io.disconnect();
      const start = performance.now();
      const tick = (t) => {
        const p = Math.min(1, (t - start) / duration);
        const eased = 1 - Math.pow(1 - p, 3);
        const n = target * eased;
        el.textContent = prefix + (decimals > 0 ? n.toFixed(decimals) : Math.round(n).toLocaleString()) + suffix;
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    });
  }, { threshold: 0.4 });

  io.observe(el);
});
