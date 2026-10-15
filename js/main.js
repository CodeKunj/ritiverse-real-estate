/**
 * Lumera Heights - Master Luxury Real Estate Animation Engine
 * Seamless integration of Lenis Smooth Scroll, GSAP 3, ScrollTrigger & Micro-interactions
 */

// 1. Initialize Lenis Smooth Scroll
let lenis = null;
if (typeof Lenis !== 'undefined') {
  lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    orientation: 'vertical',
    gestureOrientation: 'vertical',
    smoothWheel: true,
    wheelMultiplier: 1,
    smoothTouch: false
  });
}

// 2. Link GSAP ScrollTrigger with Lenis Single-Ticker
if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
  gsap.registerPlugin(ScrollTrigger);

  if (lenis) {
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => {
      lenis.raf(time * 1000);
    });
    gsap.ticker.lagSmoothing(0);
  }
}

document.addEventListener('DOMContentLoaded', () => {

  // 3. Preloader Exit & Hero Entrance
  const preloader = document.getElementById('preloader');
  const header = document.querySelector('header');

  if (preloader) {
    setTimeout(() => {
      if (typeof gsap !== 'undefined') {
        gsap.to(preloader, {
          yPercent: -100,
          duration: 1.1,
          ease: 'power3.inOut',
          onComplete: () => {
            preloader.style.display = 'none';
            if (header) {
              header.classList.remove('-translate-y-full');
            }
            initHeroAnimations();
          }
        });
      } else {
        preloader.style.transition = 'transform 0.8s ease';
        preloader.style.transform = 'translateY(-100%)';
        setTimeout(() => {
          preloader.style.display = 'none';
          if (header) header.classList.remove('-translate-y-full');
          initHeroAnimations();
        }, 850);
      }
    }, 1500);
  } else {
    if (header) header.classList.remove('-translate-y-full');
    initHeroAnimations();
  }

  // 4. Header Scroll Blur Effect (Transparent at top -> White Glassmorphism on scroll)
  if (header) {
    const updateHeaderState = () => {
      const scrollPos = window.scrollY || (lenis ? lenis.scroll : 0);
      if (scrollPos > 30) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    };
    window.addEventListener('scroll', updateHeaderState, { passive: true });
    if (lenis) {
      lenis.on('scroll', updateHeaderState);
    }
    updateHeaderState();
  }

  // 5. Mobile Navigation Drawer Toggle
  const burgerBtn = document.querySelector('button[aria-label="Open navigation menu"]');
  const mobileDrawer = document.getElementById('mobileNavDrawer') || document.querySelector('header .fixed.inset-0');
  if (burgerBtn && mobileDrawer) {
    let isOpen = false;
    burgerBtn.addEventListener('click', () => {
      isOpen = !isOpen;
      burgerBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (isOpen) {
        mobileDrawer.classList.remove('-translate-y-full', 'hidden');
        mobileDrawer.classList.add('translate-y-0', 'block');
        document.body.style.overflow = 'hidden';
      } else {
        mobileDrawer.classList.add('-translate-y-full');
        mobileDrawer.classList.remove('translate-y-0');
        document.body.style.overflow = '';
      }
    });

    mobileDrawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        isOpen = false;
        burgerBtn.setAttribute('aria-expanded', 'false');
        mobileDrawer.classList.add('-translate-y-full');
        mobileDrawer.classList.remove('translate-y-0');
        document.body.style.overflow = '';
      });
    });
  }

  // 6. About Section - Circular Arc 3D Carousel Controller
  initAboutCarousel();

  // 7. Core Features Center Image Switcher
  initCoreFeaturesSwitcher();

  // 8. Horizontal Pinned Scroll for #lumera-features
  initFeaturesHorizontalPin();

  // 9. Parallax Architecture Cards & Watermark Title
  initArchitectureParallax();

  // 10. Draggable Refinement & Highlights Progress Bars
  initHorizontalDraggables();

  // 11. FAQ Accordion Interaction
  initFaqAccordion();

  // 12. ROI Counter Trigger via ScrollTrigger
  initRoiCounters();

  // 13. Dynamic Curtain Footer Reveal
  initFooterReveal();

  // 14. Showcase Video Expansion on Scroll
  initShowcaseAnimation();

  // 15. Bringing Section Kinetic Text & Video Expansion
  initBringingAnimation();

  // 16. Autoplay Video Engine
  initAutoplayVideos();

  // 14. Smooth scrolling for hash anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href && href !== '#' && href.startsWith('#')) {
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          if (lenis) {
            lenis.scrollTo(target, { offset: -60, duration: 1.2 });
          } else {
            target.scrollIntoView({ behavior: 'smooth' });
          }
        }
      }
    });
  });

});

