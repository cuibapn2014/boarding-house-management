/**
 * Saved Listing Functionality
 * Handles saving and unsaving boarding house listings
 */

// CSRF Token for Laravel
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

/**
 * Toggle save/unsave a listing
 * @param {HTMLElement} button - The save button element
 */
async function toggleSaveListing(button) {
    const boardingHouseId = button.dataset.boardingHouseId;
    const isSaved = button.dataset.saved === 'true';
    
    // Check if user is authenticated
    if (!isUserAuthenticated()) {
        showToast('Vui lòng đăng nhập để lưu tin', 'warning');
        // Redirect to login page after a delay
        setTimeout(() => {
            window.location.href = '/login';
        }, 1500);
        return;
    }

    // Disable button during request
    button.disabled = true;

    try {
        const response = await fetch('/api/saved-listings/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                boarding_house_id: boardingHouseId
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Update button state
            updateSaveButton(button, data.saved);
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        console.error('Error toggling saved listing:', error);
        showToast('Không thể kết nối đến máy chủ', 'error');
    } finally {
        button.disabled = false;
    }
}

/**
 * Update save button appearance
 * @param {HTMLElement} button - The save button element
 * @param {boolean} isSaved - Whether the listing is saved
 */
function updateSaveButton(button, isSaved) {
    const icon = button.querySelector('i');
    const textSpan = button.querySelector('span');
    
    if (isSaved) {
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid');
        button.dataset.saved = 'true';
        button.setAttribute('title', 'Bỏ lưu tin');
        button.setAttribute('aria-label', 'Bỏ lưu tin này');
        if (textSpan) {
            textSpan.textContent = 'Đã lưu';
        }
    } else {
        icon.classList.remove('fa-solid');
        icon.classList.add('fa-regular');
        button.dataset.saved = 'false';
        button.setAttribute('title', 'Lưu tin');
        button.setAttribute('aria-label', 'Lưu tin này');
        if (textSpan) {
            textSpan.textContent = 'Lưu tin';
        }
    }
}

/**
 * Check if user is authenticated
 * @returns {boolean}
 */
function isUserAuthenticated() {
    // You can implement this based on your authentication system
    // For now, we'll check if there's a user session indicator
    // This should be set in your master layout
    return window.isAuthenticated || false;
}

/**
 * Show toast notification
 * @param {string} message - The message to display
 * @param {string} type - The type of toast (success, error, warning, info)
 */
function showToast(message, type = 'info') {
    // Check if Toastify is available
    if (typeof Toastify !== 'undefined') {
        const bgColors = {
            success: 'linear-gradient(to right, #00b09b, #96c93d)',
            error: 'linear-gradient(to right, #ff5f6d, #ffc371)',
            warning: 'linear-gradient(to right, #f7971e, #ffd200)',
            info: 'linear-gradient(to right, #667eea, #764ba2)'
        };

        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: {
                background: bgColors[type] || bgColors.info,
            }
        }).showToast();
    } else {
        // Fallback to alert if Toastify is not available
        alert(message);
    }
}

/**
 * Load saved status for all listings on the page
 */
async function loadSavedStatuses() {
    if (!isUserAuthenticated()) {
        return;
    }

    const saveButtons = document.querySelectorAll('.save-listing-btn');
    
    for (const button of saveButtons) {
        const boardingHouseId = button.dataset.boardingHouseId;
        
        try {
            const response = await fetch(`/api/saved-listings/check/${boardingHouseId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            const data = await response.json();
            
            if (data.saved) {
                updateSaveButton(button, true);
            }
        } catch (error) {
            console.error('Error checking saved status:', error);
        }
    }
}

/**
 * Remove a saved listing (for saved listings page)
 * @param {number} boardingHouseId - The ID of the boarding house
 * @param {HTMLElement} cardElement - The card element to remove
 */
async function removeSavedListing(boardingHouseId, cardElement) {
    if (!confirm('Bạn có chắc muốn bỏ lưu tin này?')) {
        return;
    }

    try {
        const response = await fetch(`/api/saved-listings/${boardingHouseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Fade out and remove the card
            cardElement.style.transition = 'opacity 0.3s ease';
            cardElement.style.opacity = '0';
            
            setTimeout(() => {
                cardElement.remove();
                
                // Check if there are no more saved listings
                const remainingCards = document.querySelectorAll('.saved-listing-card');
                if (remainingCards.length === 0) {
                    location.reload(); // Reload to show empty state
                }
            }, 300);
            
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        console.error('Error removing saved listing:', error);
        showToast('Không thể kết nối đến máy chủ', 'error');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load saved statuses for all listings
    loadSavedStatuses();
    
    // Add event listeners for save buttons if not using inline onclick
    // (keeping inline onclick for simplicity, but this is an alternative approach)
    /*
    const saveButtons = document.querySelectorAll('.save-listing-btn');
    saveButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSaveListing(this);
        });
    });
    */
});

