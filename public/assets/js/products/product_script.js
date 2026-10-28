window.addEventListener("load", event => {

    function productHeading() {
        const productPrice = parseFloat(document.getElementById('product-price').dataset.price);
        const imagesData = document.getElementById('images-data').textContent;
        console.log(imagesData);
        const product = {
            value: productPrice,
            images: JSON.parse(imagesData)
        }

        function initializeSwipers() {
            // Check if Swiper instances already exist and destroy them
            if (window.galleryThumbs) {
                window.galleryThumbs.destroy(true, true);
            }
            if (window.galleryMain) {
                window.galleryMain.destroy(true, true);
            }

            // Create new Swiper instances
            window.galleryThumbs = new Swiper('.galleryThumbs', {
                spaceBetween: 0,
                slidesPerView: 'auto',
                loop: false,
                allowTouchMove: false,
                allowSlidePrev: false,
                allowSlideNext: false,
            });

            window.galleryMain = new Swiper('.galleryMain', {
                spaceBetween: 300,
                speed: 500,
                loop: true,
                loopedSlides: 5,
                effect: "coverflow",
                coverflowEffect: {
                    rotate: 50,
                    slideShadows: false,
                    depth: 200,
                    stretch: 50,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                thumbs: {
                    swiper: window.galleryThumbs,
                },
            });
        }

        // const btnAdd = document.querySelector('.btn.add'),
        //     btnContainer = document.querySelector('.btnContainer'),
        const wrapper = document.querySelector('.wrapper'),
            itemNumber = document.querySelector('.itemNumber'),
            shoppingQuantity = document.querySelector('.shoppingQuantity'),
            inputQuantity = document.querySelector('.inputQuantity'),
            plus = document.querySelector('.plus'),
            minus = document.querySelector('.minus'),
            arrowDrop = document.querySelector('.arrowDrop'),
            dropdown = document.querySelector('.dropdown'),
            nav = document.querySelector('nav'),
            error = document.querySelector('.error'),
            shoppingIcon = document.querySelector('.shoppingIcon'),
            shoppingMenu = document.querySelector('.shoppingMenu'),
            emptyCart = document.querySelector('.emptyCart');

        let priceFinal = document.querySelector('.priceFinal'),
            priceOriginal = document.querySelector('.priceOriginal'),
            discount = null,
            sizeNumber = document.querySelector('.sizeNumber'),
            dropItem = document.querySelectorAll('.dropItem'),
            maxQuantity = 5,
            newMaxQuantity = maxQuantity;

        ////////////////
        // Events
        ////////////////

        document.getElementById('add-to-cart').addEventListener('click', function (event) {
            event.preventDefault();
            const size = document.querySelector('.sizeNumber').innerText;
            const quantity = document.querySelector('.inputQuantity').value;
            const urlTemplate = this.getAttribute('data-url');
            window.location.href = urlTemplate.replace('SIZE', size).replace('QUANTITY', quantity);
        });


        plus.addEventListener("click", plusQuantity);
        minus.addEventListener("click", minusQuantity);
        arrowDrop.addEventListener("click", openDrop);
        // shoppingIcon.addEventListener("click", openShoppingCart);

        // emptyCart.addEventListener("click", cleanCart);

        dropItem.forEach(function (el) {
            el.addEventListener("click", getSize);
        })

        // window.addEventListener("resize", resize);


        ////////////////
        // Functions
        ////////////////

        // Fixed Nav

        window.onscroll = function () {
            if (window.pageYOffset >= 60) {
                nav.classList.add("fixed");
            } else {
                nav.classList.remove("fixed");
            }
        };

        // Change button position on mobile

        // function resize() {
        //     //Button
        //     if (window.innerHeight > wrapper.offsetHeight) {
        //         btnContainer.classList.remove('fixedBtn');
        //     } else {
        //         btnContainer.classList.add('fixedBtn');
        //     }
        //     parallax();
        // }

        // Parallax

        function parallax() {
            if (window.innerWidth > 800) {
                const scene = document.querySelectorAll('.scene');
                scene.forEach(pic => {
                    const parallax = new Parallax(pic);
                })
            }
        }

        // Calculate the Discount

        function getDisccount() {
            priceOriginal.innerText = product.value + (product.value * 0.2) + "€";
            discount = product.value;
            priceFinal.innerText = discount + "€";
        }

        // Calculate the the Prices with discounts

        function getPrice() {

            priceFinal.innerText = discount * inputQuantity.value + "€";
            priceOriginal.innerText = product.value * inputQuantity.value + "€";

            setTimeout(() => {
                priceFinal.classList.remove('anime');
            }, 400);
        }

        // Update the prices with the quantity counter

        function plusQuantity() {
            if (inputQuantity.value < maxQuantity) {
                inputQuantity.value === inputQuantity.value++;
                priceFinal.classList.add('anime');
            }
            getPrice();
        }

        function minusQuantity() {
            if (inputQuantity.value > 1) {
                inputQuantity.value === inputQuantity.value--;
                priceFinal.classList.add('anime');
            }
            getPrice();
        }

        // Add items to shopping cart

        // function addItem() {
        //
        //     let cenas = parseInt(itemNumber.innerText) + parseInt(inputQuantity.value);
        //
        //     if (cenas <= newMaxQuantity) {
        //         itemNumber.style.display = "flex";
        //         itemNumber.innerText = cenas;
        //         shoppingQuantity.innerText = "x" + cenas;
        //         itemNumber.classList.add("addItem");
        //         error.style.display = "none";
        //     } else {
        //         error.style.display = "flex";
        //     }
        //
        //     setTimeout(() => {
        //         itemNumber.classList.remove("addItem");
        //     }, 700);
        // }

        // Open Drop

        function openDrop() {
            if (dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
            } else {
                dropdown.classList.add('open');
            }
        }

        //get Drop Size Number Value

        function getSize(e) {
            sizeNumber.innerText = e.currentTarget.innerText;
            openDrop();
        }

        // Open Shopphing cart

        // function openShoppingCart() {
        //     if (itemNumber.innerText !== "0") {
        //         if (shoppingMenu.classList.contains('openShopping')) {
        //             shoppingMenu.classList.remove('openShopping');
        //         } else {
        //             shoppingMenu.classList.add('openShopping');
        //         }
        //     }
        // }

        //Clean Shopping Cart

        function cleanCart() {
            shoppingMenu.classList.remove('openShopping');
            itemNumber.style.display = "none";
            itemNumber.classList.remove('addItem');
            itemNumber.innerText = "0";
        }

        // Populate the images for Swiper
        const galleryMainWrapper = document.querySelector('.galleryMain .swiper-wrapper');
        const galleryThumbsWrapper = document.querySelector('.galleryThumbs .swiper-wrapper');
        galleryMainWrapper.innerHTML = '';
        galleryThumbsWrapper.innerHTML = '';

        product.images.forEach(function (imgUrl) {
            const mainSlide = document.createElement('div');
            mainSlide.classList.add('swiper-slide');
            mainSlide.innerHTML = `
        <div class="scene" data-hover-only="false"> 
            <img src="${imgUrl}" data-depth="0.5" alt="">
            <img src="${imgUrl}" data-depth="1" class="shadow" alt="">
        </div>
    `;

            const thumbSlide = document.createElement('div');
            thumbSlide.classList.add('swiper-slide');
            thumbSlide.innerHTML = `<img src="${imgUrl}" alt="">`;

            galleryMainWrapper.appendChild(mainSlide);
            galleryThumbsWrapper.appendChild(thumbSlide);
        });

        // product.images.forEach(function (imgUrl) {
        //
        //     let template = `
        //         <div class="swiper-slide">
        //             <div class="scene" data-hover-only="false">
        //                 <img src="${imgUrl}" data-depth="0.5" alt="">
        //                 <img src="${imgUrl}" data-depth="1" class="shadow" alt="">
        //             </div>
        //         </div>`;
        //
        //     let template2 = `
        //         <div class="swiper-slide">
        //             <img src="${imgUrl}" alt="">
        //         </div>`;
        //
        //     document.querySelector('.galleryMain .swiper-wrapper').insertAdjacentHTML('beforeend', template);
        //     document.querySelector('.galleryThumbs .swiper-wrapper').insertAdjacentHTML('beforeend', template2);
        // });


        // Make the slider function

        const galleryThumbs = new Swiper('.galleryThumbs', {
            spaceBetween: 0,
            slidesPerView: 'auto',
            loop: false,
            allowTouchMove: false,
            allowSlidePrev: false,
            allowSlideNext: false,

        });

        const galleryMain = new Swiper('.galleryMain', {
            spaceBetween: 300,
            speed: 500,
            loop: true,
            loopedSlides: 5, //looped slides should be the same
            effect: "coverflow",
            coverflowEffect: {
                rotate: 50,
                slideShadows: false,
                depth: 200,
                stretch: 50,

            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,

            },
            thumbs: {
                swiper: galleryThumbs,
            },
        });
        // Call functions
        getDisccount();
        parallax();
        initializeSwipers();
    }

    productHeading();
});
