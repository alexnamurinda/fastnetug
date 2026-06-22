<?php
$basePath = '../';
$activePage = 'reviews';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Reviews - FastNetUG</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/reviews.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
</head>

<body>

<?php include '../includes/nav.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h1>Client Reviews</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reviews</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Overview -->
    <section class="reviews-overview">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-4" data-aos="zoom-in">
                    <div class="reviews-summary">
                        <div class="overall-rating">
                            <div class="rating-number">4.9</div>
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="rating-count">Based on 150+ reviews</div>
                        </div>
                        <div class="rating-breakdown">
                            <div class="rating-bar">
                                <span class="rating-label">5 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">85%</span>
                            </div>
                            <div class="rating-bar">
                                <span class="rating-label">4 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">12%</span>
                            </div>
                            <div class="rating-bar">
                                <span class="rating-label">3 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 2%" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">2%</span>
                            </div>
                            <div class="rating-bar">
                                <span class="rating-label">2 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 1%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">1%</span>
                            </div>
                            <div class="rating-bar">
                                <span class="rating-label">1 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8" data-aos="fade-left">
                    <div class="service-ratings">
                        <h3>What Our Users Love</h3>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="service-rating-card">
                                    <div class="service-icon">
                                        <i class="fas fa-tachometer-alt"></i>
                                    </div>
                                    <h4>Connection Speed</h4>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="rating-score">4.9/5</div>
                                    <div class="review-count">Fast &amp; reliable</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="service-rating-card">
                                    <div class="service-icon">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <h4>Pricing</h4>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="rating-score">4.8/5</div>
                                    <div class="review-count">Affordable packages</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="service-rating-card">
                                    <div class="service-icon">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <h4>Customer Support</h4>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <div class="rating-score">4.7/5</div>
                                    <div class="review-count">24/7 support</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Reviews -->
    <section class="featured-reviews">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Featured Reviews</h2>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="featured-review-card">
                        <div class="review-header">
                            <div class="reviewer-image">
                                <img src="../images/developer.jpg" alt="Sarah" class="rounded-circle">
                            </div>
                            <div class="reviewer-info">
                                <h4>Sarah Nakato</h4>
                                <div class="service-badge student">Namuli Hostel</div>
                            </div>
                            <div class="review-rating">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="review-date">May 15, 2025</div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>"FastNetUG has been a game-changer for my studies! The connection is super fast and stable - I can stream lectures, download research materials, and attend online classes without any interruptions."</p>
                            <p>"The pricing is very student-friendly, and the support team is always ready to help. I've recommended it to all my classmates!"</p>
                        </div>
                        <div class="review-response">
                            <h5><i class="fas fa-reply"></i> Response from FastNetUG</h5>
                            <p>Thank you Sarah! We're thrilled to support your academic journey. Keep excelling in your studies!</p>
                            <div class="response-author">— FastNetUG Team</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="featured-review-card">
                        <div class="review-header">
                            <div class="reviewer-image">
                                <img src="../images/sara.jpg" alt="James" class="rounded-circle">
                            </div>
                            <div class="reviewer-info">
                                <h4>James Okello</h4>
                                <div class="service-badge hostel">Kasalita Hostel</div>
                            </div>
                            <div class="review-rating">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="review-date">June 2, 2025</div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>"I've been using FastNetUG for 6 months now and I'm impressed with the consistency. Whether it's late night research or weekend Netflix, the connection never disappoints."</p>
                            <p>"The registration process was simple, and the agents are very professional. Worth every shilling!"</p>
                        </div>
                        <div class="review-response">
                            <h5><i class="fas fa-reply"></i> Response from FastNetUG</h5>
                            <p>Thanks James! We're committed to providing reliable internet for both your studies and entertainment. Enjoy your streaming!</p>
                            <div class="response-author">— FastNetUG Team</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Filters -->
    <section class="review-filters-section">
        <div class="container">
            <div class="filter-container" data-aos="fade-up">
                <div class="filter-header">
                    <h3><i class="fas fa-sliders-h me-2"></i>Filter Reviews</h3>
                    <button class="reset-btn" id="resetFilters"><i class="fas fa-undo me-1"></i> Reset Filters</button>
                </div>
                <div class="filter-options">
                    <div class="filter-group">
                        <label for="serviceFilter">Location</label>
                        <div class="custom-select">
                            <select class="filter-select" id="serviceFilter">
                                <option value="all">All Locations</option>
                                <option value="namuli">Namuli Hostel</option>
                                <option value="kasalita">Kasalita Hostel</option>
                                <option value="herman">Herman Hostel</option>
                                <option value="new-hostel">New Hostel</option>
                                <option value="lower-herman">Lower Herman</option>
                            </select>
                            <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label for="ratingFilter">Rating</label>
                        <div class="custom-select">
                            <select class="filter-select" id="ratingFilter">
                                <option value="all">All Ratings</option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                            <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label for="sortFilter">Sort By</label>
                        <div class="custom-select">
                            <select class="filter-select" id="sortFilter">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="highest">Highest Rating</option>
                                <option value="lowest">Lowest Rating</option>
                            </select>
                            <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>
                </div>
                <div class="active-filters">
                    <span class="active-filter-label">Active Filters:</span>
                    <div class="filter-tags" id="activeFilterTags"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- All Reviews -->
    <section class="all-reviews">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Recent Reviews</h2>
            <div class="reviews-grid" id="reviewsContainer">

                <div class="review-item" data-aos="fade-up" data-service="herman" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/developer.jpg" alt="Mary" class="rounded-circle">
                            <div>
                                <h5>Mary Auma</h5>
                                <div class="service-badge student">Herman Hostel</div>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="review-date">July 10, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"Amazing speeds for online classes! The connection is stable even during peak hours. FastNetUG has made my university life so much easier."</p>
                    </div>
                </div>

                <div class="review-item" data-aos="fade-up" data-service="new-hostel" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/sara.jpg" alt="Peter" class="rounded-circle">
                            <div>
                                <h5>Peter Ssemakula</h5>
                                <div class="service-badge hostel">New Hostel</div>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="review-date">July 5, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"Best WiFi service around campus! Fast, reliable, and affordable. The registration process was quick and the support team is very helpful."</p>
                    </div>
                </div>

                <div class="review-item" data-aos="fade-up" data-service="namuli" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/developer.jpg" alt="Grace" class="rounded-circle">
                            <div>
                                <h5>Grace Nalubega</h5>
                                <div class="service-badge student">Namuli Hostel</div>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="review-date">June 28, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"Perfect for students! I can download large files, stream videos, and attend virtual classes without any issues. Highly recommended!"</p>
                    </div>
                </div>

                <div class="review-item" data-aos="fade-up" data-service="lower-herman" data-rating="4">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/sara.jpg" alt="David" class="rounded-circle">
                            <div>
                                <h5>David Mutesi</h5>
                                <div class="service-badge home">Lower Herman</div>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="review-date">June 20, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"Good service overall. The connection is fast and the prices are reasonable. Sometimes there are minor slowdowns during peak hours, but it's still much better than other options."</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Write Review -->
    <section class="write-review-section">
        <div class="container">
            <div class="write-review-container" data-aos="fade-up">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <div class="write-review-content">
                            <h2>Share Your Experience</h2>
                            <p>We value your feedback! Share your experience with FastNetUG and help other students make informed decisions about their internet connectivity.</p>
                            <ul class="review-benefits">
                                <li><i class="fas fa-check-circle"></i> Help fellow students choose the best WiFi</li>
                                <li><i class="fas fa-check-circle"></i> Share your connectivity experience</li>
                                <li><i class="fas fa-check-circle"></i> Help us improve our services</li>
                                <li><i class="fas fa-check-circle"></i> Recognize our support team</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form id="review-form" class="review-form">
                            <div class="form-group">
                                <label for="reviewer-name">Your Name</label>
                                <input type="text" class="form-control" id="reviewer-name" required>
                            </div>
                            <div class="form-group">
                                <label for="service-type">Your Location</label>
                                <select class="form-control" id="service-type" required>
                                    <option value="">Select your hostel/location</option>
                                    <option value="namuli">Namuli Hostel</option>
                                    <option value="kasalita">Kasalita Hostel</option>
                                    <option value="herman">Herman Hostel</option>
                                    <option value="new-hostel">New Hostel</option>
                                    <option value="lower-herman">Lower Herman</option>
                                    <option value="other">Other Location</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Your Rating</label>
                                <div class="rating-select">
                                    <i class="far fa-star" data-rating="1"></i>
                                    <i class="far fa-star" data-rating="2"></i>
                                    <i class="far fa-star" data-rating="3"></i>
                                    <i class="far fa-star" data-rating="4"></i>
                                    <i class="far fa-star" data-rating="5"></i>
                                </div>
                                <input type="hidden" id="rating-value" value="0" required>
                            </div>
                            <div class="form-group">
                                <label for="review-text">Your Review</label>
                                <textarea class="form-control" id="review-text" rows="5" required></textarea>
                                <small class="text-muted">Tell us about your FastNetUG experience</small>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="consent-checkbox" required>
                                <label class="form-check-label" for="consent-checkbox">I consent to having my review displayed on the FastNetUG website</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include '../includes/footer.php'; ?>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" aria-label="Back to Top"><i class="fas fa-arrow-up"></i></a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="../scripts/main.js"></script>
    <script src="../scripts/reviews.js"></script>
</body>

</html>
