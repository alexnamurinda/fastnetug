// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get all the necessary elements
    const serviceFilter = document.getElementById('serviceFilter');
    const ratingFilter = document.getElementById('ratingFilter');
    const sortFilter = document.getElementById('sortFilter');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const reviewsContainer = document.getElementById('reviewsContainer');
    const activeFilterTags = document.getElementById('activeFilterTags');
    
    // Store all reviews for reset functionality
    const allReviews = Array.from(reviewsContainer.querySelectorAll('.review-item'));
    
    // Current filter state
    let currentFilters = {
        service: 'all',
        rating: 'all',
        sort: 'newest'
    };
    
    // Initialize filters
    updateActiveFilterTags();
    
    // Event listeners for filter changes
    serviceFilter.addEventListener('change', function() {
        currentFilters.service = this.value;
        updateActiveFilterTags();
        applyFilters();
    });
    
    ratingFilter.addEventListener('change', function() {
        currentFilters.rating = this.value;
        updateActiveFilterTags();
        applyFilters();
    });
    
    sortFilter.addEventListener('change', function() {
        currentFilters.sort = this.value;
        updateActiveFilterTags();
        applyFilters();
    });
    
    // Reset filters event listener
    resetFiltersBtn.addEventListener('click', function() {
        // Reset select elements to default values
        serviceFilter.value = 'all';
        ratingFilter.value = 'all';
        sortFilter.value = 'newest';
        
        // Reset filter state
        currentFilters = {
            service: 'all',
            rating: 'all',
            sort: 'newest'
        };
        
        // Update UI
        updateActiveFilterTags();
        applyFilters();
    });
    
    // Add click event for filter tags
    activeFilterTags.addEventListener('click', function(e) {
        if (e.target.classList.contains('fa-times')) {
            const tagElement = e.target.parentElement;
            const tagText = tagElement.textContent.trim().replace(' ×', '');
            
            // Find which filter to reset
            if (tagText === 'All Services' || tagText.includes('Real Estate') || tagText.includes('Air Ticket') || tagText.includes('Visa')) {
                serviceFilter.value = 'all';
                currentFilters.service = 'all';
            } else if (tagText === 'All Ratings' || tagText.includes('Star')) {
                ratingFilter.value = 'all';
                currentFilters.rating = 'all';
            } else if (tagText.includes('First')) {
                sortFilter.value = 'newest';
                currentFilters.sort = 'newest';
            }
            
            updateActiveFilterTags();
            applyFilters();
        }
    });
    
    // Function to apply filters
    function applyFilters() {
        // Clone all reviews for manipulation
        let filteredReviews = [...allReviews];
        
        // Filter by service
        if (currentFilters.service !== 'all') {
            filteredReviews = filteredReviews.filter(review => 
                review.dataset.service === currentFilters.service
            );
        }
        
        // Filter by rating
        if (currentFilters.rating !== 'all') {
            filteredReviews = filteredReviews.filter(review => 
                review.dataset.rating === currentFilters.rating
            );
        }
        
        // Sort reviews
        filteredReviews = sortReviews(filteredReviews, currentFilters.sort);
        
        // Clear the container
        reviewsContainer.innerHTML = '';
        
        // Display no results message if no reviews match the filters
        if (filteredReviews.length === 0) {
            const noResults = document.createElement('div');
            noResults.className = 'col-12 text-center';
            noResults.innerHTML = '<p class="my-5">No reviews match your selected filters. Please try different criteria.</p>';
            reviewsContainer.appendChild(noResults);
        } else {
            // Add filtered reviews back to the container
            filteredReviews.forEach(review => {
                reviewsContainer.appendChild(review);
            });
        }
        
        // Re-initialize AOS animations if used
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }
    
    // Function to sort reviews
    function sortReviews(reviews, sortType) {
        return reviews.sort((a, b) => {
            // Parse dates - assumes format is like "March 28, 2025"
            if (sortType === 'newest' || sortType === 'oldest') {
                const dateA = new Date(a.querySelector('.review-date').textContent);
                const dateB = new Date(b.querySelector('.review-date').textContent);
                
                return sortType === 'newest' ? dateB - dateA : dateA - dateB;
            }
            
            // Parse ratings (5 to 1 stars)
            if (sortType === 'highest' || sortType === 'lowest') {
                const ratingA = parseInt(a.dataset.rating);
                const ratingB = parseInt(b.dataset.rating);
                
                return sortType === 'highest' ? ratingB - ratingA : ratingA - ratingB;
            }
            
            return 0;
        });
    }
    
    // Function to update active filter tags
    function updateActiveFilterTags() {
        // Clear existing tags
        activeFilterTags.innerHTML = '';
        
        // Add service filter tag
        let serviceTagText = 'All Services';
        if (currentFilters.service === 'real-estate') serviceTagText = 'Real Estate';
        if (currentFilters.service === 'air-tickets') serviceTagText = 'Air Ticket Booking';
        if (currentFilters.service === 'visa') serviceTagText = 'Visa Consultation';
        
        const serviceTag = document.createElement('span');
        serviceTag.className = 'filter-tag';
        serviceTag.innerHTML = `${serviceTagText} <i class="fas fa-times"></i>`;
        activeFilterTags.appendChild(serviceTag);
        
        // Add rating filter tag
        let ratingTagText = 'All Ratings';
        if (currentFilters.rating !== 'all') ratingTagText = `${currentFilters.rating} Stars`;
        
        const ratingTag = document.createElement('span');
        ratingTag.className = 'filter-tag';
        ratingTag.innerHTML = `${ratingTagText} <i class="fas fa-times"></i>`;
        activeFilterTags.appendChild(ratingTag);
        
        // Add sort filter tag
        let sortTagText = 'Newest First';
        if (currentFilters.sort === 'oldest') sortTagText = 'Oldest First';
        if (currentFilters.sort === 'highest') sortTagText = 'Highest Rating';
        if (currentFilters.sort === 'lowest') sortTagText = 'Lowest Rating';
        
        const sortTag = document.createElement('span');
        sortTag.className = 'filter-tag';
        sortTag.innerHTML = `${sortTagText} <i class="fas fa-times"></i>`;
        activeFilterTags.appendChild(sortTag);
    }
    
    // Star rating functionality in the review form
    const ratingStars = document.querySelectorAll('.rating-select i');
    const ratingValue = document.getElementById('rating-value');
    
    ratingStars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = this.dataset.rating;
            highlightStars(rating);
        });
        
        star.addEventListener('mouseout', function() {
            highlightStars(ratingValue.value);
        });
        
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingValue.value = rating;
            highlightStars(rating);
        });
    });
    
    function highlightStars(rating) {
        ratingStars.forEach(star => {
            if (star.dataset.rating <= rating) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }
    
    // Form submission
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // You can implement form submission logic here
            // For example, show a success message:
            
            const formContainer = reviewForm.parentElement;
            formContainer.innerHTML = `
                <div class="submission-success">
                    <i class="fas fa-check-circle text-success mb-3" style="font-size: 48px;"></i>
                    <h3>Thank You for Your Review!</h3>
                    <p>Your feedback has been submitted successfully and will appear on our site after review.</p>
                </div>
            `;
        });
    }
});