// Curtain Footer Reveal Spacer
function initFooterReveal() {
  const footer = document.getElementById('footer');
  const siteWrapper = document.querySelector('.site-content-wrapper');
  if (!footer || !siteWrapper) return;

  let resizeTimer;
  const updateFooterSpace = () => {
    if (window.innerWidth <= 1025) {
      siteWrapper.style.marginBottom = '0px';
    } else {
      if (footer && siteWrapper) {
        siteWrapper.style.marginBottom = `${footer.offsetHeight}px`;
      }
    }
  };

  updateFooterSpace();
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(updateFooterSpace, 120);
  }, { passive: true });
  window.addEventListener('load', updateFooterSpace);
}

// ==========================================
// A. HERO ANIMATIONS & ROLLING DIGIT WHEELS
// ==========================================
function initHeroAnimations() {
  if (typeof gsap === 'undefined') return;

  // Stagger in the massive hero typography
  gsap.from('#hero h1 span', {
    y: 50,
    opacity: 0,
    stagger: 0.12,
    duration: 1.1,
    ease: 'power4.out'
  });

  gsap.from('#hero p, #hero a[data-pressed]', {
    y: 30,
    opacity: 0,
    stagger: 0.1,
    duration: 0.9,
    ease: 'power3.out',
    delay: 0.2
  });

  // Stagger in the Hero stat cards
  gsap.from('#hero .grid > div', {
    y: 35,
    opacity: 0,
    stagger: 0.1,
    duration: 0.9,
    ease: 'power3.out',
    delay: 0.35
  });
}

function initRoiCounters() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  gsap.from('#roi .fadeup > div', {
    scrollTrigger: {
      trigger: '#roi',
      start: 'top 80%',
      once: true
    },
    y: 35,
    opacity: 0,
    stagger: 0.1,
    duration: 0.9,
    ease: 'power3.out'
  });
}

// ==========================================
// B. ABOUT SECTION CIRCULAR 3D ARC CAROUSEL
// ==========================================
let aboutCurrentIndex = 2; // Slide 2 (Architectural Identity) is default active
const aboutTotalSlides = 7;

