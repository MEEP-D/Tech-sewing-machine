/**
 * Modern Interactivity for Máy May Thông Minh
 */

document.addEventListener('DOMContentLoaded', () => {
    const scheduleTask = (callback, timeout = 1200) => {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(callback, { timeout });
            return;
        }

        setTimeout(callback, Math.min(timeout, 400));
    };

    // 1. Reveal Animations on Scroll
    const setupRevealAnimations = () => {
        if (!('IntersectionObserver' in window)) return;

        const revealCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal');
                    observer.unobserve(entry.target);
                }
            });
        };

        const revealObserver = new IntersectionObserver(revealCallback, {
            threshold: 0.1
        });

        document.querySelectorAll('.product-card, .section-header').forEach(el => {
            el.style.opacity = '0';
            revealObserver.observe(el);
        });
    };

    scheduleTask(setupRevealAnimations, 1800);

    const deferredBackgrounds = document.querySelectorAll('[data-bg]');
    const applyDeferredBackground = (element) => {
        if (!element || element.dataset.bgLoaded === '1') return;

        const bg = element.getAttribute('data-bg');
        if (!bg) return;

        element.style.backgroundImage = `url("${bg}")`;
        element.dataset.bgLoaded = '1';
    };

    if ('IntersectionObserver' in window) {
        const backgroundObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                applyDeferredBackground(entry.target);
                observer.unobserve(entry.target);
            });
        }, {
            rootMargin: '300px 0px'
        });

        deferredBackgrounds.forEach((element) => {
            if (element.classList.contains('hero-slide') && !element.classList.contains('active')) {
                return;
            }

            backgroundObserver.observe(element);
        });
    } else {
        deferredBackgrounds.forEach(applyDeferredBackground);
    }

    // 2. Header stays fixed at top via CSS; no hide/show behavior on scroll.

    // 3. Search Box Interactivity
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('active');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('active');
        });
    }

    // 3.1 Desktop overflow menu (right drawer)
    const navLinksRoot = document.querySelector('.nav-links');
    const desktopMoreToggle = document.getElementById('desktop-more-toggle');
    const desktopMoreDrawer = document.getElementById('desktop-more-drawer');
    const desktopMoreList = document.getElementById('desktop-more-list');
    const desktopMoreClose = document.getElementById('desktop-more-close');
    const menuOverlay = document.getElementById('menu-overlay');
    let navOverflowItems = [];

    const restoreOverflowItems = () => {
        if (!navLinksRoot || !desktopMoreList) return;
        navOverflowItems.forEach(item => navLinksRoot.appendChild(item));
        navOverflowItems = [];
        desktopMoreList.innerHTML = '';
    };

    const rebalanceOverflowMenu = () => {
        if (!navLinksRoot || !desktopMoreList || !desktopMoreToggle) return;
        restoreOverflowItems();

        if (window.innerWidth <= 1024) {
            desktopMoreToggle.style.display = 'none';
            return;
        }

        const navItems = Array.from(navLinksRoot.querySelectorAll(':scope > .nav-item:not(.more-menu)'));
        if (navItems.length === 0) return;
        const DESKTOP_VISIBLE_MENU_LIMIT = 6;
        const overflowed = navItems.slice(DESKTOP_VISIBLE_MENU_LIMIT);

        desktopMoreToggle.style.display = overflowed.length > 0 ? 'inline-flex' : 'none';
        if (overflowed.length === 0) return;

        overflowed.forEach(item => {
            navOverflowItems.push(item);
            desktopMoreList.appendChild(item);
        });
    };

    if (desktopMoreToggle && desktopMoreDrawer && desktopMoreList && menuOverlay) {
        const openDesktopDrawer = () => {
            desktopMoreDrawer.classList.add('active');
            menuOverlay.classList.add('active');
            desktopMoreToggle.setAttribute('aria-expanded', 'true');
            desktopMoreDrawer.setAttribute('aria-hidden', 'false');
            document.body.classList.add('menu-open');
        };

        const closeDesktopDrawer = () => {
            desktopMoreDrawer.classList.remove('active');
            menuOverlay.classList.remove('active');
            desktopMoreToggle.setAttribute('aria-expanded', 'false');
            desktopMoreDrawer.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('menu-open');
        };

        desktopMoreToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = desktopMoreDrawer.classList.contains('active');
            if (isOpen) closeDesktopDrawer();
            else openDesktopDrawer();
        });

        if (desktopMoreClose) {
            desktopMoreClose.addEventListener('click', closeDesktopDrawer);
        }

        menuOverlay.addEventListener('click', () => {
            if (desktopMoreDrawer.classList.contains('active')) {
                closeDesktopDrawer();
            }
        });

        document.addEventListener('click', (e) => {
            const isOpen = desktopMoreDrawer.classList.contains('active');
            if (!isOpen) return;

            const clickedInsideDrawer = desktopMoreDrawer.contains(e.target);
            const clickedToggle = desktopMoreToggle.contains(e.target);
            if (!clickedInsideDrawer && !clickedToggle) {
                closeDesktopDrawer();
            }
        });

        let resizeTimer;
        const onResize = () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                closeDesktopDrawer();
                rebalanceOverflowMenu();
            }, 120);
        };

        window.addEventListener('resize', onResize);
        window.addEventListener('load', rebalanceOverflowMenu);
        rebalanceOverflowMenu();
    }

    let mobileSubmenuId = 0;
    const getDirectChild = (parent, selector) => {
        return Array.from(parent.children).find((child) => child.matches(selector)) || null;
    };

    const ensureMobileSubmenuToggles = () => {
        const expandableItems = document.querySelectorAll('.nav-item.has-children, .mega-links li.has-children');
        expandableItems.forEach((item) => {
            const link = getDirectChild(item, 'a');
            const panel = getDirectChild(item, '.mega-menu, .sub-links');
            if (!link || !panel) return;

            item.classList.add('has-mobile-toggle');

            let toggle = getDirectChild(item, '.mobile-submenu-toggle');
            if (!toggle) {
                toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'mobile-submenu-toggle';
                toggle.innerHTML = '<i class="fas fa-chevron-down" aria-hidden="true"></i>';
                item.insertBefore(toggle, panel);
            }

            if (!panel.id) {
                mobileSubmenuId += 1;
                panel.id = `mobile-submenu-${mobileSubmenuId}`;
            }

            toggle.setAttribute('aria-controls', panel.id);
            toggle.setAttribute('aria-expanded', item.classList.contains('is-open') ? 'true' : 'false');
            toggle.setAttribute('aria-label', item.classList.contains('is-open') ? 'Thu gọn menu con' : 'Mở menu con');
        });
    };

    const syncMobileSubmenuToggle = (item) => {
        if (!item) return;
        const toggle = getDirectChild(item, '.mobile-submenu-toggle');
        if (!toggle) return;

        const isOpen = item.classList.contains('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        toggle.setAttribute('aria-label', isOpen ? 'Thu gọn menu con' : 'Mở menu con');
    };

    ensureMobileSubmenuToggles();

    // 4. Mega Menu Accordion Logic (Shared and Responsive)
    const setupAccordion = (selector, childSelector, forceAllSizes = false) => {
        const parents = document.querySelectorAll(selector);
        parents.forEach(parent => {
            const children = parent.querySelector(childSelector);
            if (children) {
                const link = parent.querySelector('a');
                link.addEventListener('click', (e) => {
                    const isMobile = window.innerWidth <= 1024; // Align with CSS media queries
                    if (isMobile && getDirectChild(parent, '.mobile-submenu-toggle')) {
                        return;
                    }
                    if (isMobile || forceAllSizes) {
                        // If closed, prevent navigation and open it
                        if (!parent.classList.contains('is-open')) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Close other siblings for a clean accordion look
                            const siblings = parent.parentElement.querySelectorAll('.is-open');
                            siblings.forEach(sibling => {
                                if (sibling !== parent) {
                                    sibling.classList.remove('is-open');
                                    syncMobileSubmenuToggle(sibling);
                                }
                            });

                            parent.classList.add('is-open');
                            syncMobileSubmenuToggle(parent);
                        }
                    }
                });
            }
        });
    };

    // Setup for Top-Level Nav Items (Sản Phẩm) - Mobile Only
    setupAccordion('.nav-item', '.mega-menu');
    // Setup for Mega Menu Sub-items (Máy May 1 Kim, etc.) - All Sizes
    setupAccordion('.mega-links li', '.sub-links', true);

    // 5. Hero Slider Logic (Reusable Premium Hero Slider)
    const slides = document.querySelectorAll('.hero-slide');
    const contents = document.querySelectorAll('.hero-slide-content');
    const dots = document.querySelectorAll('.dot');
    const heroSection = document.querySelector('.hero');
    let currentSlide = 0;
    const slideCount = slides.length;
    let slideInterval;

    const loadHeroSlideBackground = (slide) => {
        if (!slide) return;
        applyDeferredBackground(slide);
    };

    const warmHeroSlides = (index) => {
        if (slideCount <= 1) return;

        const nextIndexes = [
            (index + 1) % slideCount,
            (index - 1 + slideCount) % slideCount,
        ];

        scheduleTask(() => {
            nextIndexes.forEach((slideIndex) => loadHeroSlideBackground(slides[slideIndex]));
        }, 900);
    };

    const getActiveSlide = () => slides[currentSlide] || null;
    const getActiveSlideLink = () => {
        const activeSlide = getActiveSlide();
        return activeSlide ? (activeSlide.getAttribute('data-link') || '').trim() : '';
    };

    const showSlide = (index) => {
        loadHeroSlideBackground(slides[index]);

        // Remove active states
        slides.forEach(s => s.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        // Set new active state
        slides[index].classList.add('active');
        const slideLink = slides[index].getAttribute('data-link') || '';
        slides[index].style.cursor = slideLink ? 'pointer' : 'default';
        if (heroSection) {
            heroSection.style.cursor = slideLink && slides[index].classList.contains('no-overlay') ? 'pointer' : 'default';
        }
        if (contents[index]) contents[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        currentSlide = index;
        warmHeroSlides(index);
    };

    const nextSlide = () => {
        const next = (currentSlide + 1) % slideCount;
        showSlide(next);
    };

    const startSlider = () => {
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    };

    const resetSlider = () => {
        clearInterval(slideInterval);
        startSlider();
    };

    if (slideCount > 0 && dots.length > 0) {
        loadHeroSlideBackground(slides[0]);
        warmHeroSlides(0);

        slides.forEach(slide => {
            slide.addEventListener('click', (event) => {
                const href = slide.getAttribute('data-link');
                if (!href) return;

                event.stopPropagation();
                window.location.href = href;
            });
        });

        if (heroSection) {
            heroSection.addEventListener('click', (event) => {
                const target = event.target instanceof Element ? event.target : null;
                if (!target) return;
                if (target.closest('a, button, .slider-nav, .hero-arrow')) return;

                const activeSlide = getActiveSlide();
                const href = getActiveSlideLink();
                if (!activeSlide || !activeSlide.classList.contains('no-overlay') || !href) return;

                window.location.href = href;
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                resetSlider();
            });
        });

        const btnHeroPrev = document.getElementById('hero-prev');
        const btnHeroNext = document.getElementById('hero-next');
        
        if (btnHeroPrev && btnHeroNext) {
            btnHeroPrev.addEventListener('click', () => {
                const prev = (currentSlide - 1 + slideCount) % slideCount;
                showSlide(prev);
                resetSlider();
            });
            btnHeroNext.addEventListener('click', () => {
                nextSlide();
                resetSlider();
            });
        }

        startSlider();
    }

    // 6. Mobile Menu Toggle with Drawer & Overlay
    const mobileToggle = document.getElementById('mobile-toggle');
    const navLinks = document.querySelector('.nav-links');
    const menuOverlayMobile = document.getElementById('menu-overlay');
    
    if (mobileToggle && navLinks && menuOverlayMobile) {
        const setMobileMenuState = (open) => {
            if (window.innerWidth > 1024) {
                return;
            }
            navLinks.classList.toggle('active', open);
            menuOverlayMobile.classList.toggle('active', open);
            document.body.classList.toggle('menu-open', open);
            mobileToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            
            const icon = mobileToggle.querySelector('i');
            if (!icon) return;
            if (open) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        };

        const openMobileMenu = () => setMobileMenuState(true);
        const closeMobileMenu = () => setMobileMenuState(false);
        const toggleMobileMenu = () => setMobileMenuState(!navLinks.classList.contains('active'));

        mobileToggle.setAttribute('aria-controls', 'mobile-menu');
        mobileToggle.setAttribute('aria-expanded', 'false');
        navLinks.setAttribute('id', 'mobile-menu');

        mobileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMobileMenu();
        });

        menuOverlayMobile.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        navLinks.addEventListener('click', (e) => {
            if (window.innerWidth > 1024) return;

            const eventTarget = e.target instanceof Element ? e.target : e.target.parentElement;
            const toggle = eventTarget ? eventTarget.closest('.mobile-submenu-toggle') : null;
            if (!toggle || !navLinks.contains(toggle)) return;

            const expandableItem = toggle.closest('.nav-item.has-children, .mega-links li.has-children');
            if (!expandableItem) return;

            const hasExpandablePanel = getDirectChild(expandableItem, '.mega-menu, .sub-links');
            if (!hasExpandablePanel) return;

            e.preventDefault();
            e.stopImmediatePropagation();

            const siblingItems = expandableItem.parentElement ? expandableItem.parentElement.querySelectorAll('.is-open') : [];
            siblingItems.forEach((item) => {
                if (item !== expandableItem) {
                    item.classList.remove('is-open');
                    syncMobileSubmenuToggle(item);
                }
            });
            expandableItem.classList.toggle('is-open');
            syncMobileSubmenuToggle(expandableItem);
        }, true);

        // Close menu when clicking a link inside, BUT NOT if it's a structural link (has children)
        const drawerLinks = navLinks.querySelectorAll('a');
        drawerLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const parentLi = link.closest('li');
                // If it has children sub-links or a mega menu, don't close the whole drawer on first click
                if (parentLi && (parentLi.classList.contains('has-children') || parentLi.querySelector('.mega-menu') || parentLi.querySelector('.sub-links'))) {
                    return; 
                }
                
                if (navLinks.classList.contains('active')) {
                    closeMobileMenu();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024 && navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
                menuOverlayMobile.classList.remove('active');
                document.body.classList.remove('menu-open');
                mobileToggle.setAttribute('aria-expanded', 'false');
                const icon = mobileToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }

    // 7. Handle Top-Level Nav Item Accordions (Already handled by improved Section 4)
    // No longer need separate logic for nav-item as it's merged into section 4 above

    // Close mobile menu when clicking a link (optional, good for UX)
    const allLinks = document.querySelectorAll('.nav-link:not([href="#"])');
    allLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 1024 && navLinks) {
                const parentLi = link.closest('li');
                if (parentLi && (parentLi.classList.contains('has-children') || parentLi.querySelector('.mega-menu') || parentLi.querySelector('.sub-links'))) {
                    return;
                }

                navLinks.classList.remove('active');
                if (menuOverlayMobile) {
                    menuOverlayMobile.classList.remove('active');
                }
                document.body.classList.remove('menu-open');
                if (mobileToggle) {
                    mobileToggle.setAttribute('aria-expanded', 'false');
                    const icon = mobileToggle.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            }
        });
    });

    // 8. FAQ Toggle Logic
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        if (question) {
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all other FAQ items
                faqItems.forEach(i => i.classList.remove('active'));
                
                // If the clicked item was not active, open it
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        }
    });

    // 9. Slider Logic for Products (Infinite Loop + Dynamic Visibility)
    const sliderButtons = document.querySelectorAll('.slider-btn');
    const sliders = document.querySelectorAll('.product-grid');

    function updateSliderButtons() {
        sliders.forEach(slider => {
            const wrapper = slider.parentElement;
            const prevBtn = wrapper.querySelector('.prev-btn');
            const nextBtn = wrapper.querySelector('.next-btn');
            if (prevBtn && nextBtn) {
                // Check if scrollable
                if (slider.scrollWidth > slider.clientWidth + 5) {
                    prevBtn.style.display = 'flex';
                    nextBtn.style.display = 'flex';
                } else {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'none';
                }
            }
        });
    }

    sliderButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const slider = document.getElementById(targetId);
            if (slider) {
                const firstCard = slider.querySelector('.product-card');
                const cardWidth = firstCard ? firstCard.offsetWidth : 400;
                const gap = 32; // 2rem = 32px
                const scrollAmount = cardWidth + gap;
                
                const maxScroll = slider.scrollWidth - slider.clientWidth;
                
                if (btn.classList.contains('next-btn')) {
                    // If near the end, loop back to start
                    if (slider.scrollLeft >= maxScroll - 50) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                    }
                } else {
                    // If near the start, loop to end
                    if (slider.scrollLeft <= 50) {
                        slider.scrollTo({ left: maxScroll, behavior: 'smooth' });
                    } else {
                        slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                    }
                }
            }
        });
    });

    // Run on load and resize
    let sliderButtonFrame = 0;
    const queueSliderButtonUpdate = () => {
        if (sliderButtonFrame) {
            cancelAnimationFrame(sliderButtonFrame);
        }

        sliderButtonFrame = requestAnimationFrame(() => {
            sliderButtonFrame = 0;
            updateSliderButtons();
        });
    };

    queueSliderButtonUpdate();
    window.addEventListener('resize', queueSliderButtonUpdate);

    // 10. Banner Switcher Logic
    const bannerData = Array.isArray(window.__homeBannerData) ? window.__homeBannerData : [];

    const bannerDots = document.querySelectorAll('.nav-dot');
    const bannerImg = document.getElementById('banner-img');
    const bannerImgLink = document.getElementById('banner-img-link');
    const bannerLink = document.getElementById('banner-link');
    const bannerWatermark = document.getElementById('banner-watermark');
    const bannerProductCode = document.getElementById('banner-product-code');
    const bannerSpecsRow = document.getElementById('banner-specs-row');
    const btnPrev = document.getElementById('banner-prev');
    const btnNext = document.getElementById('banner-next');
    const bannerRoot = document.getElementById('banner-switcher-root');

    let currentBannerIndex = 0;
    let bannerSpecRenderTimer = 0;

    const createBannerSpecItem = (label, value, extraClass = '') => {
        const item = document.createElement('div');
        item.className = `banner-spec-item${extraClass ? ` ${extraClass}` : ''}`;

        const labelNode = document.createElement('span');
        labelNode.className = 'label';
        labelNode.textContent = label || '-';

        const valueNode = document.createElement('span');
        valueNode.className = 'value';
        valueNode.textContent = value || '-';

        item.appendChild(labelNode);
        item.appendChild(valueNode);

        return item;
    };

    const renderBannerSpecs = (specs, price) => {
        if (!bannerSpecsRow) return;

        const normalizedSpecs = Array.isArray(specs)
            ? specs.filter(spec => (spec && (spec.label || spec.value)))
            : [];

        bannerSpecsRow.classList.add('is-updating');

        clearTimeout(bannerSpecRenderTimer);
        bannerSpecRenderTimer = setTimeout(() => {
            bannerSpecsRow.replaceChildren();
            bannerSpecsRow.style.setProperty('--banner-spec-columns', Math.max(normalizedSpecs.length + 1, 1));

            normalizedSpecs.forEach(spec => {
                bannerSpecsRow.appendChild(createBannerSpecItem(spec.label, spec.value));
            });

            bannerSpecsRow.appendChild(createBannerSpecItem('Giá tham khảo', price || '-', 'banner-spec-item-price'));
            bannerSpecsRow.classList.remove('is-updating');
        }, 300);
    };

    const updateBanner = (index) => {
        const data = bannerData[index];
        if (!data) return;

        const safeImage = data.image || 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
        const safeLink = data.link || '#';
        const safeSpecs = Array.isArray(data.specs) ? data.specs : [];
        const safePrice = data.price || '-';
        currentBannerIndex = index;

        // Update dots
        bannerDots.forEach((d, i) => {
            if (i === index) d.classList.add('active');
            else d.classList.remove('active');
        });

        // Update Image & Watermark with subtle fade
        if (bannerImg) {
            bannerImg.style.opacity = '0.3';
            if (bannerWatermark) bannerWatermark.style.opacity = '0';
            
            setTimeout(() => {
                bannerImg.src = safeImage;
                if (bannerImgLink) bannerImgLink.href = safeLink;
                if (bannerLink) bannerLink.href = safeLink;
                if (bannerRoot) bannerRoot.setAttribute('data-link', safeLink);
                if (bannerWatermark) {
                    bannerWatermark.textContent = data.code || data.name || '';
                    bannerWatermark.style.opacity = '1';
                }
                if (bannerProductCode) bannerProductCode.textContent = `Mã SP: ${data.code || data.name || '-'}`;
                bannerImg.style.opacity = '1';
            }, 300);
        }

        renderBannerSpecs(safeSpecs, safePrice);
    };

    if (bannerDots.length > 0 && bannerData.length > 0) {
        bannerDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const index = parseInt(dot.getAttribute('data-index'));
                updateBanner(index);
            });
        });
    }

    if (btnPrev && btnNext && bannerData.length > 0) {
        btnPrev.addEventListener('click', () => {
            let index = (currentBannerIndex - 1 + bannerData.length) % bannerData.length;
            updateBanner(index);
        });
        btnNext.addEventListener('click', () => {
            let index = (currentBannerIndex + 1) % bannerData.length;
            updateBanner(index);
        });
    }

    if (bannerRoot) {
        bannerRoot.addEventListener('click', (e) => {
            if (e.target.closest('a, button')) return;
            const href = bannerRoot.getAttribute('data-link');
            if (href) window.location.href = href;
        });
    }

    document.addEventListener('click', (event) => {
        const card = event.target.closest('.clickable-card[data-card-link]');
        if (!card) return;
        if (event.target.closest('a, button, input, select, textarea, label')) return;

        const href = card.getAttribute('data-card-link');
        if (!href) return;
        window.location.href = href;
    });
});
