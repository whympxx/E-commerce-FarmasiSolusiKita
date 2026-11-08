// Products Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add loading animation to product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Smooth scroll for category links
    const categoryLinks = document.querySelectorAll('.category-sidebar a');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state to the clicked link
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            
            // Reset after navigation
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 1000);
        });
    });

    // Filter form enhancement
    const filterForm = document.querySelector('.filter-section form');
    if (filterForm) {
        const inputs = filterForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Add visual feedback
                this.style.borderColor = 'var(--primary-color)';
                setTimeout(() => {
                    this.style.borderColor = '';
                }, 1000);
            });
        });
    }

    // Price range validation
    const minPriceInput = document.querySelector('input[name="min_price"]');
    const maxPriceInput = document.querySelector('input[name="max_price"]');
    
    if (minPriceInput && maxPriceInput) {
        maxPriceInput.addEventListener('input', function() {
            const minPrice = parseFloat(minPriceInput.value) || 0;
            const maxPrice = parseFloat(this.value) || 0;
            
            if (maxPrice > 0 && minPrice > maxPrice) {
                this.style.borderColor = 'var(--danger-color)';
                this.setCustomValidity('Harga maksimal harus lebih besar dari harga minimal');
            } else {
                this.style.borderColor = '';
                this.setCustomValidity('');
            }
        });
    }

    // Add to cart animation
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.disabled) return;
            
            // Add ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Search enhancement
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Add search icon animation
                const searchIcon = this.parentElement.querySelector('.btn i');
                if (searchIcon) {
                    searchIcon.className = 'fas fa-spinner fa-spin';
                    setTimeout(() => {
                        searchIcon.className = 'fas fa-search';
                    }, 1000);
                }
            }, 500);
        });
    }

    // Sort dropdown enhancement
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            // Add loading state to the form
            const form = this.closest('form');
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengurutkan...';
                submitButton.disabled = true;
            }
        });
    }

    // Lazy loading for product images (if implemented later)
    const productImages = document.querySelectorAll('.product-card .card-img-top img');
    if (productImages.length > 0) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('loading-skeleton');
                    observer.unobserve(img);
                }
            });
        });

        productImages.forEach(img => {
            if (img.dataset.src) {
                imageObserver.observe(img);
            }
        });
    }

    // Keyboard navigation for product cards
    productCards.forEach(card => {
        card.setAttribute('tabindex', '0');
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const detailLink = this.querySelector('.btn-view-detail');
                if (detailLink) {
                    detailLink.click();
                }
            }
        });
    });

    // Add to favorites functionality (placeholder)
    const addToFavorites = (productId) => {
        // This would be implemented when favorites feature is added
        console.log('Add to favorites:', productId);
        showMessage('Fitur favorit akan segera hadir!', 'info');
    };

    // Quick view functionality (placeholder)
    const quickView = (productId) => {
        // This would be implemented when quick view modal is added
        console.log('Quick view:', productId);
        showMessage('Fitur quick view akan segera hadir!', 'info');
    };

    // Share product functionality
    const shareProduct = (productUrl, productName) => {
        if (navigator.share) {
            navigator.share({
                title: productName,
                url: productUrl
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(productUrl).then(() => {
                showMessage('Link produk berhasil disalin!', 'success');
            });
        }
    };

    // Infinite scroll (optional enhancement)
    let isLoading = false;
    let currentPage = 1;
    
    const loadMoreProducts = () => {
        if (isLoading) return;
        
        isLoading = true;
        currentPage++;
        
        const url = new URL(window.location);
        url.searchParams.set('page', currentPage);
        
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newProducts = doc.querySelectorAll('.product-card');
                
                if (newProducts.length > 0) {
                    const productsContainer = document.querySelector('.row');
                    newProducts.forEach(product => {
                        productsContainer.appendChild(product);
                    });
                }
                
                isLoading = false;
            })
            .catch(error => {
                console.error('Error loading more products:', error);
                isLoading = false;
            });
    };

    // Intersection Observer for infinite scroll
    const loadMoreTrigger = document.createElement('div');
    loadMoreTrigger.style.height = '20px';
    loadMoreTrigger.style.width = '100%';
    
    const loadMoreObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading) {
                loadMoreProducts();
            }
        });
    });
    
    // Add load more trigger to pagination container
    const paginationContainer = document.querySelector('.pagination-container');
    if (paginationContainer) {
        paginationContainer.appendChild(loadMoreTrigger);
        loadMoreObserver.observe(loadMoreTrigger);
    }

    // Product comparison functionality (placeholder)
    const compareProducts = (productIds) => {
        console.log('Compare products:', productIds);
        showMessage('Fitur perbandingan produk akan segera hadir!', 'info');
    };

    // Wishlist functionality (placeholder)
    const addToWishlist = (productId) => {
        console.log('Add to wishlist:', productId);
        showMessage('Fitur wishlist akan segera hadir!', 'info');
    };

    // Export functions to global scope for use in other scripts
    window.ProductsPage = {
        addToFavorites,
        quickView,
        shareProduct,
        compareProducts,
        addToWishlist
    };
});

// Ripple animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .product-card {
        cursor: pointer;
    }
    
    .product-card:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }
    
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .slide-in {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style); 