function initAboutCarousel() {
  const slides = document.querySelectorAll('.about-circle-slide');
  if (!slides.length) return;

  const prevBtn = document.getElementById('aboutPrevBtn');
  const nextBtn = document.getElementById('aboutNextBtn');
  const track = document.getElementById('aboutCarouselTrack');

  function render(animated = true) {
    const isMobile = window.innerWidth <= 1024;
    
    slides.forEach((slide) => {
      const idx = parseInt(slide.getAttribute('data-index'), 10);
      
      // Calculate circular distance
      let diff = idx - aboutCurrentIndex;
      if (diff > 3) diff -= 7;
      if (diff < -3) diff += 7;

      const absDiff = Math.abs(diff);

      let x, y, scale, opacity, zIndex;

      if (!isMobile) {
        x = diff * 21; // in vw
        y = absDiff * 3.5; // parabolic arc curve in vw
        scale = Math.max(0.35, 1 - absDiff * 0.22);
        opacity = absDiff > 2 ? 0 : (absDiff === 0 ? 1 : 0.65);
        zIndex = 10 - absDiff;
      } else {
        x = diff * 45;
        y = absDiff * 5.5;
        scale = Math.max(0.4, 1 - absDiff * 0.28);
        opacity = absDiff > 1 ? 0 : (absDiff === 0 ? 1 : 0.55);
        zIndex = 10 - absDiff;
      }

      if (absDiff === 0) {
        slide.classList.add('is-active');
      } else {
        slide.classList.remove('is-active');
      }

      if (typeof gsap !== 'undefined' && animated) {
        gsap.to(slide, {
          xPercent: -50,
          yPercent: -50,
          x: x + 'vw',
          y: y + 'vw',
          scale: scale,
          opacity: opacity,
          zIndex: zIndex,
          duration: 0.75,
          ease: 'power3.out',
          overwrite: 'auto'
        });
      } else {
        slide.style.transform = `translate(calc(-50% + ${x}vw), calc(-50% + ${y}vw)) scale(${scale})`;
        slide.style.opacity = opacity;
        slide.style.zIndex = zIndex;
      }
    });

    // Update active slide text below
    const activeSlide = document.querySelector(`.about-circle-slide[data-index="${aboutCurrentIndex}"]`);
    if (activeSlide) {
      const title = activeSlide.getAttribute('data-title');
      const desc = activeSlide.getAttribute('data-desc');
      const textWrapper = document.getElementById('aboutTextWrapper');
      const activeTitleEl = document.getElementById('aboutActiveTitle');
      const activeDescEl = document.getElementById('aboutActiveDesc');

      if (textWrapper && activeTitleEl && activeDescEl) {
        textWrapper.style.opacity = '0';
        setTimeout(() => {
          if (title) activeTitleEl.textContent = title;
          if (desc) activeDescEl.textContent = desc;
          textWrapper.style.opacity = '1';
        }, 180);
      }
    }
  }

  // Initial layout
  render(false);

  // Resize listener (debounced)
  let aboutResizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(aboutResizeTimer);
    aboutResizeTimer = setTimeout(() => {
      render(false);
    }, 120);
  }, { passive: true });

  // Navigation click handlers
  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      aboutCurrentIndex = (aboutCurrentIndex + 1) % aboutTotalSlides;
      render(true);
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      aboutCurrentIndex = (aboutCurrentIndex - 1 + aboutTotalSlides) % aboutTotalSlides;
      render(true);
    });
  }

  // Direct click on slides (only when not dragging)
  let isAboutDragging = false;
  slides.forEach(slide => {
    slide.addEventListener('click', (e) => {
      if (isAboutDragging) {
        e.preventDefault();
        return;
      }
      const clickedIdx = parseInt(slide.getAttribute('data-index'), 10);
      if (clickedIdx !== aboutCurrentIndex) {
        aboutCurrentIndex = clickedIdx;
        render(true);
      }
    });
  });

  // Touch Swipe & Desktop Mouse Drag & Wheel for About Carousel
  if (track) {
    let startX = 0;
    let isMouseDown = false;

    // Mobile touch
    track.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchend', (e) => {
      const endX = e.changedTouches[0].clientX;
      const diffX = endX - startX;
      if (Math.abs(diffX) > 40) {
        if (diffX < 0) {
          aboutCurrentIndex = (aboutCurrentIndex + 1) % aboutTotalSlides;
        } else {
          aboutCurrentIndex = (aboutCurrentIndex - 1 + aboutTotalSlides) % aboutTotalSlides;
        }
        render(true);
      }
    }, { passive: true });

    // Desktop mouse drag
    track.addEventListener('mousedown', (e) => {
      isMouseDown = true;
      isAboutDragging = false;
      startX = e.clientX;
    });

    window.addEventListener('mousemove', (e) => {
      if (!isMouseDown) return;
      if (Math.abs(e.clientX - startX) > 6) {
        isAboutDragging = true;
      }
    });

    window.addEventListener('mouseup', (e) => {
      if (!isMouseDown) return;
      isMouseDown = false;
      const diffX = e.clientX - startX;
      if (Math.abs(diffX) > 45) {
        if (diffX < 0) {
          aboutCurrentIndex = (aboutCurrentIndex + 1) % aboutTotalSlides;
        } else {
          aboutCurrentIndex = (aboutCurrentIndex - 1 + aboutTotalSlides) % aboutTotalSlides;
        }
        render(true);
      }
      setTimeout(() => { isAboutDragging = false; }, 80);
    });

    // Trackpad horizontal swipe
    let aboutWheelCooldown = false;
    track.addEventListener('wheel', (e) => {
      const delta = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : (e.shiftKey ? e.deltaY : 0);
      if (Math.abs(delta) > 25 && !aboutWheelCooldown) {
        aboutWheelCooldown = true;
        if (delta > 0) {
          aboutCurrentIndex = (aboutCurrentIndex + 1) % aboutTotalSlides;
        } else {
          aboutCurrentIndex = (aboutCurrentIndex - 1 + aboutTotalSlides) % aboutTotalSlides;
        }
        render(true);
        setTimeout(() => { aboutWheelCooldown = false; }, 350);
      }
    }, { passive: true });
  }
}

