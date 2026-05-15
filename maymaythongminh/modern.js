/**
 * Modern Interactivity for Máy May Thông Minh
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Reveal Animations on Scroll
    const revealCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
                observer.unobserve(entry.target); // Only animate once
            }
        });
    };

    const revealObserver = new IntersectionObserver(revealCallback, {
        threshold: 0.1
    });

    document.querySelectorAll('.product-card, .section-header').forEach(el => {
        el.style.opacity = '0'; // Initial state for reveal
        revealObserver.observe(el);
    });

    // 2. Sticky Header refinement
    const header = document.querySelector('.header-main');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        if (currentScroll <= 0) {
            header.classList.remove('scroll-up');
            return;
        }

        if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-up');
            header.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-down');
            header.classList.add('scroll-up');
        }
        lastScroll = currentScroll;
    });

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

    // 4. Mega Menu Accordion Logic (Shared and Responsive)
    const setupAccordion = (selector, childSelector, forceAllSizes = false) => {
        const parents = document.querySelectorAll(selector);
        parents.forEach(parent => {
            const children = parent.querySelector(childSelector);
            if (children) {
                const link = parent.querySelector('a');
                link.addEventListener('click', (e) => {
                    const isMobile = window.innerWidth <= 1024; // Align with CSS media queries
                    if (isMobile || forceAllSizes) {
                        // If closed, prevent navigation and open it
                        if (!parent.classList.contains('is-open')) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Close other siblings for a clean accordion look
                            const siblings = parent.parentElement.querySelectorAll('.is-open');
                            siblings.forEach(sibling => {
                                if (sibling !== parent) sibling.classList.remove('is-open');
                            });

                            parent.classList.add('is-open');
                        } else {
                            // If it's already open on mobile, maybe close it?
                            // For desktop sub-products, we might want to navigate on second click.
                            if (isMobile) {
                                e.preventDefault();
                                e.stopPropagation();
                                parent.classList.remove('is-open');
                            }
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

    // 5. Hero Slider Logic
    const slides = document.querySelectorAll('.hero-slide');
    const contents = document.querySelectorAll('.hero-slide-content');
    const dots = document.querySelectorAll('.dot');
    let currentSlide = 0;
    const slideCount = slides.length;
    let slideInterval;

    const showSlide = (index) => {
        slides.forEach(s => s.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        slides[index].classList.add('active');
        contents[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
    };

    const nextSlide = () => {
        let next = (currentSlide + 1) % slideCount;
        showSlide(next);
    };

    const startSlider = () => {
        slideInterval = setInterval(nextSlide, 5000);
    };

    const resetSlider = () => {
        clearInterval(slideInterval);
        startSlider();
    };

    if (slideCount > 0) {
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
                let prev = (currentSlide - 1 + slideCount) % slideCount;
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
    const menuOverlay = document.getElementById('menu-overlay');
    
    if (mobileToggle && navLinks && menuOverlay) {
        const toggleMenu = () => {
            const isActive = navLinks.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            document.body.classList.toggle('menu-open');
            
            const icon = mobileToggle.querySelector('i');
            if (isActive) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        };

        mobileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu();
        });

        menuOverlay.addEventListener('click', toggleMenu);

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
                    toggleMenu();
                }
            });
        });
    }

    // 7. Handle Top-Level Nav Item Accordions (Already handled by improved Section 4)
    // No longer need separate logic for nav-item as it's merged into section 4 above

    // Close mobile menu when clicking a link (optional, good for UX)
    const allLinks = document.querySelectorAll('.nav-link:not([href="#"])');
    allLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                navLinks.classList.remove('active');
                if (mobileToggle) {
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
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
    updateSliderButtons();
    window.addEventListener('resize', updateSliderButtons);

    // 10. Banner Switcher Logic
    const bannerData = [
        {
            name: "X-1209D",
            fullName: "Máy Lấy Dấu X-1209D",
            image: "images/anh1.jpg",
            link: "index.php?act=product_detail&idcatpd=60&idproduct=300",
            specs: {
                size: "1200x900mm",
                speed: "1500mm/s",
                precision: "0.05mm",
                price: "Liên hệ"
            }
        },
        {
            name: "Smart S1",
            fullName: "Máy Lập Trình Smart S1",
            image: "images/anh2.jpg",
            link: "index.php?act=product_detail&idcatpd=60&idproduct=301",
            specs: {
                size: "300x200mm",
                speed: "2500 vòng/phút",
                precision: "0.1mm",
                price: "25.000.000đ"
            }
        },
        {
            name: "Laser Auto",
            fullName: "Máy Cắt Laser Tự Động",
            image: "images/anh3.jpg",
            link: "index.php?act=product_detail&idcatpd=60&idproduct=302",
            specs: {
                size: "1500x1000mm",
                speed: "5000mm/s",
                precision: "0.01mm",
                price: "85.000.000đ"
            }
        }
    ];

    const bannerDots = document.querySelectorAll('.nav-dot');
    const bannerImg = document.getElementById('banner-img');
    const bannerImgLink = document.getElementById('banner-img-link');
    const bannerLink = document.getElementById('banner-link');
    const bannerWatermark = document.getElementById('banner-watermark');
    const specValues = document.querySelectorAll('.banner-spec-item .value');
    const btnPrev = document.getElementById('banner-prev');
    const btnNext = document.getElementById('banner-next');

    let currentBannerIndex = 0;

    const updateBanner = (index) => {
        const data = bannerData[index];
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
                bannerImg.src = data.image;
                if (bannerImgLink) bannerImgLink.href = data.link;
                if (bannerLink) bannerLink.href = data.link;
                if (bannerWatermark) {
                    bannerWatermark.textContent = data.name;
                    bannerWatermark.style.opacity = '1';
                }
                bannerImg.style.opacity = '1';
            }, 300);
        }

        // Update Specs: only animate if value actually changes
        specValues.forEach(spec => {
            const type = spec.getAttribute('data-spec');
            const newValue = data.specs[type];
            
            if (spec.textContent !== newValue) {
                spec.classList.add('changing');
                setTimeout(() => {
                    spec.textContent = newValue;
                    spec.classList.remove('changing');
                }, 300);
            }
        });
    };

    if (bannerDots.length > 0) {
        bannerDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const index = parseInt(dot.getAttribute('data-index'));
                updateBanner(index);
            });
        });
    }

    if (btnPrev && btnNext) {
        btnPrev.addEventListener('click', () => {
            let index = (currentBannerIndex - 1 + bannerData.length) % bannerData.length;
            updateBanner(index);
        });
        btnNext.addEventListener('click', () => {
            let index = (currentBannerIndex + 1) % bannerData.length;
            updateBanner(index);
        });
    }

    console.log('Modern JS initialized for Máy May Thông Minh');
});
