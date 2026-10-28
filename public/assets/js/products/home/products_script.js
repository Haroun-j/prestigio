document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.container_ .card');

    cards.forEach((card) => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const xAxis = ((e.clientX - rect.left) - (rect.width / 2)) / 25;
            const yAxis = ((e.clientY - rect.top) - (rect.height / 2)) / 25;
            card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        card.addEventListener('mouseenter', () => {
            card.style.transition = 'none';
            popEffect(card, true);
        });

        card.addEventListener('mouseleave', () => {
            card.style.transition = 'all 0.5s ease';
            card.style.transform = 'rotateY(0deg) rotateX(0deg)';
            popEffect(card, false);
        });
    });

    function popEffect(card, popOut = true) {
        const transformValue = popOut ? 'translateZ(150px)' : 'translateZ(0px)';

        const elementsToTransform = [
            card.querySelector('.title'),
            card.querySelector('.sneaker img'),
            card.querySelector('.info h3'),
            card.querySelector('.sizes'),
            card.querySelector('.colors'),
            card.querySelector('.purchase button')
        ];

        elementsToTransform.forEach(element => {
            if (element) {
                if (element.matches('.sneaker img') && popOut) {
                    element.style.transform = 'translateZ(200px) rotateZ(-45deg)';
                } else {
                    element.style.transform = transformValue;
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('productSlider');
    const slides = slider.querySelectorAll('.shoe-box');
    let slideIndex = 1;
    let startX;
    let autoSlideTimeout;

    const updateSliderPosition = () => {
        const slideWidth = slider.clientWidth / (window.innerWidth < 599 ? 1 : 3);
        slider.style.transition = 'transform 0.5s cubic-bezier(0.455, 0.03, 0.515, 0.955)';
        slider.style.transform = `translateX(-${(slideIndex - 1) * slideWidth}px)`;
        highlightActiveSlide();
    };

    const highlightActiveSlide = () => {
        slides.forEach((slide, index) => {
            slide.classList.remove('active');
            slide.style.opacity = '0.5';
            if (index === slideIndex) {
                slide.classList.add('active');
                slide.style.opacity = '1';
            }
        });
    };

    const autoSlide = () => {
        slideIndex = slideIndex >= slides.length - 1 ? 1 : slideIndex + 1;
        updateSliderPosition();
    };

    const resetAutoSlideTimer = () => {
        clearInterval(autoSlideTimeout);
        autoSlideTimeout = setInterval(autoSlide, 3000);
    };
    if (window.innerWidth < 599) {
        const controlsDiv = document.createElement('div');
        controlsDiv.classList.add('slider-controls');

        const prevArrow = document.createElement('button');
        prevArrow.id = 'slidePrev';
        prevArrow.textContent = '<';
        controlsDiv.appendChild(prevArrow);

        const nextArrow = document.createElement('button');
        nextArrow.id = 'slideNext';
        nextArrow.textContent = '>';
        controlsDiv.appendChild(nextArrow);

        document.querySelector('.slider-container').appendChild(controlsDiv);

        document.getElementById('slideNext').addEventListener('click', () => {
            slideIndex++;
            if (slideIndex > slides.length) slideIndex = 1;
            updateSliderPosition();
        });

        document.getElementById('slidePrev').addEventListener('click', () => {
            slideIndex--;
            if (slideIndex < 1) slideIndex = slides.length;
            updateSliderPosition();
        });

        slider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });

        slider.addEventListener('touchmove', (e) => {
            const moveX = e.touches[0].clientX;
            const diff = startX - moveX;

            if (diff > 50) {
                slideIndex++;
                if (slideIndex > slides.length) slideIndex = 1;
                updateSliderPosition();
            } else if (diff < -50) {
                slideIndex--;
                if (slideIndex < 1) slideIndex = slides.length;
                updateSliderPosition();
            }
        });
    } else{
    // Event listeners
    slides.forEach((slide, index) => {
        slide.addEventListener('click', () => {
            slideIndex = index;
            updateSliderPosition();
            resetAutoSlideTimer();
        });
    });
}
    document.getElementById('slideNext').addEventListener('click', () => {
        slideIndex++;
        if (slideIndex >= slides.length) slideIndex = 1; // Adjusting to loop correctly
        updateSliderPosition();
        resetAutoSlideTimer();
    });

    document.getElementById('slidePrev').addEventListener('click', () => {
        slideIndex--;
        if (slideIndex < 1) slideIndex = slides.length - 1;
        updateSliderPosition();
        resetAutoSlideTimer();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') {
            slideIndex++;
            if (slideIndex >= slides.length) slideIndex = 1;
        } else if (e.key === 'ArrowLeft') {
            slideIndex--;
            if (slideIndex < 1) slideIndex = slides.length - 1;
        } else {
            return;
        }
        updateSliderPosition();
        resetAutoSlideTimer();
    });

    // Mouse events
    slider.addEventListener('mouseenter', () => clearInterval(autoSlideTimeout));
    slider.addEventListener('mouseleave', resetAutoSlideTimer);

    // Initialize
    resetAutoSlideTimer();
    updateSliderPosition();
});