// ==========================================
// C. CORE FEATURES IMAGE PREVIEW SWITCHER
// ==========================================
function initCoreFeaturesSwitcher() {
  const items = document.querySelectorAll('.core-feature-item');
  const mainImg = document.getElementById('coreFeatureMainImg');
  if (!items.length || !mainImg) return;

  items.forEach(item => {
    const activate = () => {
      items.forEach(el => el.classList.remove('active'));
      item.classList.add('active');

      const targetImgSrc = item.getAttribute('data-img');
      if (targetImgSrc && mainImg.getAttribute('src') !== targetImgSrc) {
        if (typeof gsap !== 'undefined') {
          gsap.to(mainImg, {
            opacity: 0.4,
            duration: 0.15,
            ease: 'power2.in',
            onComplete: () => {
              mainImg.setAttribute('src', targetImgSrc);
              gsap.to(mainImg, { opacity: 1, duration: 0.35, ease: 'power2.out' });
            }
          });
        } else {
          mainImg.setAttribute('src', targetImgSrc);
        }
      }
    };

    item.addEventListener('mouseenter', activate);
    item.addEventListener('click', activate);
  });
}

// ==========================================
// D. HORIZONTAL PINNED RESIDENCE TRACK
// ==========================================
let currentResidenceIdx = 0;
const totalResidences = 5;

function initFeaturesHorizontalPin() {
  const featuresSection = document.getElementById('lumera-features');
  const residenceTrack = document.querySelector('.lumera-residence-track');
  
  if (!featuresSection || !residenceTrack || typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const mm = gsap.matchMedia();
  mm.add("(min-width: 1026px)", () => {
    const getScrollAmount = () => Math.max(0, residenceTrack.scrollWidth - window.innerWidth);

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: featuresSection,
        start: 'top top',
        end: 'bottom bottom',
        scrub: 0.8,
        invalidateOnRefresh: true,
        id: 'residenceTrigger',
        onUpdate: (self) => {
          const idx = Math.min(totalResidences - 1, Math.floor(self.progress * totalResidences));
          if (idx !== currentResidenceIdx) {
            currentResidenceIdx = idx;
            updateResidenceButtons();
          }
        }
      }
    });

    tl.to(residenceTrack, {
      x: () => -getScrollAmount(),
      ease: 'none'
    });

    // Horizontal wheel / trackpad gesture passthrough
    const onFeaturesWheel = (e) => {
      const delta = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : (e.shiftKey ? e.deltaY : 0);
      if (Math.abs(delta) > 10) {
        const st = ScrollTrigger.getById('residenceTrigger');
        if (st && st.isActive) {
          e.preventDefault();
          const targetY = window.scrollY + delta * 2;
          if (typeof lenis !== 'undefined' && lenis) {
            lenis.scrollTo(targetY, { immediate: true });
          } else {
            window.scrollBy({ top: delta * 2, behavior: 'auto' });
          }
        }
      }
    };
    featuresSection.addEventListener('wheel', onFeaturesWheel, { passive: false });

    // Drag navigation across residences
    let resDragDown = false;
    let resStartX = 0;
    let resDeltaX = 0;

    const onResMouseDown = (e) => {
      if (e.target.closest('button, a, input, select')) return;
      resDragDown = true;
      resStartX = e.clientX;
      resDeltaX = 0;
    };

    const onResMouseMove = (e) => {
      if (!resDragDown) return;
      resDeltaX = e.clientX - resStartX;
    };

    const onResMouseUp = () => {
      if (!resDragDown) return;
      resDragDown = false;
      if (Math.abs(resDeltaX) > 60) {
        if (resDeltaX < 0) {
          nextResidence();
        } else {
          prevResidence();
        }
      }
    };

    residenceTrack.addEventListener('mousedown', onResMouseDown);
    window.addEventListener('mousemove', onResMouseMove);
    window.addEventListener('mouseup', onResMouseUp);

    return () => {
      tl.kill();
      gsap.set(residenceTrack, { clearProps: "all" });
      featuresSection.removeEventListener('wheel', onFeaturesWheel);
      residenceTrack.removeEventListener('mousedown', onResMouseDown);
      window.removeEventListener('mousemove', onResMouseMove);
      window.removeEventListener('mouseup', onResMouseUp);
    };
  });
}

