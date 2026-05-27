/**
 * JTree · Scroll-reveal.
 *
 * Adds `.is-visible` to any element carrying `.jth-reveal` once it enters
 * the viewport. Single IntersectionObserver instance shared across the
 * page; observation is dropped after first reveal so we don't waste work
 * on long pages.
 *
 * Reduced-motion is handled in CSS — this file still adds the class so
 * the final state matches; the transition is just neutralized.
 *
 * No dependencies. Inlined-safe.
 */
(function () {
  'use strict';

  function reveal() {
    var targets = document.querySelectorAll('.jth-reveal');
    if (!targets.length) return;

    // Graceful fallback for very old browsers — show everything.
    if (typeof IntersectionObserver === 'undefined') {
      for (var i = 0; i < targets.length; i++) {
        targets[i].classList.add('is-visible');
      }
      return;
    }

    var io = new IntersectionObserver(function (entries, observer) {
      for (var i = 0; i < entries.length; i++) {
        var entry = entries[i];
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      }
    }, {
      // Fire slightly before fully visible so the motion feels anticipatory,
      // not delayed. ~12% of viewport height = a comfortable read-ahead.
      rootMargin: '0px 0px -12% 0px',
      threshold: 0.01
    });

    for (var j = 0; j < targets.length; j++) {
      io.observe(targets[j]);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', reveal);
  } else {
    reveal();
  }
})();
