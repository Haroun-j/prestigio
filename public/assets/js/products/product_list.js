(function() {
	let field = document.querySelector('.items');
	let li = Array.from(field.children);

	function FilterProduct() {
		let indicator = document.querySelector('.indicator').children;

		this.run = function() {
			for (let i = 0; i < indicator.length; i++) {
				indicator[i].onclick = function() {
					for (let x = 0; x < indicator.length; x++) {
						indicator[x].classList.remove('active');
					}
					this.classList.add('active');
					const displayItems = this.getAttribute('data-filter');

					for (let z = 0; z < li.length; z++) {
						li[z].style.transform = "scale(0)";
						setTimeout(() => {
							li[z].style.display = "none";
						}, 500);

						if ((li[z].getAttribute('data-category') === displayItems) || displayItems === "all") {
							li[z].style.transform = "scale(1)";
							setTimeout(() => {
								li[z].style.display = "block";
							}, 500);
						}
					}
				};
			}
		}
	}

	function SortProduct() {
		let select = document.getElementById('select');
		let ar = [];
		for (let i = 0; i < li.length; i++) {
			const last = li[i].lastElementChild;
			const x = last.textContent.trim();
			const y = Number(x.substring(1));
			li[i].setAttribute("data-price", y);
			if (i !== 0) {
				ar.push(li[i]);
			}
		}
		this.run = () => {
			addevent();
		}

		function addevent() {
			select.onchange = sortingValue;
		}

		function sortingValue() {
			if (this.value === 'Default') {
				while (field.firstChild) { field.removeChild(field.firstChild); }
				field.append(li[0], ...ar);
			}
			if (this.value === 'LowToHigh') {
				SortElem(field, ar, true)
			}
			if (this.value === 'HighToLow') {
				SortElem(field, ar, false)
			}
		}

		function SortElem(field, li, asc) {
			let dm, sortli;
			dm = asc ? 1 : -1;
			sortli = li.sort((a, b) => {
				const ax = Number(a.getAttribute('data-price'));
				const bx = Number(b.getAttribute('data-price'));
				return ax > bx ? (1 * dm) : (-1 * dm);
			});
			while (field.firstChild) { field.removeChild(field.firstChild); }
			field.append(li[0], ...sortli);
		}
	}


	// new FilterProduct().run();
	// new SortProduct().run();
})();