function updateResidenceButtons() {
  document.querySelectorAll('.residence-tab-btn').forEach((btn, idx) => {
    if (idx === currentResidenceIdx) {
      btn.classList.add('bg-white', 'text-[#1c1b1a]');
      btn.classList.remove('text-white/70');
    } else {
      btn.classList.remove('bg-white', 'text-[#1c1b1a]');
      btn.classList.add('text-white/70');
    }
  });
}

function goToResidence(index) {
  currentResidenceIdx = Math.max(0, Math.min(totalResidences - 1, index));
  const st = ScrollTrigger.getById('residenceTrigger');
  
  if (st) {
    const progress = currentResidenceIdx / (totalResidences - 1);
    const targetScroll = st.start + progress * (st.end - st.start);
    if (lenis) {
      lenis.scrollTo(targetScroll, { duration: 1.2 });
    } else {
      window.scrollTo({ top: targetScroll, behavior: 'smooth' });
    }
  } else {
    const track = document.querySelector('.lumera-residence-track');
    if (track) {
      const cardWidth = window.innerWidth;
      track.style.transition = 'transform 0.65s cubic-bezier(0.16, 1, 0.3, 1)';
      track.style.transform = `translate3d(-${currentResidenceIdx * cardWidth}px, 0, 0)`;
    }
  }
  updateResidenceButtons();
}

function nextResidence() {
  goToResidence(currentResidenceIdx + 1);
}

function prevResidence() {
  goToResidence(currentResidenceIdx - 1);
}

// ==========================================
// E. ARCHITECTURE PARALLAX & WATERMARK
// ==========================================
function initArchitectureParallax() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  // Stagger parallax columns
  gsap.utils.toArray('[data-scroll="true"]').forEach((col, i) => {
    const speed = parseFloat(col.getAttribute('data-scroll-speed') || 0.1);
    gsap.to(col, {
      y: (i % 2 === 0 ? -50 : 50) * (speed * 10),
      ease: 'none',
      scrollTrigger: {
        trigger: '#works',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      }
    });
  });

  // Background watermark "LUMERA HEIGHTS" subtle scale scrub
  const watermark = document.getElementById('work-bg-title');
  if (watermark) {
    gsap.to(watermark, {
      scale: 1.05,
      opacity: 0.08,
      ease: 'none',
      scrollTrigger: {
        trigger: '#works',
        start: 'top top',
        end: 'bottom bottom',
        scrub: true
      }
    });
  }

  // Hero background image subtle scale
  const heroImg = document.querySelector('#hero img');
  if (heroImg) {
    gsap.to(heroImg, {
      scale: 1.12,
      ease: 'none',
      scrollTrigger: {
        trigger: '#hero',
        start: 'top top',
        end: 'bottom top',
        scrub: true
      }
    });
  }

  // Fadeup elements reveal
  gsap.utils.toArray('.fadeup, [data-copy-wrapper="true"]').forEach((el) => {
    gsap.fromTo(el, 
      { y: 30, opacity: 0 },
      {
        y: 0,
        opacity: 1,
        duration: 0.85,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: el,
          start: 'top 88%',
          toggleActions: 'play none none none'
        }
      }
    );
  });
}

