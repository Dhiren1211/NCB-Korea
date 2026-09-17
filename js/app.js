/**
 * NCB Website — Main JavaScript
 * Handles: header scroll, mobile menu, scroll animations,
 * counter animation, gallery filter, form validation, lightbox
 */

(function () {
  'use strict';

  // === HEADER SCROLL ===
  var header = document.getElementById('header');
  function handleScroll() {
    if (window.scrollY > 50) header.classList.add('header--scrolled');
    else header.classList.remove('header--scrolled');
  }
  window.addEventListener('scroll', handleScroll, { passive: true });

  // === MOBILE MENU ===
  var hamburger = document.getElementById('hamburger');
  var mobileMenu = document.getElementById('mobileMenu');
  var menuOverlay = document.getElementById('menuOverlay');
  var menuClose = document.getElementById('menuClose');

  function openMenu() {
    mobileMenu.classList.add('mobile-menu--open');
    menuOverlay.classList.add('mobile-menu__overlay--visible');
    hamburger.classList.add('hamburger--active');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    mobileMenu.classList.remove('mobile-menu--open');
    menuOverlay.classList.remove('mobile-menu__overlay--visible');
    hamburger.classList.remove('hamburger--active');
    document.body.style.overflow = '';
  }
  if (hamburger) hamburger.addEventListener('click', function () {
    mobileMenu.classList.contains('mobile-menu--open') ? closeMenu() : openMenu();
  });
  if (menuClose) menuClose.addEventListener('click', closeMenu);
  if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);
  document.querySelectorAll('.mobile-menu__nav-link, .mobile-menu__more-item').forEach(function (l) {
    l.addEventListener('click', closeMenu);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMenu();
  });

  // === SCROLL TO TOP ===
  var scrollBtn = document.getElementById('scrollTopBtn');
  if (scrollBtn) scrollBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // === SCROLL ANIMATIONS ===
  if ('IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('fade-in--visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('.fade-in').forEach(function (el) { obs.observe(el); });
  } else {
    document.querySelectorAll('.fade-in').forEach(function (el) {
      el.classList.add('fade-in--visible');
    });
  }

  // === USER DROPDOWN (click outside to close) ===
  document.addEventListener('click', function (e) {
    var dropdown = document.getElementById('userDropdown');
    var menu = document.getElementById('userMenu');
    if (dropdown && menu && !menu.contains(e.target)) {
      dropdown.classList.remove('header__dropdown--open');
    }
  });

  // === STAGGERED GRID ANIMATIONS ===
  ['.about__cards', '.services__grid', '.impact__grid', '.events__grid', '.committee__grid', '.volunteer__grid'].forEach(function (sel) {
    var c = document.querySelector(sel);
    if (!c) return;
    for (var i = 0; i < c.children.length; i++) {
      c.children[i].style.transitionDelay = (i * 0.1) + 's';
    }
  });

  // === COUNTER ANIMATION ===
  var countersAnimated = false;
  function animateCounters() {
    if (countersAnimated) return;
    var impactSection = document.querySelector('.impact');
    if (!impactSection) return;
    var rect = impactSection.getBoundingClientRect();
    if (rect.top > window.innerHeight || rect.bottom < 0) return;
    countersAnimated = true;
    document.querySelectorAll('.impact-card__number[data-target]').forEach(function (counter) {
      var target = parseInt(counter.getAttribute('data-target'), 10);
      var suffix = counter.textContent.replace(/[0-9]/g, '');
      var duration = 2000;
      var startTime = null;
      function step(ts) {
        if (!startTime) startTime = ts;
        var p = Math.min((ts - startTime) / duration, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        counter.textContent = Math.floor(eased * target) + suffix;
        if (p < 1) requestAnimationFrame(step);
        else counter.textContent = target + suffix;
      }
      requestAnimationFrame(step);
    });
  }
  window.addEventListener('scroll', animateCounters, { passive: true });
  animateCounters();

  // === GALLERY FILTER ===
  document.querySelectorAll('.gallery-filter__btn, .filter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var parent = this.parentElement;
      parent.querySelectorAll('.gallery-filter__btn, .filter-btn').forEach(function (b) {
        b.classList.remove('gallery-filter__btn--active', 'filter-btn--active');
      });
      this.classList.add('gallery-filter__btn--active', 'filter-btn--active');
    });
  });

  // === TOAST AUTO-DISMISS ===
  document.querySelectorAll('.toast').forEach(function (t) {
    setTimeout(function () { t.remove(); }, 5000);
  });

  // === FORM VALIDATION HELPERS ===
  document.querySelectorAll('form[data-validate]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var valid = true;
      form.querySelectorAll('[required]').forEach(function (input) {
        if (!input.value.trim()) {
          input.style.borderColor = '#e53935';
          valid = false;
        } else {
          input.style.borderColor = '';
        }
        if (input.type === 'email' && input.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
          input.style.borderColor = '#e53935';
          valid = false;
        }
      });
      if (!valid) e.preventDefault();
    });
  });

  // === SMOOTH ANCHOR LINKS ===
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var href = this.getAttribute('href');
      if (href === '#') return;
      var target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        var h = header ? header.offsetHeight : 0;
        window.scrollTo({ top: target.offsetTop - h, behavior: 'smooth' });
      }
    });
  });

})();