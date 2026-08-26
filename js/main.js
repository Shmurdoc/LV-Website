/* js/main.js — Viata Luxe | Lenis 1.1.18 + GSAP 3.12.5 ScrollTrigger + enhanced animations */
(function(){
  "use strict";

  /* ——— Preloader 1.2s max quiet ——— */
  var pre = document.getElementById("preloader");
  function hidePre(){
    if(!pre) return;
    pre.classList.add("is-hidden");
    setTimeout(function(){ pre.style.display="none"; }, 700);
  }
  setTimeout(hidePre, 1100);
  window.addEventListener("load", function(){ setTimeout(hidePre, 200); });

  /* ——— Mobile drawer with animated links ——— */
  var toggle = document.getElementById("navToggle");
  var drawer = document.getElementById("mobileDrawer");
  if(toggle && drawer){
    toggle.addEventListener("click", function(){
      var isOpen = drawer.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", isOpen ? "true":"false");
      toggle.classList.toggle("is-active", isOpen);
    });
    drawer.querySelectorAll("a").forEach(function(a){
      a.addEventListener("click", function(){ drawer.classList.remove("is-open"); toggle.setAttribute("aria-expanded","false"); toggle.classList.remove("is-active"); });
    });
  }

  /* ——— Lenis smooth scroll ——— */
  var lenis = null;
  try{
    if(window.Lenis){
      lenis = new Lenis({
        duration: 1.2,
        easing: function(t){ return Math.min(1, 1.001 - Math.pow(2, -10 * t)); },
        smoothWheel: true,
        smoothTouch: false,
        gestureOrientation: "vertical"
      });
      function raf(time){ lenis.raf(time); requestAnimationFrame(raf); }
      requestAnimationFrame(raf);
      if(window.gsap && window.ScrollTrigger){
        lenis.on("scroll", ScrollTrigger.update);
        gsap.ticker.add(function(time){ lenis.raf(time * 1000); });
        gsap.ticker.lagSmoothing(0);
      }
    }
  }catch(e){ console.warn("Lenis init failed", e); }

  /* ——— GSAP ScrollTrigger reveals ——— */
  if(window.gsap && window.ScrollTrigger){
    gsap.registerPlugin(ScrollTrigger);

    // Enhanced reveal — smoother entrance with stagger support
    gsap.utils.toArray(".reveal").forEach(function(el){
      var delay = parseFloat(el.getAttribute("data-delay")) || 0;
      gsap.fromTo(el,
        { y: 18, opacity: 0 },
        {
          y: 0, opacity: 1, duration: 0.72, ease: "power3.out", delay: delay,
          scrollTrigger: { trigger: el, start: "top 90%", once: true, toggleActions: "play none none none" }
        }
      );
    });

    // Staggered reveals for grids — animate children in sequence
    function staggerReveal(container, selector, opts){
      var parent = document.querySelector(container);
      if(!parent) return;
      var items = parent.querySelectorAll(selector);
      if(items.length < 1) return;
      ScrollTrigger.create({
        trigger: parent,
        start: "top 85%",
        once: true,
        onEnter: function(){
          gsap.fromTo(items,
            { y: 24, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.6, ease: "power3.out", stagger: opts.stagger || 0.08, delay: opts.delay || 0 }
          );
        }
      });
    }

    staggerReveal(".pillars", ".pillar", { stagger: 0.09, delay: 0.1 });
    staggerReveal(".moments__grid", ".moment", { stagger: 0.1 });
    staggerReveal(".rooms", ".room", { stagger: 0.12 });
    staggerReveal(".promise__stats", ".promise__stat", { stagger: 0.08 });

    // Gallery masonry item entrance
    var masonryItems = document.querySelectorAll(".masonry__item");
    if(masonryItems.length){
      ScrollTrigger.create({
        trigger: ".masonry",
        start: "top 85%",
        once: true,
        onEnter: function(){
          gsap.fromTo(masonryItems,
            { y: 24, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.55, ease: "power3.out", stagger: 0.06 }
          );
        }
      });
    }

    // Gallery filter transition
    var filterBtns = document.querySelectorAll(".filter__inner button");
    filterBtns.forEach(function(btn){
      btn.addEventListener("click", function(){
        filterBtns.forEach(function(b){ b.classList.remove("is-active"); });
        btn.classList.add("is-active");
        var filterVal = btn.getAttribute("data-filter");
        var items = document.querySelectorAll(".masonry__item");
        gsap.fromTo(items,
          { opacity: 0, scale: 0.96 },
          { opacity: 1, scale: 1, duration: 0.4, ease: "power3.out", stagger: 0.04, delay: 0.1 }
        );
      });
    });

    // Hero content entrance animated via CSS — no GSAP needed

    // Hero parallax enhanced with depth layers
    var heroEl = document.querySelector(".hero");
    if(heroEl){
      var heroImg = heroEl.querySelector(".hero__media img");
      var heroContent = heroEl.querySelector(".hero__content");
      if(heroImg){
        gsap.to(heroImg, {
          yPercent: -8, ease: "none",
          scrollTrigger: { trigger: heroEl, start: "top top", end: "bottom top", scrub: 1 }
        });
      }
      if(heroContent){
        gsap.to(heroContent, {
          yPercent: 4, ease: "none",
          scrollTrigger: { trigger: heroEl, start: "top top", end: "bottom top", scrub: 0.8 }
        });
      }
    }

    // Room media parallax with depth
    document.querySelectorAll(".room__media img").forEach(function(img){
      var room = img.closest(".room");
      if(!room) return;
      gsap.to(img, {
        yPercent: -6, ease: "none",
        scrollTrigger: { trigger: room, start: "top bottom", end: "bottom top", scrub: 1 }
      });
    });

    // Featured media parallax
    document.querySelectorAll(".featured__media img").forEach(function(img){
      gsap.to(img, {
        yPercent: -5, ease: "none",
        scrollTrigger: { trigger: img.closest(".featured__grid"), start: "top bottom", end: "bottom top", scrub: 0.8 }
      });
    });

    // Dusk horizontal scroller parallax
    if(window.matchMedia("(prefers-reduced-motion: no-preference)").matches){
      document.querySelectorAll(".dusk-panel__media img").forEach(function(img){
        var speed = parseFloat(img.closest("[data-speed]")?.getAttribute("data-speed") || "0.06");
        gsap.to(img, {
          xPercent: -6 * (speed/0.08), ease:"none",
          scrollTrigger:{ trigger: img.closest(".dusk-panel"), start:"left right", end:"right left", scrub:0.8, horizontal:true }
        });
      });
    }
  }

  /* ——— IntersectionObserver reveals fallback (if GSAP absent) ——— */
  if(!(window.gsap && window.ScrollTrigger)){
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting){ e.target.classList.add("is-in"); io.unobserve(e.target); }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -8% 0px" });
    document.querySelectorAll(".reveal").forEach(function(el){ io.observe(el); });
  } else {
    var io2 = new IntersectionObserver(function(entries){
      entries.forEach(function(e){ if(e.isIntersecting) e.target.classList.add("is-in"); });
    }, { threshold: 0.1 });
    document.querySelectorAll(".reveal, .masonry__item").forEach(function(el){ io2.observe(el); });
  }

  /* ——— Hero pause control ——— */
  var hero = document.getElementById("hero");
  var pauseBtn = document.getElementById("heroPause");
  if(hero && pauseBtn){
    var isPaused = false;
    pauseBtn.addEventListener("click", function(){
      isPaused = !isPaused;
      hero.classList.toggle("is-paused", isPaused);
      pauseBtn.setAttribute("aria-pressed", isPaused ? "true":"false");
      pauseBtn.innerHTML = isPaused ? "&#9654;" : "&#10074;&#10074;";
      pauseBtn.title = isPaused ? "Play animation" : "Pause animation";
    });
    if(window.matchMedia("(prefers-reduced-motion: reduce)").matches){
      hero.classList.add("is-paused");
      pauseBtn.setAttribute("aria-pressed","true");
      pauseBtn.innerHTML="&#9654;";
    }
  }

  /* ——— Counter (GSAP-powered) ——— */
  function animateCounter(el){
    var target = parseFloat(el.getAttribute("data-target") || el.textContent);
    var suffix = el.getAttribute("data-suffix") || "";
    var prefix = el.getAttribute("data-prefix") || "";
    var isFloat = String(target).indexOf(".") !== -1;
    if(window.gsap){
      var obj = { val: 0 };
      gsap.to(obj, {
        val: target, duration: 1.4, ease: "power3.out",
        onUpdate: function(){ el.textContent = prefix + (isFloat ? obj.val.toFixed(1) : Math.round(obj.val)) + suffix; },
        onComplete: function(){ el.textContent = prefix + target + suffix; }
      });
    } else {
      var start = 0; var duration = 1100; var startTime = null;
      function step(ts){
        if(!startTime) startTime = ts;
        var p = Math.min(1, (ts - startTime)/duration);
        var eased = 1 - Math.pow(1 - p, 3);
        var val = start + (target - start) * eased;
        el.textContent = prefix + (isFloat ? val.toFixed(1) : Math.round(val)) + suffix;
        if(p < 1) requestAnimationFrame(step);
        else el.textContent = prefix + target + suffix;
      }
      requestAnimationFrame(step);
    }
  }
  var counterIO = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){ animateCounter(e.target); counterIO.unobserve(e.target); }
    });
  }, { threshold: 0.5 });
  document.querySelectorAll(".counter").forEach(function(c){ counterIO.observe(c); });

  /* ——— Lightbox with GSAP entrance ——— */
  var lb = document.getElementById("lightbox");
  var lbImg = lb ? lb.querySelector("img") : null;
  var lbClose = lb ? lb.querySelector(".lightbox__close") : null;
  var lbPrev = lb ? lb.querySelector(".lightbox__nav--prev") : null;
  var lbNext = lb ? lb.querySelector(".lightbox__nav--next") : null;
  var gallery = [];
  document.querySelectorAll("[data-lightbox]").forEach(function(a, i){
    gallery.push(a.getAttribute("href") || a.getAttribute("data-src") || a.querySelector("img")?.src);
    a.addEventListener("click", function(e){
      e.preventDefault();
      openLb(i);
    });
  });
  var current = 0;
  function openLb(i){
    if(!lb || !lbImg) return;
    current = i;
    lbImg.src = gallery[current] || "";
    lb.classList.add("is-open");
    document.body.style.overflow="hidden";
    var cnt = lb.querySelector(".lightbox__counter");
    if(cnt) cnt.textContent = (current+1)+" / "+gallery.length;
    if(window.gsap){
      gsap.fromTo(lbImg, { scale: 0.85, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.35, ease: "power3.out" });
    }
  }
  function closeLb(){
    if(!lb) return;
    lb.classList.remove("is-open");
    document.body.style.overflow="";
  }
  function navLb(dir){
    current = (current + dir + gallery.length) % gallery.length;
    if(lbImg){
      lbImg.src = gallery[current] || "";
      if(window.gsap){
        gsap.fromTo(lbImg, { opacity: 0.6 }, { opacity: 1, duration: 0.2 });
      }
    }
    var cnt = lb ? lb.querySelector(".lightbox__counter") : null;
    if(cnt) cnt.textContent = (current+1)+" / "+gallery.length;
  }
  if(lbClose) lbClose.addEventListener("click", closeLb);
  if(lb) lb.addEventListener("click", function(e){ if(e.target===lb) closeLb(); });
  if(lbPrev) lbPrev.addEventListener("click", function(e){ e.stopPropagation(); navLb(-1); });
  if(lbNext) lbNext.addEventListener("click", function(e){ e.stopPropagation(); navLb(1); });
  document.addEventListener("keydown", function(e){
    if(!lb || !lb.classList.contains("is-open")) return;
    if(e.key==="Escape") closeLb();
    if(e.key==="ArrowLeft") navLb(-1);
    if(e.key==="ArrowRight") navLb(1);
  });

  /* ——— Layout helpers ——— */
  function prepare(){
    document.documentElement.style.setProperty("--vh", (window.innerHeight * 0.01) + "px");
  }
  function layout(){
    document.querySelectorAll("img[loading='lazy']").forEach(function(img){
      if(!img.complete) img.style.contentVisibility="auto";
    });
  }
  prepare(); layout();
  window.addEventListener("resize", prepare);
  window.addEventListener("load", layout);

  /* ——— Smooth anchor ——— */
  document.querySelectorAll('a[href^="#"]').forEach(function(a){
    a.addEventListener("click", function(e){
      var id = a.getAttribute("href");
      if(id.length>1){
        var target = document.querySelector(id);
        if(target){
          e.preventDefault();
          if(lenis) lenis.scrollTo(target, { offset: -68 });
          else target.scrollIntoView({ behavior:"smooth", block:"start" });
        }
      }
    });
  });

})();