// ==========================================
// F. DRAGGABLE PROGRESS BARS & HORIZONTAL CAROUSELS
// ==========================================
function initHorizontalDraggables() {
  function setupSlider(trackId, progressId) {
    const track = document.getElementById(trackId);
    const progress = document.getElementById(progressId);
    if (!track) return;

    let isDown = false;
    let startX = 0;
    let scrollStart = 0;
    let isDragging = false;
    let momentumID = null;
    let velX = 0;
    let lastX = 0;
    let lastTime = 0;

    // Real-time progress bar indicator updater
    const updateProgressBar = () => {
      if (!progress) return;
      const maxScroll = track.scrollWidth - track.clientWidth;
      if (maxScroll <= 2) {
        progress.style.width = '100%';
        progress.style.left = '0%';
        return;
      }
      const scrollRatio = Math.max(0, Math.min(1, track.scrollLeft / maxScroll));
      const thumbWidthPercent = Math.max(16, Math.min(38, (track.clientWidth / track.scrollWidth) * 100));
      const availableRange = 100 - thumbWidthPercent;
      progress.style.width = `${thumbWidthPercent}%`;
      progress.style.left = `${scrollRatio * availableRange}%`;
    };

    track.addEventListener('scroll', updateProgressBar, { passive: true });
    window.addEventListener('resize', updateProgressBar, { passive: true });
    setTimeout(updateProgressBar, 150);
    setTimeout(updateProgressBar, 600);

    // Mouse drag interaction
    track.addEventListener('mousedown', (e) => {
      if (momentumID) cancelAnimationFrame(momentumID);
      isDown = true;
      isDragging = false;
      track.classList.add('cursor-grabbing');
      track.classList.remove('cursor-grab');
      startX = e.pageX - track.offsetLeft;
      scrollStart = track.scrollLeft;
      lastX = e.pageX;
      lastTime = performance.now();
      velX = 0;
    });

    window.addEventListener('mouseup', () => {
      if (!isDown) return;
      isDown = false;
      track.classList.remove('cursor-grabbing');
      track.classList.add('cursor-grab');

      // Inertial momentum on mouse release
      if (Math.abs(velX) > 0.5) {
        const stepMomentum = () => {
          track.scrollLeft -= velX;
          velX *= 0.92;
          if (Math.abs(velX) > 0.5) {
            momentumID = requestAnimationFrame(stepMomentum);
          }
        };
        momentumID = requestAnimationFrame(stepMomentum);
      }
      setTimeout(() => { isDragging = false; }, 80);
    });

    track.addEventListener('mouseleave', () => {
      if (isDown) {
        isDown = false;
        track.classList.remove('cursor-grabbing');
        track.classList.add('cursor-grab');
      }
    });

    track.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - track.offsetLeft;
      const walk = (x - startX) * 1.4;
      if (Math.abs(walk) > 4) isDragging = true;
      track.scrollLeft = scrollStart - walk;

      const now = performance.now();
      const dt = now - lastTime || 16;
      velX = ((e.pageX - lastX) / dt) * 16;
      lastX = e.pageX;
      lastTime = now;
    });

    // Suppress child links / click triggers while dragging
    track.addEventListener('click', (e) => {
      if (isDragging) {
        e.preventDefault();
        e.stopPropagation();
      }
    }, true);

    // Trackpad horizontal swipe / mouse wheel support
    track.addEventListener('wheel', (e) => {
      const delta = Math.abs(e.deltaX) > Math.abs(e.deltaY)
        ? e.deltaX
        : (e.shiftKey ? e.deltaY : e.deltaY * 0.85);

      const maxScroll = track.scrollWidth - track.clientWidth;
      if (maxScroll > 0) {
        const atStart = track.scrollLeft <= 0 && delta < 0;
        const atEnd = track.scrollLeft >= maxScroll - 2 && delta > 0;
        if (!atStart && !atEnd) {
          e.preventDefault();
          track.scrollLeft += delta;
          updateProgressBar();
        }
      }
    }, { passive: false });

    // Scrub / click on progress bar track
    if (progress && progress.parentElement) {
      const barTrack = progress.parentElement;
      barTrack.style.cursor = 'pointer';
      barTrack.addEventListener('click', (e) => {
        const rect = barTrack.getBoundingClientRect();
        const clickRatio = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
        const maxScroll = track.scrollWidth - track.clientWidth;
        track.scrollTo({ left: clickRatio * maxScroll, behavior: 'smooth' });
      });
    }
  }

  // Initialize all horizontal drag tracks across the project
  setupSlider('projectSliderTrack', 'projectProgressBar');
  setupSlider('highlightsSliderTrack', 'highlightsProgressBar');
  setupSlider('servicesSliderTrack', 'servicesProgressBar');
}

