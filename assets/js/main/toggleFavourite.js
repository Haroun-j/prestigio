export function initFavoriteToggle() {
    const heartIcons = document.querySelectorAll('.heart-container');
    
    heartIcons.forEach(icon => {
        icon.addEventListener('click', async (event) => {
            event.preventDefault();
            const productId = icon.dataset.productId;
            if (!productId) {
                console.error('Product ID not found on element.');
                return;
            }
            
            try {
                const response = await fetch('/add-to-favorite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({productId: productId})
                });
                
                const data = await response.json();
                if (data.success) {
                    if (data.isLiked) {
                        icon.style.background = "linear-gradient(45deg, cyan, royalblue)";
                    } else {
                        icon.style.background = "#00000030";
                    }
                } else {
                    console.error('Error toggling favorite:', data.message);
                }
            } catch (error) {
                console.error('Request failed:', error);
            }
        });
    });
}