let currentPage = 1;
const reviewsPerPage = 3;
const reviewsContainer = document.getElementById("reviewsContainer");
const reviews = Array.from(reviewsContainer.getElementsByClassName("review-item"));
const totalPages = Math.ceil(reviews.length / reviewsPerPage);

function showPage(page) {
    const start = (page - 1) * reviewsPerPage;
    const end = start + reviewsPerPage;
    reviews.forEach((review, index) => {
        if (index >= start && index < end) {
            review.style.display = 'block';
        } else {
            review.style.display = 'none';
        }
    });
    updatePagination();
}

function updatePagination() {
    const paginationItems = document.querySelectorAll(".pagination .page-item");
    paginationItems.forEach((item, index) => {
        if (index === currentPage) {
            item.classList.add("active");
        } else {
            item.classList.remove("active");
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    showPage(currentPage);

    // Handling pagination clicks
    const prevButton = document.querySelector('.pagination .page-item:first-child');
    const nextButton = document.querySelector('.pagination .page-item:last-child');
    
    prevButton.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    });

    nextButton.addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    });

    document.querySelectorAll('.pagination .page-item').forEach((button, index) => {
        if (index > 0 && index < totalPages + 1) {
            button.addEventListener('click', function() {
                currentPage = index;
                showPage(currentPage);
            });
        }
    });
});