// ==========================================
// G. FAQ ACCORDION INTERACTION
// ==========================================
function initFaqAccordion() {
  const faqButtons = document.querySelectorAll('#faq [role="button"]');
  if (!faqButtons.length) return;

  faqButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const isExpanded = btn.getAttribute('aria-expanded') === 'true';
      const willExpand = !isExpanded;

      // Close all other panels
      faqButtons.forEach(other => {
        if (other !== btn) {
          other.setAttribute('aria-expanded', 'false');
          other.classList.remove('faq-active-sand');
          const rotateIcon = other.querySelector('.rotate-0, .rotate-90');
          if (rotateIcon) {
            rotateIcon.classList.add('rotate-90');
            rotateIcon.classList.remove('rotate-0');
          }
          const otherPanel = document.getElementById(other.getAttribute('aria-controls'));
          if (otherPanel) {
            if (typeof gsap !== 'undefined') {
              gsap.to(otherPanel, { height: 0, opacity: 0, duration: 0.35, ease: 'power2.out', onComplete: () => { otherPanel.style.display = 'none'; } });
            } else {
              otherPanel.style.height = '0px';
              otherPanel.style.opacity = '0';
              otherPanel.style.display = 'none';
            }
          }
        }
      });

      // Toggle clicked panel
      btn.setAttribute('aria-expanded', willExpand ? 'true' : 'false');
      const rotateIcon = btn.querySelector('.rotate-0, .rotate-90');
      if (rotateIcon) {
        if (willExpand) {
          rotateIcon.classList.remove('rotate-90');
          rotateIcon.classList.add('rotate-0');
        } else {
          rotateIcon.classList.remove('rotate-0');
          rotateIcon.classList.add('rotate-90');
        }
      }

      const panel = document.getElementById(btn.getAttribute('aria-controls'));
      if (panel) {
        if (willExpand) {
          btn.classList.add('faq-active-sand');
          panel.style.display = 'block';
          panel.style.height = 'auto';
          const targetHeight = panel.scrollHeight;
          panel.style.height = '0px';
          
          if (typeof gsap !== 'undefined') {
            gsap.to(panel, {
              height: targetHeight,
              opacity: 1,
              duration: 0.4,
              ease: 'power3.out'
            });
          } else {
            panel.style.height = targetHeight + 'px';
            panel.style.opacity = '1';
          }
        } else {
          btn.classList.remove('faq-active-sand');
          if (typeof gsap !== 'undefined') {
            gsap.to(panel, {
              height: 0,
              opacity: 0,
              duration: 0.3,
              ease: 'power2.out',
              onComplete: () => { panel.style.display = 'none'; }
            });
          } else {
            panel.style.height = '0px';
            panel.style.opacity = '0';
            panel.style.display = 'none';
          }
        }
      }
    });
  });
}

// ==========================================
// H. ENQUIRE MODAL & TOAST NOTIFICATION
// ==========================================
function openEnquireModal(residenceId = '') {
  const modal = document.getElementById('enquireModal');
  if (modal) {
    if (residenceId) {
      const select = modal.querySelector('select[name="residence"]');
      if (select) select.value = residenceId;
    }
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeEnquireModal() {
  const modal = document.getElementById('enquireModal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeEnquireModal();
  }
});

function showToast(message) {
  const toast = document.getElementById('lumeraToast');
  const msgElem = document.getElementById('lumeraToastMessage');
  if (toast && msgElem) {
    msgElem.textContent = message;
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
    }, 4500);
  }
}

function submitEnquiry(e) {
  e.preventDefault();
  const form = e.target;
  const btn = document.getElementById('enquireSubmitBtn');
  const originalText = btn.textContent;
  btn.disabled = true;
  btn.textContent = 'Submitting Request...';

  const formData = new FormData(form);

  fetch('enquire_handler.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    btn.disabled = false;
    btn.textContent = originalText;
    if (data.success) {
      closeEnquireModal();
      showToast(data.message || 'Enquiry successfully submitted!');
      form.reset();
    } else {
      alert(data.message || 'Something went wrong. Please try again.');
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.textContent = originalText;
    console.error('Enquiry error:', err);
    closeEnquireModal();
    showToast('Your request has been received. Our team will contact you.');
  });
}

