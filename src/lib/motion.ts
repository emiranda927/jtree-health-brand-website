import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger);

let started = false;

export function initMotion() {
  if (started) return;
  started = true;

  const root = document.documentElement;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Smooth scroll (Lenis) ---- */
  if (!reduce) {
    const lenis = new Lenis({
      duration: 1.05,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      smoothWheel: true,
      touchMultiplier: 1.6,
    });
    (window as unknown as { __lenis?: Lenis }).__lenis = lenis;

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    // Smooth anchor jumps
    document.querySelectorAll<HTMLAnchorElement>('a[href^="#"]').forEach((a) => {
      a.addEventListener('click', (e) => {
        const id = a.getAttribute('href');
        if (!id || id.length < 2) return;
        const target = document.querySelector(id);
        if (!target) return;
        e.preventDefault();
        lenis.scrollTo(target as HTMLElement, { offset: -84, duration: 1.1 });
      });
    });
  }

  /* ---- Scroll reveals ---- */
  const reveals = gsap.utils.toArray<HTMLElement>('[data-reveal]');
  reveals.forEach((el) => {
    if (reduce) { gsap.set(el, { opacity: 1 }); return; }
    const delay = parseFloat(el.dataset.revealDelay || '0');
    const mode = el.dataset.reveal || 'up';
    const fromVars: gsap.TweenVars = { opacity: 0 };
    const toVars: gsap.TweenVars = {
      opacity: 1, duration: 0.95, ease: 'power3.out', delay,
      scrollTrigger: { trigger: el, start: 'top 86%', once: true },
    };
    if (mode === 'up') { fromVars.y = 30; toVars.y = 0; }
    if (mode === 'scale') { fromVars.scale = 0.94; fromVars.transformOrigin = '50% 60%'; toVars.scale = 1; }
    gsap.fromTo(el, fromVars, toVars);
  });

  /* ---- Staggered groups: [data-reveal-group] animates its [data-reveal-item] children ---- */
  gsap.utils.toArray<HTMLElement>('[data-reveal-group]').forEach((group) => {
    const items = group.querySelectorAll<HTMLElement>('[data-reveal-item]');
    if (!items.length) return;
    if (reduce) { gsap.set(items, { opacity: 1 }); return; }
    gsap.from(items, {
      opacity: 0, y: 26, duration: 0.8, ease: 'power3.out', stagger: 0.09,
      scrollTrigger: { trigger: group, start: 'top 82%', once: true },
    });
  });

  /* ---- Hero intro timeline (runs on load) ---- */
  const hero = document.querySelector<HTMLElement>('[data-hero-scope]');
  if (hero) {
    const bits = hero.querySelectorAll<HTMLElement>('[data-hero]');
    if (reduce) {
      gsap.set(bits, { opacity: 1 });
    } else {
      const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
      tl.fromTo(bits,
        { opacity: 0, y: (i: number, el: HTMLElement) => (el.dataset.hero === 'media' ? 40 : 24) },
        { opacity: 1, y: 0, duration: 1.0, stagger: 0.12, delay: 0.15 }
      );
      // Cinematic slow-zoom on the hero photograph
      const heroZoom = hero.querySelector<HTMLElement>('[data-hero-zoom]');
      if (heroZoom) {
        gsap.fromTo(heroZoom, { scale: 1.14 }, { scale: 1, duration: 2.6, ease: 'power2.out' });
      }
    }
  }

  /* ---- Layered parallax (hero scene + any [data-parallax]) ---- */
  if (!reduce) {
    gsap.utils.toArray<HTMLElement>('[data-parallax]').forEach((el) => {
      const speed = parseFloat(el.dataset.parallax || '0.3');
      const container = el.closest<HTMLElement>('[data-hero-scope]') || el.parentElement || el;
      gsap.to(el, {
        yPercent: -speed * 22,
        ease: 'none',
        scrollTrigger: { trigger: container, start: 'top top', end: 'bottom top', scrub: true },
      });
    });
  }

  /* ---- Subtle float loop for tagged decorative elements ---- */
  if (!reduce) {
    gsap.utils.toArray<HTMLElement>('[data-float]').forEach((el, i) => {
      gsap.to(el, {
        y: '+=10',
        rotation: (i % 2 === 0 ? 2 : -2),
        duration: 3 + (i % 3) * 0.6,
        ease: 'sine.inOut',
        yoyo: true,
        repeat: -1,
        delay: i * 0.2,
      });
    });
  }

  /* ---- Nav scrolled state ---- */
  const nav = document.querySelector('[data-nav]');
  if (nav) {
    const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 16);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  ScrollTrigger.refresh();
}