/* ——— YouTube facade + maps facade + form + masonry prepare ——— */
(function(){
  "use strict";

  try{
    var rm = window.matchMedia("(prefers-reduced-motion: reduce)");
    if(rm && rm.matches) document.documentElement.classList.add("rm");
  }catch(e){}

  /* ——— YouTube lite facade ——— */
  function loadYT(facade){
    var id = facade.getAttribute("data-yt");
    if(!id || facade.querySelector("iframe")) return;
    var iframe = document.createElement("iframe");
    iframe.src = "https://www.youtube-nocookie.com/embed/"+id+"?autoplay=1&rel=0&modestbranding=1&playsinline=1";
    iframe.title = "YouTube video";
    iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
    iframe.allowFullscreen = true;
    iframe.loading = "eager";
    iframe.style.cssText="position:absolute;inset:0;width:100%;height:100%;border:0";
    facade.appendChild(iframe);
    facade.querySelectorAll("img, .yt-facade__overlay, .yt-facade__play, .yt-facade__label").forEach(function(el){ el.style.opacity="0"; el.style.pointerEvents="none"; });
    facade.setAttribute("aria-label","YouTube video now playing");
  }
  document.querySelectorAll(".yt-facade").forEach(function(f){
    f.addEventListener("click", function(){ loadYT(f); });
    f.addEventListener("keydown", function(e){
      if(e.key==="Enter" || e.key===" "){ e.preventDefault(); loadYT(f); }
    });
    var btn = f.querySelector(".yt-facade__play");
    if(btn) btn.addEventListener("click", function(e){ e.stopPropagation(); loadYT(f); });
  });

  /* ——— Google Maps facade ——— */
  var mf = document.getElementById("mapsFacade");
  var mframe = document.getElementById("mapsFrame");
  function loadMaps(){
    if(!mf || !mframe) return;
    mframe.hidden = false;
    mf.style.display="none";
    if(window.ScrollTrigger) ScrollTrigger.refresh();
  }
  if(mf){
    mf.addEventListener("click", loadMaps);
    mf.addEventListener("keydown", function(e){ if(e.key==="Enter"||e.key===" ") { e.preventDefault(); loadMaps(); }});
    var mb = mf.querySelector(".maps-facade__btn");
    if(mb) mb.addEventListener("click", function(e){ e.stopPropagation(); loadMaps(); });
  }

  /* ——— Contact form ——— */
  var form = document.getElementById("connectForm");
  if(form){
    var hp = document.getElementById("website");
    var msg = document.getElementById("formMsg");
    function showMsg(text, ok){
      if(!msg) return;
      msg.hidden=false;
      msg.textContent=text;
      msg.className = "connect-form__msg " + (ok ? "connect-form__msg--ok" : "connect-form__msg--err");
    }
    var today = new Date().toISOString().slice(0,10);
    var arr = document.getElementById("fArrival");
    var dep = document.getElementById("fDeparture");
    if(arr) arr.min = today;
    if(dep) dep.min = today;
    if(arr && dep){
      arr.addEventListener("change", function(){ dep.min = arr.value || today; if(dep.value && dep.value <= arr.value){ dep.value=""; showMsg("Departure must be after arrival.", false);} });
    }
    form.addEventListener("submit", function(e){
      if(hp && hp.value.trim() !== ""){
        e.preventDefault();
        showMsg("Spam detected — not sent.", false);
        return;
      }
      var name = (document.getElementById("fName")||{}).value || "";
      var email = (document.getElementById("fEmail")||{}).value || "";
      var arrival = (document.getElementById("fArrival")||{}).value || "";
      var departure = (document.getElementById("fDeparture")||{}).value || "";
      if(!form.checkValidity()){
        showMsg("Please fill the required fields (name, email, arrival, departure).", false);
        return;
      }
      if(arrival && departure && departure <= arrival){
        e.preventDefault();
        showMsg("Departure must be after arrival.", false);
        return;
      }
      e.preventDefault();
      var phone = (document.getElementById("fPhone")||{}).value || "";
      var guests = (document.getElementById("fGuests")||{}).value || "";
      var notes = (document.getElementById("fNotes")||{}).value || "";
      var subject = encodeURIComponent("Viata Luxe enquiry — "+name+" · "+arrival+" → "+departure);
      var body = encodeURIComponent(
        "Name: "+name+"\nEmail: "+email+"\nPhone: "+phone+"\nGuests: "+guests+"\nArrival: "+arrival+"\nDeparture: "+departure+"\nNotes: "+notes+"\n\n— via Viata Luxe connect form"
      );
      var mailto = "mailto:info@viataluxe.com?subject="+subject+"&body="+body;
      showMsg("Opening your mail app… If nothing opens, email info@viataluxe.com directly. NightsBridge above is instant.", true);
      setTimeout(function(){ window.location.href = mailto; }, 400);
    });
  }

  /* ——— Masonry prepare ——— */
  function prepareMasonry(){
    var m = document.getElementById("masonry");
    if(!m) return;
    m.querySelectorAll("img").forEach(function(img){
      if(!img.complete) img.style.contentVisibility="auto";
    });
    if(window.ScrollTrigger) ScrollTrigger.refresh();
  }
  if(typeof window.prepare === "function"){
    var _oldPrepare = window.prepare;
    window.prepare = function(){ _oldPrepare(); prepareMasonry(); };
  } else {
    window.prepare = prepareMasonry;
  }
  window.addEventListener("load", prepareMasonry);
  prepareMasonry();

})();