// ==========================================
// I. SHOWCASE SECTION VIDEO EXPANSION ON SCROLL
// ==========================================
function initShowcaseAnimation() {
  const showcase = document.getElementById('showcase');
  if (!showcase) return;

  const video = showcase.querySelector('video');
  const videoBlock = showcase.querySelector('.video-block');
  const innerDetail = showcase.querySelector('.inner-detail');

  // Video autoplay assurance
  if (video) {
    video.muted = true;
    const playPromise = video.play();
    if (playPromise !== undefined) {
      playPromise.catch(() => {
        const startOnScroll = () => {
          video.play().catch(() => {});
          window.removeEventListener('scroll', startOnScroll);
          window.removeEventListener('touchstart', startOnScroll);
        };
        window.addEventListener('scroll', startOnScroll, { passive: true });
        window.addEventListener('touchstart', startOnScroll, { passive: true });
      });
    }
  }

  // GSAP ScrollTrigger timeline matching the reference site
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' && videoBlock) {
    const mm = gsap.matchMedia();
    mm.add("(min-width: 1025px)", () => {
      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: showcase,
          start: "30% 80%",
          end: "bottom 95%",
          scrub: 0.25,
          invalidateOnRefresh: true
        }
      });

      tl.to(videoBlock, {
        width: "90vw",
        height: "44vw",
        yPercent: 30,
        right: "5%",
        ease: "power1.out",
        duration: 5
      }, 0);

      if (innerDetail) {
        tl.to(innerDetail, {
          opacity: 1,
          duration: 1.2,
          ease: "power1.out"
        }, 3.5);
      }

      return () => {
        tl.kill();
        gsap.set([videoBlock, innerDetail].filter(Boolean), { clearProps: "all" });
      };
    });
  }
}

// ==========================================
// J. AUTOPLAY VIDEO ENGINE
// ==========================================
function initAutoplayVideos() {
  const videos = document.querySelectorAll('video');
  videos.forEach(v => {
    v.muted = true;
    const play = () => {
      v.play().catch(() => {});
    };
    play();
    window.addEventListener('scroll', play, { once: true, passive: true });
    window.addEventListener('click', play, { once: true, passive: true });
    window.addEventListener('touchstart', play, { once: true, passive: true });
  });
}

// ==========================================
// K. BRINGING SECTION KINETIC TEXT & VIDEO EXPANSION
// ==========================================
function initBringingAnimation() {
  const section = document.getElementById('bringing-desktop');
  if (!section) return;

  const line1 = section.querySelector('.bringing-title-1');
  const line2 = section.querySelector('.bringing-title-2');
  const line3 = section.querySelector('.bringing-title-3');
  const videoBox = section.querySelector('.bringing-video-box');

  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const mm = gsap.matchMedia();
  mm.add("(min-width: 1025px)", () => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: "top top",
        end: "+=3200",
        pin: true,
        pinSpacing: true,
        scrub: 1,
        anticipatePin: 1,
        invalidateOnRefresh: true
      },
      defaults: { ease: "none" }
    });

    // Phase 1: Lines 1 & 3 glide gracefully from right to center (120% -> 0%)
    if (line1) {
      tl.fromTo(line1,
        { xPercent: 120 },
        { xPercent: 0, duration: 4.0, ease: "power1.inOut" },
        0
      );
    }
    // Phase 1: Line 2 glides gracefully from left to center (-120% -> 0%)
    if (line2) {
      tl.fromTo(line2,
        { xPercent: -120 },
        { xPercent: 0, duration: 4.0, ease: "power1.inOut" },
        0
      );
    }
    if (line3) {
      tl.fromTo(line3,
        { xPercent: 120 },
        { xPercent: 0, duration: 4.0, ease: "power1.inOut" },
        0
      );
    }

    // Phase 2: Distinct pause at center (4.0s to 4.8s) so user sees all 3 lines aligned
    tl.to({}, { duration: 0.8 }, 4.0);

    // Phase 3: Video box in Line 2 expands smoothly to max scale, while surrounding text fades out
    const textsToFade = [line1, line2 ? line2.querySelector('p') : null, line3].filter(Boolean);
    if (textsToFade.length) {
      tl.to(textsToFade, {
        opacity: 0,
        duration: 1.6,
        ease: "power1.out"
      }, 4.8);
    }

    if (videoBox) {
      tl.fromTo(videoBox,
        { scale: 1, xPercent: 0, yPercent: 0 },
        {
          scale: 4.2,
          xPercent: -135,
          yPercent: -20,
          position: "absolute",
          duration: 4.2,
          ease: "power1.inOut"
        },
        4.8
      );
    }

    // Phase 4: Hold at max size (9.0s to 11.0s) for continuous luxury video viewing before unpinning
    tl.to({}, { duration: 2.0 }, 9.0);

    return () => {
      tl.kill();
      gsap.set([line1, line2, line3, videoBox, line2 ? line2.querySelector('p') : null].filter(Boolean), { clearProps: "all" });
    };
  });
}




