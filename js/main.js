/* ============================================================
   Viata Luxe — main.js
   Lenis + GSAP (graceful fallbacks when CDN/JS unavailable)
   Multi-page build: every feature is guarded by element presence
   ============================================================ */
(function () {
  'use strict';

  document.documentElement.classList.add('js');

  var REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var HAS_GSAP = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
  var HAS_LENIS = typeof window.Lenis !== 'undefined';
  var IMG = '../images/';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  /* ---------------- Smooth scroll ---------------- */
  var lenis = null;
  if (HAS_LENIS && !REDUCED) {
    lenis = new Lenis({ lerp: 0.09, smoothWheel: true });
    if (HAS_GSAP) {
      lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add(function (t) { lenis.raf(t * 1000); });
      gsap.ticker.lagSmoothing(0);
    } else {
      var raf = function (t) { lenis.raf(t); requestAnimationFrame(raf); };
      requestAnimationFrame(raf);
    }
  }

  /* ---------------- Nav links: smooth target ---------------- */
  $$('.nav__menu a[href^="#"], .footer__nav a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href');
      var target = $(id);
      if (!target) return;
      e.preventDefault();
      closeMenu();
      if (lenis) lenis.scrollTo(target, { offset: -70 });
      else target.scrollIntoView({ behavior: REDUCED ? 'auto' : 'smooth' });
    });
  });

  /* ---------------- Deep link from another page ---------------- */
  function jumpToHash() {
    if (!location.hash) return;
    var target = $(decodeURIComponent(location.hash));
    if (!target) return;
    if (lenis) {
      setTimeout(function () { lenis.scrollTo(target, { offset: -70, immediate: true }); }, 60);
    } else if (!REDUCED) {
      setTimeout(function () { target.scrollIntoView({ behavior: 'smooth' }); }, 60);
    }
  }

  /* ---------------- Progress bar + nav state ---------------- */
  var bar = $('#progressBar');
  var nav = $('#nav');
  var onScroll = function () {
    if (bar) {
      var st = window.scrollY || document.documentElement.scrollTop;
      var h = document.documentElement.scrollHeight - window.innerHeight;
      bar.style.width = (h > 0 ? (st / h) * 100 : 0) + '%';
    }
    if (nav) nav.classList.toggle('is-scrolled', window.scrollY > 40);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  if (lenis) lenis.on('scroll', onScroll);
  onScroll();

  /* ---------------- Back to top ---------------- */
  var toTop = $('#toTop');
  if (toTop) {
    var onToTop = function () {
      toTop.classList.toggle('is-visible', (window.scrollY || document.documentElement.scrollTop) > 640);
    };
    window.addEventListener('scroll', onToTop, { passive: true });
    if (lenis) lenis.on('scroll', onToTop);
    onToTop();
    toTop.addEventListener('click', function () {
      if (lenis) lenis.scrollTo(0, { offset: 0 });
      else window.scrollTo({ top: 0, behavior: REDUCED ? 'auto' : 'smooth' });
    });
  }

  /* ---------------- Mobile menu ---------------- */
  var toggle = $('#navToggle');
  var menu = $('#navMenu');
  var closeMenu = function () {
    if (!toggle || !menu) return;
    toggle.setAttribute('aria-expanded', 'false');
    menu.classList.remove('is-open');
    document.body.classList.remove('nav-open');
  };
  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      menu.classList.toggle('is-open', !open);
      document.body.classList.toggle('nav-open', !open);
    });
    document.addEventListener('click', function (e) {
      if (menu.classList.contains('is-open') && !menu.contains(e.target) && !toggle.contains(e.target)) closeMenu();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && menu.classList.contains('is-open')) closeMenu();
    });
  }

  /* ---------------- Active nav link (in-page spy) ---------------- */
  var sections = ['arrival', 'place', 'accommodation', 'amenities', 'safari', 'moments', 'gallery', 'book']
    .map(function (id) { return document.getElementById(id); })
    .filter(Boolean);
  var links = $$('.nav__menu a[href^="#"]');
  var onSpy = function () {
    var pos = window.scrollY + 120;
    var current = sections.length ? sections[0].id : '';
    sections.forEach(function (sec) {
      if (sec.offsetTop <= pos) current = sec.id;
    });
    links.forEach(function (l) {
      l.classList.toggle('is-active', l.getAttribute('href') === '#' + current);
    });
  };
  window.addEventListener('scroll', onSpy, { passive: true });
  onSpy();

  /* ---------------- Scroll reveals ---------------- */
  function revealCss(els, delayStep) {
    els.forEach(function (el, i) {
      if (delayStep) el.style.transitionDelay = (i * delayStep) + 'ms';
      el.classList.add('is-visible');
    });
  }
  function observeReveals() {
    var els = $$('.reveal, .reveal-left, .reveal-right, .chapter__head');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (el) { io.observe(el); });
  }

  if (HAS_GSAP && !REDUCED) {
    gsap.registerPlugin(ScrollTrigger);
    $$('.reveal, .reveal-left, .reveal-right').forEach(function (el) {
      var dir = el.classList.contains('reveal-left') ? -1 : el.classList.contains('reveal-right') ? 1 : 0;
      gsap.fromTo(el,
        { opacity: 0, x: dir * 46, y: dir === 0 ? 34 : 0 },
        {
          opacity: 1, x: 0, y: 0, duration: 0.9, ease: 'power3.out',
          scrollTrigger: { trigger: el, start: 'top 86%', once: true }
        });
    });
  } else {
    observeReveals();
  }

  /* ---------------- Char-level chapter titles ---------------- */
  var heads = $$('.chapter__head');
  function splitChars() {
    $$('.chars').forEach(function (h) {
      var txt = h.textContent;
      h.textContent = '';
      var frag = document.createDocumentFragment();
      txt.split('').forEach(function (ch, i) {
        var s = document.createElement('span');
        s.className = 'char';
        s.textContent = ch === ' ' ? '\u00A0' : ch;
        if (!REDUCED) s.style.transitionDelay = (i * 22) + 'ms';
        frag.appendChild(s);
      });
      h.appendChild(frag);
    });
    if (!REDUCED) heads.forEach(function (head) { head.classList.remove('is-visible'); });
  }
  function observeHeads() {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
      });
    }, { threshold: 0.3 });
    heads.forEach(function (h) { io.observe(h); });
  }
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(splitChars).catch(splitChars);
  } else { splitChars(); }
  observeHeads();

  /* ---------------- Hero parallax (index only) ---------------- */
  if (HAS_GSAP && !REDUCED && $('#arrival') && $$('.hero__layer[data-depth]').length) {
    gsap.utils.toArray('.hero__layer[data-depth]').forEach(function (layer) {
      var d = parseFloat(layer.getAttribute('data-depth'));
      gsap.fromTo(layer,
        { yPercent: 0 },
        {
          yPercent: d * 34,
          ease: 'none',
          scrollTrigger: { trigger: '#arrival', start: 'top top', end: 'bottom top', scrub: 0.5 }
        });
    });
    gsap.to('#heroContent', {
      y: -60, opacity: 0.25, ease: 'none',
      scrollTrigger: { trigger: '#arrival', start: 'top top', end: '45% top', scrub: 0.4 }
    });
  }

  /* ---------------- Hero slideshow (index only) ---------------- */
  var slidesBox = $('#heroSlides');
  if (slidesBox) {
    var slides = $$('.hero__slide', slidesBox);
    var dotsBox = $('#heroDots');
    var capEl = $('#heroCaption');
    var fill = $('#heroBarFill');
    var DELAY = 6000;
    var idx = 0;
    var t0 = null;
    var rafId = 0;
    var timer = 0;
    var playing = false;

    function go(dir, update) {
      idx = (idx + dir + slides.length) % slides.length;
      slides.forEach(function (s, i) {
        s.classList.toggle('is-active', i === idx);
      });
      if (dotsBox) {
        $$('.hero__dot', dotsBox).forEach(function (d, i) {
          d.classList.toggle('is-active', i === idx);
          d.setAttribute('aria-current', i === idx ? 'true' : 'false');
        });
      }
      if (capEl) {
        var next = slides[idx].getAttribute('data-caption') || '';
        capEl.classList.add('is-fading');
        setTimeout(function () {
          capEl.textContent = next;
          capEl.classList.remove('is-fading');
        }, 240);
      }
      if (fill) fill.style.width = '0%';
      t0 = null;
      if (update !== false && playing) restartTimer();
    }

    function tick(ts) {
      if (!playing) return;
      if (t0 === null) t0 = ts;
      var p = Math.min((ts - t0) / DELAY, 1);
      if (fill) fill.style.width = (p * 100) + '%';
      if (p >= 1) { go(1); return; }
      rafId = requestAnimationFrame(tick);
    }
    function restartTimer() {
      clearTimeout(timer);
      timer = setTimeout(function () { rafId = requestAnimationFrame(tick); }, 60);
    }
    function play() {
      if (playing || REDUCED) return;
      playing = true;
      restartTimer();
    }
    function pause() {
      playing = false;
      clearTimeout(timer);
      cancelAnimationFrame(rafId);
    }

    if (dotsBox) {
      slides.forEach(function (s, i) {
        var dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'hero__dot';
        dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        dot.addEventListener('click', function () { go(i - idx); play(); });
        dotsBox.appendChild(dot);
      });
    }

    go(0, false);

    slidesBox.addEventListener('mouseenter', pause);
    slidesBox.addEventListener('mouseleave', play);
    slidesBox.addEventListener('focusin', pause);
    slidesBox.addEventListener('focusout', play);

    var sx = null;
    slidesBox.addEventListener('touchstart', function (e) { sx = e.changedTouches[0].clientX; }, { passive: true });
    slidesBox.addEventListener('touchend', function (e) {
      if (sx === null) return;
      var dx = e.changedTouches[0].clientX - sx;
      sx = null;
      if (Math.abs(dx) < 48) return;
      go(dx < 0 ? 1 : -1);
      play();
    }, { passive: true });

    document.addEventListener('keydown', function (e) {
      if (lb && !lb.hidden) return;
      var t = e.target;
      var tag = t && (t.tagName || '').toLowerCase();
      if (tag === 'input' || tag === 'textarea' || tag === 'select') return;
      if (e.key === 'ArrowRight') { go(1); play(); }
      if (e.key === 'ArrowLeft') { go(-1); play(); }
    });

    if (!REDUCED) play();
  }

  /* ---------------- Page-hero background slideshow (safari / gallery) ---------------- */
  var pageHeroSlides = $$('.page-hero__slides');
  pageHeroSlides.forEach(function (box) {
    var slides = $$('.page-hero__slide', box);
    if (slides.length < 2) return;
    var DELAY = 5000;
    var idx = 0;
    var t0 = null;
    var rafId = 0;
    var timer = 0;
    var playing = false;

    function go(dir) {
      idx = (idx + dir + slides.length) % slides.length;
      slides.forEach(function (s, i) {
        s.classList.toggle('is-active', i === idx);
      });
      t0 = null;
      if (playing) restartTimer();
    }
    function tick(ts) {
      if (!playing) return;
      if (t0 === null) t0 = ts;
      var p = (ts - t0) / DELAY;
      if (p >= 1) { go(1); return; }
      rafId = requestAnimationFrame(tick);
    }
    function restartTimer() {
      clearTimeout(timer);
      timer = setTimeout(function () { rafId = requestAnimationFrame(tick); }, 60);
    }
    function play() {
      if (playing || REDUCED) return;
      playing = true;
      restartTimer();
    }
    function pause() {
      playing = false;
      clearTimeout(timer);
      cancelAnimationFrame(rafId);
    }

    go(0);
    box.addEventListener('mouseenter', pause);
    box.addEventListener('mouseleave', play);

    if (!REDUCED) play();
  });

  /* ---------------- Distance counter (0 → 2 km) ---------------- */
  var counterEl = $('.counter');
  if (counterEl) {
    var ioC = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        ioC.disconnect();
        var target = parseInt(counterEl.getAttribute('data-count'), 10) || 2;
        var suffix = counterEl.getAttribute('data-suffix') || '';
        var t0 = null;
        var step = function (ts) {
          if (!t0) t0 = ts;
          var p = Math.min((ts - t0) / 1400, 1);
          var eased = 1 - Math.pow(1 - p, 3);
          counterEl.textContent = Math.round(eased * target) + suffix;
          if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
      });
    }, { threshold: 0.6 });
    ioC.observe(counterEl);
  }

  /* ---------------- Amenity chips cascade ---------------- */
  var chips = $('#amenityChips');
  if (chips) {
    var chipsItems = $$('li', chips);
    var ioCh = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        ioCh.disconnect();
        chipsItems.forEach(function (li, i) {
          setTimeout(function () { li.classList.add('is-in'); }, 40 + i * 45);
        });
      });
    }, { threshold: 0.25 });
    ioCh.observe(chips);
  }

  /* ---------------- Gallery (index + gallery page) ---------------- */
  var galleryData = {
    bedrooms: {
      caption: 'Crisp linen, quiet light, and a bed that wins the day.',
      alt: 'Luxe bedroom',
      folder: '2025/05',
      images: ['10-1', '8-1', '16', '6-1', '57', '3-1', '5-1', '50-1', '4-1']
    },
    kitchens: {
      caption: 'Self-catering, made effortless.',
      alt: 'Kitchen',
      folder: '2025/05',
      images: ['24-1', '22-1', '20-1', '35-1', '35', '34', '33', '32-1']
    },
    bathrooms: {
      caption: 'A spa moment, in private.',
      alt: 'Bathroom',
      folder: '2025/05',
      images: ['54-1', '83-1', '85-1', '55-1', '82-1', '90', 'DSCF3081', 'DSCF3159', 'DSCF3119']
    },
    living: {
      caption: 'Evenings made for sinking in.',
      alt: 'Living space',
      folder: '2025/05',
      images: ['42-1', '43-1', '26-1', '37-1', '40-1', '47-1', '45-1', '28', '39-1']
    },
    outdoors: {
      caption: 'Braai fires, cool drinks, Lowveld skies.',
      alt: 'Outdoor space',
      folder: '2025/05',
      images: ['56-1', '59-1', '64-1', '78-1', '65-1', '80-1', '81-1', 'DSCF3125', '83']
    }
  };

  var stage = $('#galleryStage');
  var tabs = $$('.gallery__tab');
  var currentCat = 'bedrooms';
  var currentList = [];

  function renderGallery(cat, animate) {
    var d = galleryData[cat];
    var grid = document.createElement('div');
    grid.className = 'gallery__grid';
    grid.setAttribute('role', 'tabpanel');
    d.images.forEach(function (name, i) {
      var folder = (cat === 'outdoors' && name === 'DSCF3125') ? '2025/04' : d.folder;
      var fig = document.createElement('figure');
      fig.className = 'gallery__card';
      fig.style.transitionDelay = animate ? (i * 70) + 'ms' : '0ms';
      fig.tabIndex = 0;
      fig.setAttribute('role', 'button');
      fig.setAttribute('aria-label', 'Open photo: ' + d.alt + ', photo ' + (i + 1) + ' of ' + d.images.length);
      var img = document.createElement('img');
      img.src = IMG + folder + '/' + name + '.jpg';
      img.alt = d.alt + ' at Viata Luxe — photo ' + (i + 1) + ' of ' + d.images.length;
      img.loading = 'lazy';
      var cap = document.createElement('figcaption');
      cap.textContent = d.caption;
      fig.appendChild(img);
      fig.appendChild(cap);
      fig.addEventListener('click', function () { openLightbox(currentList, i); });
      fig.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          openLightbox(currentList, i);
        }
      });
      grid.appendChild(fig);
    });
    stage.innerHTML = '';
    stage.appendChild(grid);
    currentList = d.images.map(function (name) {
      var folder = (cat === 'outdoors' && name === 'DSCF3125') ? '2025/04' : d.folder;
      return { src: IMG + folder + '/' + name + '.jpg', alt: d.alt + ' at Viata Luxe', caption: d.caption };
    });
    requestAnimationFrame(function () {
      $$('.gallery__card', grid).forEach(function (el) { el.classList.add('is-in'); });
    });
  }

  if (stage) {
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        if (tab.getAttribute('aria-selected') === 'true') return;
        tabs.forEach(function (t) {
          var on = t === tab;
          t.classList.toggle('is-active', on);
          t.setAttribute('aria-selected', String(on));
        });
        currentCat = tab.getAttribute('data-cat');
        renderGallery(currentCat, true);
      });
    });
    tabs.forEach(function (tab) {
      var cat = tab.getAttribute('data-cat');
      var n = galleryData[cat] ? galleryData[cat].images.length : 0;
      if (n) tab.appendChild(document.createTextNode(' · ' + n));
    });
    renderGallery(currentCat, false);
  }

  /* ---------------- Room photo sets ---------------- */
  var roomPhotos = {
    classic1: {
      folder: '2025/11',
      alt: 'Classic Apartment 1',
      images: ['Classic-Bedroom-1', 'Classic-Bedroom-1-bedside', 'Classic-Bedroom-1-towels', 'Classic-bedroom-1-window', 'Classic-Bedroom-1-living-room-side', 'Classic-Bedroom-1-Living-room', 'Classic-Apartment-1-kitchen', 'Classic-Apartment-1-stove', 'Classic-bedroom-1-toilet', 'Classic-Bathroom-shower-top', 'Classic-Bedrrom-1-shower']
    },
    classic2: {
      folder: '2025/11',
      alt: 'Classic Apartment 2',
      images: ['Classic-Apartment-2-Bedroom', 'Classic-Apartment-2-Bedroom-side', 'Classic-Apartment-2-bedding', 'Classic-Apartment-2-Living-Room-side', 'Classic-Apartment-2-TV', 'Classic-Apartment-2-Living-room', 'Classic-Apartment-2-Kitchen', 'Classic-Apartment-2-Basin-Kitchen', 'Classic-Apartment-2-Kitchenware', 'Classic-apartment-2-Bathroom', 'Classic-Apartment-2-Toilet', 'Classic-Apartment-2-basin']
    },
    comfort3: {
      folder: '2025/11',
      alt: 'Comfort Apartment 3',
      images: ['Classic-Apartment-3-Bedroom', 'Classic-Apartment-3-Bedroom-side', 'Classic-Bedroom-3-Bedroom-sofa', 'Classic-Apartment-3-TV', 'Classic-Apartment-3-living-room-coffee-table', 'Classic-Apartment-3-Dinning', 'Classic-Bedroom-3-Stove', 'Classic-Apartment-3-Kitchen-basin', 'Classic-Apartment-3-Toilet-basin', 'Classic-Apartment-3-Toilet', 'Classic-Apartment-3-Rinse', 'Classic-Bathroom-shower-top']
    },
    deluxe4: {
      folder: '2025/11',
      alt: 'Deluxe Apartment 4',
      images: ['Classic-Bedroom-4', 'Classic-Bedroom-4-again', 'Classic-Bedroom-4-Locker', 'Classic-Apartment-4-Kitchen', 'Classic-Apartment-4-Dinning', 'Classic-Apartment-4-Kitchen-basic', 'Classic-Apartment-4-living-room', 'Classic-Apartment-4-living-room-side', 'Classic-Apartment-4-TV', 'Classic-Apartment-4-shower', 'Classic-Apartment-4-basin', 'Classic-bedroom-4-Bathroom']
    },
    superior: {
      folder: '2025/05',
      alt: 'Superior Apartment',
      images: ['24-1', '10-1', '16', '83-1', '42-1', '40-1']
    }
  };

  $$('.room__photos').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var d = roomPhotos[btn.getAttribute('data-room')];
      if (!d) return;
      var list = d.images.map(function (n) {
        return { src: IMG + d.folder + '/' + n + '.jpg', alt: d.alt + ' — ' + n.replace(/-/g, ' '), caption: d.alt };
      });
      openLightbox(list, 0);
    });
  });

  /* ---------------- Lightbox ---------------- */
  var lb = $('#lightbox');
  var lbImg = $('#lightboxImg');
  var lbCap = $('#lightboxCaption');
  var lbIdx = 0;
  var lbList = [];
  var lbFocus = null;
  var lbTrigger = null;

  function openLightbox(list, idx) {
    if (!lb) return;
    lbTrigger = document.activeElement;
    lbList = list;
    lbIdx = idx;
    lb.hidden = false;
    document.body.style.overflow = 'hidden';
    showLb();
    lbFocus = $('#lightboxClose');
    lbFocus.focus();
  }
  function closeLb() {
    if (!lb) return;
    lb.hidden = true;
    document.body.style.overflow = '';
    if (lbTrigger && document.contains(lbTrigger)) lbTrigger.focus();
    lbTrigger = null;
  }
  function showLb() {
    var item = lbList[lbIdx];
    if (!item) return;
    lbImg.src = item.src;
    lbImg.alt = item.alt;
    lbCap.textContent = item.caption + ' — ' + (lbIdx + 1) + ' / ' + lbList.length;
  }
  function lbStep(dir) {
    lbIdx = (lbIdx + dir + lbList.length) % lbList.length;
    showLb();
  }
  if (lb) {
    $('#lightboxClose').addEventListener('click', closeLb);
    $('#lightboxPrev').addEventListener('click', function () { lbStep(-1); });
    $('#lightboxNext').addEventListener('click', function () { lbStep(1); });
    lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
    document.addEventListener('keydown', function (e) {
      if (lb.hidden) return;
      if (e.key === 'Escape') closeLb();
      if (e.key === 'ArrowLeft') lbStep(-1);
      if (e.key === 'ArrowRight') lbStep(1);
      if (e.key === 'Tab') {
        var focusables = lb.querySelectorAll('button');
        if (!focusables.length) return;
        var first = focusables[0];
        var last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
    var lx = null;
    lb.addEventListener('touchstart', function (e) { lx = e.changedTouches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) {
      if (lx === null) return;
      var dx = e.changedTouches[0].clientX - lx;
      lx = null;
      if (Math.abs(dx) < 48) return;
      lbStep(dx < 0 ? 1 : -1);
    }, { passive: true });
  }

  /* ---------------- Click-to-load videos ---------------- */
  $$('.video__frame').forEach(function (frame) {
    var id = frame.getAttribute('data-youtube');
    var play = function () {
      var iframe = document.createElement('iframe');
      iframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
      iframe.title = 'YouTube video player';
      iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
      iframe.allowFullscreen = true;
      frame.innerHTML = '';
      frame.appendChild(iframe);
    };
    frame.addEventListener('click', play);
    frame.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); play(); }
    });
  });

  /* ---------------- Giraffe surprise beat ---------------- */
  var giraffe = $('#giraffe');
  if (giraffe && HAS_GSAP && !REDUCED) {
    gsap.fromTo('#giraffe',
      { opacity: 0, x: -140 },
      {
        opacity: 0.9, x: function () { return window.innerWidth + 160; }, duration: 3.2, ease: 'power1.inOut',
        scrollTrigger: { trigger: '#safari', start: 'top 55%', toggleActions: 'play none none none' }
      });
  }

  /* ---------------- Booking form (mailto) ---------------- */
  var form = $('#bookForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var name = $('#f-name').value.trim();
      var email = $('#f-email').value.trim();
      var phone = $('#f-phone').value.trim();
      var checkin = $('#f-checkin').value;
      var checkout = $('#f-checkout').value;
      var guests = $('#f-guests').value;
      var message = $('#f-message').value.trim();
      var note = $('#formNote');
      var clearErrors = function () {
        $$('#bookForm [aria-invalid]').forEach(function (el) { el.removeAttribute('aria-invalid'); });
      };
      var fail = function (msg, field) {
        note.textContent = msg;
        note.hidden = false;
        if (field) {
          field.setAttribute('aria-invalid', 'true');
          field.setAttribute('aria-describedby', 'formNote');
          field.focus();
        }
      };
      clearErrors();
      if (!name) return fail('Please add your name.', $('#f-name'));
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return fail('Please add a valid email address.', $('#f-email'));
      if (!checkin || !checkout) return fail('Please pick arrival and departure dates.', checkin ? $('#f-checkout') : $('#f-checkin'));
      if (checkout <= checkin) return fail('Departure must be after arrival.', $('#f-checkout'));
      var body = [
        'Name: ' + name,
        'Email: ' + email,
        'Phone: ' + (phone || '—'),
        'Arrival: ' + checkin,
        'Departure: ' + checkout,
        'Guests: ' + guests,
        '',
        'Notes:',
        message || '—'
      ].join('\n');
      var subject = 'Booking request — ' + name + ' (' + checkin + ' → ' + checkout + ')';
      window.location.href = 'mailto:info@viataluxe.com?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
      note.textContent = 'Opening your email app… You can also write to info@viataluxe.com directly.';
      note.hidden = false;
    });
  }

  /* ---------------- Footer year ---------------- */
  var year = $('#year');
  if (year) year.textContent = String(new Date().getFullYear());

  /* ---------------- Deep link jump (after load) ---------------- */
  window.addEventListener('load', jumpToHash);

})();