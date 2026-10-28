document.addEventListener('DOMContentLoaded', function () {
    let filtersElement = document.getElementById('filters-container');
    let filters = JSON.parse(filtersElement.getAttribute('data-filters'));
    let field = document.querySelector('.items');
    let products = Array.from(field.children);
    let checkedItems = [];
    let select = document.getElementById('select');
    let inputLeft = document.querySelector('.input-left');
    let inputRight = document.querySelector('.input-right');
    let minPrice = parseFloat(inputLeft.min);
    let maxPrice = parseFloat(inputRight.max);

    inputLeft.addEventListener('input', updateSlider);
    inputRight.addEventListener('input', updateSlider);

    function updateSliderUI(valueLeft, valueRight) {
        document.querySelector('.slider__fill').style.width = `${((valueRight - valueLeft) / (maxPrice - minPrice)) * 100}%`;
        document.querySelector('.slider__fill').style.left = `${((valueLeft - minPrice) / (maxPrice - minPrice)) * 100}%`;
        document.querySelector('.slider__btn--left').style.left = `${((valueLeft - minPrice) / (maxPrice - minPrice)) * 100}%`;
        document.querySelector('.slider__btn--right').style.left = `${((valueRight - minPrice) / (maxPrice - minPrice)) * 100 -10}%`;

        // Update price range text
        document.querySelector('.price-range').textContent = `${valueLeft} $ - ${valueRight} $`;
    }

    function updateSlider() {
        let valueLeft = parseFloat(inputLeft.value);
        let valueRight = parseFloat(inputRight.value);

        // Prevent overlap and ensure sliders move smoothly
        if (valueLeft >= valueRight - 10) {
            valueLeft = valueRight - 10;
            inputLeft.value = valueLeft;
        }

        if (valueRight <= valueLeft + 10) {
            valueRight = valueLeft + 10;
            inputRight.value = valueRight;
        }

        updateSliderUI(valueLeft, valueRight);

        // Automatically apply filter as the sliders are moved
        filterAndSortProducts();
    }

    inputLeft.addEventListener('input', updateSlider);
    inputRight.addEventListener('input', updateSlider);

    document.querySelectorAll('input[type=checkbox]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            let itemValue = this.value;

            if (this.checked) {
                checkedItems.push(itemValue);
                addChip(itemValue);
            } else {
                checkedItems = checkedItems.filter(item => item !== itemValue);
                removeChip(itemValue);
            }

            filterAndSortProducts();
        });
    });

    function filterProducts() {
        let valueLeft = parseFloat(document.querySelector('.input-left').value);
        let valueRight = parseFloat(document.querySelector('.input-right').value);

        products.forEach(product => {
            let productCategory = product.getAttribute('data-category');
            let productPrice = parseFloat(product.getAttribute('data-price'));

            if ((checkedItems.length === 0 || checkedItems.includes(productCategory)) &&
                productPrice >= valueLeft && productPrice <= valueRight) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Function to sort products by price
    function sortProducts() {
        let visibleProducts = products.filter(product => product.style.display !== 'none');

        visibleProducts.forEach(product => {
            let price = parseFloat(product.getAttribute('data-price'));
            product.setAttribute('data-price', price);
        });

        if (select.value === 'LowToHigh') {
            sortElems(visibleProducts, true);
        } else if (select.value === 'HighToLow') {
            sortElems(visibleProducts, false);
        } else {
            while (field.firstChild) {
                field.removeChild(field.firstChild);
            }
            products.forEach(product => field.appendChild(product));
        }
    }

    function sortElems(items, asc) {
        let dm = asc ? 1 : -1;

        items.sort((a, b) => {
            const ax = parseFloat(a.getAttribute('data-price'));
            const bx = parseFloat(b.getAttribute('data-price'));
            return (ax - bx) * dm;
        });

        while (field.firstChild) {
            field.removeChild(field.firstChild);
        }

        items.forEach(product => field.appendChild(product));
    }

    function filterAndSortProducts() {
        filterProducts();
        sortProducts();
    }

    // Fonction pour ajouter un élément visuel "chip"
    function addChip(value) {
        let chipContainer = document.getElementById('chip-container');
        let chipItem = document.createElement('div');
        chipItem.classList.add('chip__item');
        chipItem.setAttribute('data-value', value);
        chipItem.innerHTML = `${value} <span class="material-icons">close</span>`;

        chipContainer.appendChild(chipItem);

        chipItem.querySelector('span').addEventListener('click', function () {
            chipItem.remove();
            checkedItems = checkedItems.filter(item => item !== value);
            document.querySelector(`input[value="${value}"]`).checked = false;
            filterAndSortProducts();
        });
    }

    // Fonction pour retirer un "chip"
    function removeChip(value) {
        let chipItem = document.querySelector(`.chip__item[data-value="${value}"]`);
        if (chipItem) {
            chipItem.remove();
        }
    }

    // Toggle the filter's visibility
    document.querySelectorAll('.accordion__title').forEach(function (title) {
        title.addEventListener('click', function () {
            let filterId = parseInt(this.getAttribute('data-filter-id'));
            let filter = filters.find(f => f.id === filterId);

            filter.show = !filter.show;
            let items = this.nextElementSibling;
            if (filter.show) {
                items.style.display = 'block';
                this.querySelector('span').classList.add('rotate');
            } else {
                items.style.display = 'none';
                this.querySelector('span').classList.remove('rotate');
            }
        });
    });

    // Show more/less items in a filter
    document.querySelectorAll('.accordion__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let filterIndex = parseInt(btn.closest('.accordion__content').querySelector('.accordion__title').getAttribute('data-filter-id'));
            let filter = filters.find(f => f.id === filterIndex);
            let items = btn.closest('ul').querySelectorAll('li');

            if (btn.textContent.includes('Show more')) {
                // Show the hidden items
                items.forEach((item, index) => {
                    if (index >= filter.defaultLimit) {
                        item.style.display = 'flex';
                    }
                });
                btn.textContent = '- Show less';
            } else {
                // Hide the extra items
                items.forEach((item, index) => {
                    if (index >= filter.defaultLimit) {
                        item.style.display = 'none';
                    }
                });
                btn.textContent = '+ Show more';
            }
        });
    });


    // Handle range input changes
    document.querySelectorAll('input[type=range]').forEach(function (input) {
        input.addEventListener('input', function () {
            let valueLeft = parseInt(document.querySelector('.input-left').value);
            let valueRight = parseInt(document.querySelector('.input-right').value);

            // Limite maximale définie dans le HTML (ici 100)
            const maxValue = parseInt(document.querySelector('.input-right').max);
            const minValue = parseInt(document.querySelector('.input-left').min);

            // Mettre à jour pour la poignée gauche
            if (this.classList.contains('input-left')) {
                if (valueLeft >= valueRight) {
                    valueLeft = valueRight - 20;
                    document.querySelector('.input-left').value = valueLeft;
                }
            }

            // Mettre à jour pour la poignée droite
            if (this.classList.contains('input-right')) {
                if (valueRight >= maxValue) {
                    valueRight = maxValue;
                    inputRight.value = maxValue;
                }
                if (valueRight <= valueLeft) {
                    valueRight = valueLeft + 20;
                    document.querySelector('.input-right').value = valueRight;
                }
            }

            updateSliderUI(valueLeft, valueRight);
        });
    });


    // Remove individual filter from chips
    document.querySelectorAll('.chip__item').forEach(function (chip) {
        chip.addEventListener('click', function () {
            let index = this.getAttribute('data-checked-index');
            checkedItems.splice(index, 1);
            this.remove();
        });
    });

    // Event listener for sort select dropdown
    inputLeft.addEventListener('input', filterAndSortProducts);
    inputRight.addEventListener('input', filterAndSortProducts);

    select.addEventListener('change', filterAndSortProducts);

    // Clear all selected filters
    document.getElementById('clearAll').addEventListener('click', function () {
        checkedItems = [];
        document.querySelectorAll('input[type=checkbox]').forEach(checkbox => checkbox.checked = false);
        document.getElementById('chip-container').innerHTML = '';
        filterAndSortProducts();
    });

    // Initialize with default sorting
    filterAndSortProducts();
});
