// JTree Health · sticky nav + mobile menu toggle
(function () {
  const header = document.querySelector('.site-header');
  const toggle = document.querySelector('.nav-toggle');
  if (!toggle || !header) return;
  toggle.addEventListener('click', () => {
    header.classList.toggle('is-open');
    const open = header.classList.contains('is-open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  // close on link click
  header.querySelectorAll('.nav a').forEach(a => a.addEventListener('click', () => {
    header.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  }));
})();
