/* ═══════════════════════════════════════════════════════════
   JTREE HEALTH — SCROLL ANIMATION + ILLUSTRATION ENGINE
   IntersectionObserver-based scroll reveals with expo-out easing
   ═══════════════════════════════════════════════════════════ */

(function() {
  'use strict';

  /* ── Scroll-reveal observer ─────────────────────────── */
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

  // Observe all elements with data-reveal attribute
  function initReveals() {
    document.querySelectorAll('[data-reveal]').forEach(el => {
      revealObserver.observe(el);
    });
  }

  /* ── Stagger children observer ──────────────────────── */
  const staggerObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const children = entry.target.querySelectorAll('[data-stagger-child]');
        children.forEach((child, i) => {
          child.style.transitionDelay = (i * 0.1) + 's';
          child.classList.add('revealed');
        });
        staggerObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  function initStagger() {
    document.querySelectorAll('[data-stagger]').forEach(el => {
      staggerObserver.observe(el);
    });
  }


  /* ── Counter animation ──────────────────────────────── */
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  function animateCounter(el) {
    const target = parseInt(el.dataset.count);
    const suffix = el.dataset.countSuffix || '';
    const duration = 1600;
    const start = performance.now();

    function tick(now) {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      // expo-out easing
      const eased = 1 - Math.pow(1 - progress, 4);
      const current = Math.round(target * eased);
      el.textContent = current + suffix;
      if (progress < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  function initCounters() {
    document.querySelectorAll('[data-count]').forEach(el => {
      counterObserver.observe(el);
    });
  }

  /* ── SVG draw-on animation ──────────────────────────── */
  const drawObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const paths = entry.target.querySelectorAll('[data-draw]');
        paths.forEach((path, i) => {
          const length = path.getTotalLength();
          path.style.strokeDasharray = length;
          path.style.strokeDashoffset = length;
          path.style.transition = `stroke-dashoffset 1.2s cubic-bezier(.16,1,.3,1) ${i * 0.15}s`;
          // Force reflow
          path.getBoundingClientRect();
          path.style.strokeDashoffset = '0';
        });
        drawObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  function initDrawSVGs() {
    document.querySelectorAll('[data-draw-svg]').forEach(el => {
      drawObserver.observe(el);
    });
  }

  /* ── Boot ────────────────────────────────────────────── */
  function init() {
    initReveals();
    initStagger();
    initCounters();
    initDrawSVGs();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
