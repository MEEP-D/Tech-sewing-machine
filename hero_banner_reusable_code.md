# Reusable Premium Hero Slider Code

This document contains the complete code for the full-screen hero slider implemented in the "Máy May Thông Minh" project. You can adapt this for any project requiring a high-end, immersive banner.

## 1. HTML Structure

```html
<!-- Hero Section -->
<section class="hero">
    <!-- Image Slider -->
    <div class="hero-slider">
        <!-- Add more slides as needed. Only one should have 'active' class initially -->
        <div class="hero-slide active" style="background-image: url('path/to/your/image1.jpg');"></div>
        <div class="hero-slide" style="background-image: url('path/to/your/image2.jpg');"></div>
        <div class="hero-slide" style="background-image: url('path/to/your/image3.jpg');"></div>
    </div>

    <div class="container">
        <div class="hero-content">
            <!-- Slide 1 Content -->
            <div class="hero-slide-content active">
                <h1 class="hero-title">Your Main Headline</h1>
                <p class="hero-subtitle">Your secondary descriptive text goes here.</p>
                <div class="hero-buttons">
                    <a href="#" class="btn btn-primary">Primary Action</a>
                    <a href="#" class="btn btn-secondary">Secondary Action</a>
                </div>
            </div>

            <!-- Slide 2 Content ... and so on -->
        </div>
    </div>

    <!-- Navigation Dots -->
    <div class="slider-nav">
        <div class="dot active"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <!-- Navigation Arrows (Optional) -->
    <button class="hero-arrow prev-hero" id="hero-prev"><i class="fas fa-chevron-left"></i></button>
    <button class="hero-arrow next-hero" id="hero-next"><i class="fas fa-chevron-right"></i></button>
</section>
```

## 2. CSS Styling (Vanilla CSS)

```css
/* Hero Container */
.hero {
    position: relative;
    height: 100vh; /* Full viewport height */
    background-color: #1e3a8a; /* Fallback color */
    display: flex;
    align-items: center;
    overflow: hidden;
}

/* Slider Layer */
.hero-slider {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

/* Individual Slide */
.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    background-size: cover;
    background-position: center;
}

.hero-slide.active {
    opacity: 1;
}

/* Bottom-up Subtle Gradient Overlay */
.hero-slide::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.1) 30%, transparent 100%);
}

/* Content Layer */
.hero-content {
    position: relative;
    z-index: 2;
    color: white;
    max-width: 800px;
}

.hero-slide-content {
    display: none;
}

.hero-slide-content.active {
    display: block;
    animation: fadeInUp 0.8s ease forwards;
}

/* Typography */
.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

/* Dots Navigation */
.slider-nav {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: 1rem;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    background: #1d4ed8; /* Your primary color */
    width: 30px;
    border-radius: 6px;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

## 3. JavaScript Logic

```javascript
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.hero-slide');
    const contents = document.querySelectorAll('.hero-slide-content');
    const dots = document.querySelectorAll('.dot');
    let currentSlide = 0;
    const slideCount = slides.length;
    let slideInterval;

    const showSlide = (index) => {
        // Remove active states
        slides.forEach(s => s.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        // Set new active state
        slides[index].classList.add('active');
        if(contents[index]) contents[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
    };

    const nextSlide = () => {
        let next = (currentSlide + 1) % slideCount;
        showSlide(next);
    };

    const startSlider = () => {
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    };

    const resetSlider = () => {
        clearInterval(slideInterval);
        startSlider();
    };

    // Initialize
    if (slideCount > 0) {
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                resetSlider();
            });
        });
        startSlider();
    }
});
```
