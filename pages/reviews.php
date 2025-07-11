<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Reviews - T&T Business Solutions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/reviews.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <span class="logo-text">T&T</span> Business Solutions
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            Services <i class="fas fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="services.php">Real Estate</a></li>
                            <li><a class="dropdown-item" href="airticketbooking.php">Air Ticket Booking</a></li>
                            <li><a class="dropdown-item" href="consultation.php">Visa Consultation</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Reviews</a>
                    </li>
                </ul>
                <!-- <div class="ms-3 d-none d-lg-block">
                    <a href="pages/contact.php" class="btn btn-primary">Get a Quote</a>
                </div> -->
            </div>
        </div>
    </nav>

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

    <!-- Review Filters -->
    <section class="review-filters-section">
        <div class="container">
            <div class="filter-container" data-aos="fade-up">
                <div class="filter-header">
                    <h3>Filter Reviews</h3>
                    <button id="resetFilters" class="reset-btn"><i class="fas fa-redo-alt"></i> Reset All Filters</button>
                </div>
                <div class="filter-options">
                    <div class="filter-group">
                        <label for="serviceFilter">Service</label>
                        <div class="custom-select">
                            <select id="serviceFilter" class="filter-select">
                                <option value="all">All Services</option>
                                <option value="real-estate">Real Estate</option>
                                <option value="air-tickets">Air Ticket Booking</option>
                                <option value="visa">Visa Consultation</option>
                            </select>
                            <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label for="ratingFilter">Rating</label>
                        <div class="custom-select">
                            <select id="ratingFilter" class="filter-select">
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
                        <label for="sortFilter">Sort by</label>
                        <div class="custom-select">
                            <select id="sortFilter" class="filter-select">
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
                    <div class="filter-tags" id="activeFilterTags">
                        <span class="filter-tag">All Services <i class="fas fa-times"></i></span>
                        <span class="filter-tag">All Ratings <i class="fas fa-times"></i></span>
                        <span class="filter-tag">Newest First <i class="fas fa-times"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Overview Section -->
    <section class="reviews-overview">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4" data-aos="zoom-in">
                    <div class="reviews-summary">
                        <div class="overall-rating">
                            <div class="rating-number">4.8</div>
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <div class="rating-count">Based on 312 reviews</div>
                        </div>
                        <div class="rating-breakdown">
                            <div class="rating-bar">
                                <span class="rating-label">5 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">75%</span>
                            </div>
                            <div class="rating-bar">
                                <span class="rating-label">4 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">18%</span>
                            </div>
                            <div class="rating-bar">
                                <span class="rating-label">3 Star</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 5%" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">5%</span>
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
                                    <div class="progress-bar" role="progressbar" style="width: 1%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="rating-percentage">1%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="service-ratings">
                        <h3>Service Ratings</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="service-rating-card">
                                    <div class="service-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <h4>Real Estate</h4>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="rating-score">4.9/5</div>
                                    <div class="review-count">145 reviews</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="service-rating-card">
                                    <div class="service-icon">
                                        <i class="fas fa-plane"></i>
                                    </div>
                                    <h4>Air Ticket Booking</h4>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <div class="rating-score">4.7/5</div>
                                    <div class="review-count">98 reviews</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="service-rating-card">
                                    <div class="service-icon">
                                        <i class="fas fa-passport"></i>
                                    </div>
                                    <h4>Visa Consultation</h4>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <div class="rating-score">4.6/5</div>
                                    <div class="review-count">69 reviews</div>
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
            <div class="row">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="featured-review-card">
                        <div class="review-header">
                            <div class="reviewer-image">
                                <img src="../images/developer.jpg" alt="Client" class="rounded-circle">
                            </div>
                            <div class="reviewer-info">
                                <h4>Alexander Wilson</h4>
                                <div class="service-badge real-estate">Real Estate</div>
                            </div>
                            <div class="review-rating">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="review-date">March 15, 2025</div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>"I cannot express how grateful I am to T&T Business Solutions for their exceptional real estate services. As a first-time investor, I was extremely nervous about entering the property market. Their team, especially Adam, took the time to understand my goals and financial situation, then guided me through the entire process with patience and expertise."</p>
                            <p>"They found me a property that exceeded my expectations while staying within my budget. The attention to detail during negotiations and paperwork was impressive. I've already recommended them to several colleagues!"</p>
                        </div>
                        <div class="review-response">
                            <h5><i class="fas fa-reply"></i> Response from T&T Business Solutions</h5>
                            <p>Thank you so much for your kind words, Alexander! We're thrilled that we could help you find the perfect investment property. Adam enjoyed working with you and appreciates your recommendations. We look forward to assisting you with any future real estate needs!</p>
                            <div class="response-author">— Management Team, T&T Business Solutions</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="featured-review-card">
                        <div class="review-header">
                            <div class="reviewer-image">
                                <img src="../images/sara.jpg" alt="Client" class="rounded-circle">
                            </div>
                            <div class="reviewer-info">
                                <h4>Sophia Rodriguez</h4>
                                <div class="service-badge visa">Visa Consultation</div>
                            </div>
                            <div class="review-rating">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="review-date">March 28, 2025</div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>"After being rejected twice for my business visa application, I was losing hope. A friend recommended T&T Business Solutions, and it was the best recommendation I've ever received. Their visa consultation team performed a thorough review of my previous applications and immediately identified the issues."</p>
                            <p>"Their knowledge of visa requirements and attention to detail is remarkable. They helped restructure my application, prepared me thoroughly for the interview, and provided continuous support throughout the process. I'm now successfully running my business overseas thanks to their expertise!"</p>
                        </div>
                        <div class="review-response">
                            <h5><i class="fas fa-reply"></i> Response from T&T Business Solutions</h5>
                            <p>We're delighted to hear about your successful visa application, Sophia! Your determination combined with our team's expertise made for a winning combination. We appreciate your trust in our services during such an important process in your professional journey. Wishing you continued success with your business ventures!</p>
                            <div class="response-author">— Visa Consultation Team, T&T Business Solutions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- All Reviews -->
    <section class="all-reviews">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">All Reviews</h2>
            <div class="reviews-grid" id="reviewsContainer">
                <!-- Review Item 1 -->
                <div class="review-item" data-aos="fade-up" data-service="air-tickets" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/developer.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Emily Thompson</h5>
                                <div class="service-badge air-tickets">Air Ticket Booking</div>
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
                            <span class="review-date">April 2, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"T&T's air ticket booking service saved me over $400 on my family vacation flights! Their team found connections I didn't know existed and secured excellent seats. The whole process was smooth and stress-free."</p>
                    </div>
                </div>

                <!-- Review Item 2 -->
                <div class="review-item" data-aos="fade-up" data-service="real-estate" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/sara.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Robert Taylor</h5>
                                <div class="service-badge real-estate">Real Estate</div>
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
                            <span class="review-date">March 30, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"As a property investor, I've worked with many real estate firms, but T&T Business Solutions stands out. Their market knowledge and negotiation skills are exceptional. They helped me acquire two commercial properties that are already yielding excellent returns."</p>
                    </div>
                </div>

                <!-- Review Item 3 -->
                <div class="review-item" data-aos="fade-up" data-service="visa" data-rating="4">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/micheal.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Priya Sharma</h5>
                                <div class="service-badge visa">Visa Consultation</div>
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
                            <span class="review-date">March 28, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"Their visa consultation service was very thorough and professional. The team was knowledgeable about all the requirements and helped me prepare a strong application. The only minor issue was occasionally delayed responses, but overall I'm satisfied with the service."</p>
                    </div>
                </div>

                <!-- Review Item 4 -->
                <div class="review-item" data-aos="fade-up" data-service="air-tickets" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/sara.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Daniel Johnson</h5>
                                <div class="service-badge air-tickets">Air Ticket Booking</div>
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
                            <span class="review-date">March 25, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"I needed last-minute flights for a business emergency, and T&T came through spectacularly. Despite the short notice, they found reasonable fares and managed to get me preferred seating. Their 24/7 support was a lifesaver during my travels."</p>
                    </div>
                </div>

                <!-- Review Item 5 -->
                <div class="review-item" data-aos="fade-up" data-service="real-estate" data-rating="4">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/developer.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Jessica Lee</h5>
                                <div class="service-badge real-estate">Real Estate</div>
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
                            <span class="review-date">March 22, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"T&T helped me sell my property faster than expected and at a good price. The marketing materials they created were impressive, and they arranged numerous viewings. The process could have been more streamlined, but I'm happy with the end result."</p>
                    </div>
                </div>

                <!-- Review Item 6 -->
                <div class="review-item" data-aos="fade-up" data-service="visa" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/micheal.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Carlos Mendez</h5>
                                <div class="service-badge visa">Visa Consultation</div>
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
                            <span class="review-date">March 20, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"After struggling with visa applications for months, T&T's expert guidance made all the difference. Their detailed approach and knowledge of recent policy changes helped me secure my visa on the first attempt. Their interview preparation was particularly helpful."</p>
                    </div>
                </div>

                <!-- Review Item 7 -->
                <div class="review-item" data-aos="fade-up" data-service="air-tickets" data-rating="3">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/sara.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Thomas Wilson</h5>
                                <div class="service-badge air-tickets">Air Ticket Booking</div>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="review-date">March 18, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"The flight options were good, but I experienced some communication issues. There was confusion about my luggage allowance that wasn't clarified until the last minute. The service was average overall, but they did resolve the issues eventually."</p>
                    </div>
                    <!-- <div class="review-response">
                        <h5><i class="fas fa-reply"></i> Response from T&T Business Solutions</h5>
                        <p>Thank you for your feedback, Thomas. We apologize for the confusion regarding your luggage allowance. We're reviewing our communication protocols to ensure such misunderstandings don't happen again. We appreciate your patience and would love the opportunity to serve you better in the future.</p>
                        <div class="response-author">— Air Ticket Team, T&T Business Solutions</div>
                    </div> -->
                </div>

                <!-- Review Item 8 -->
                <div class="review-item" data-aos="fade-up" data-service="real-estate" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/micheal.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Linda Chen</h5>
                                <div class="service-badge real-estate">Real Estate</div>
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
                            <span class="review-date">March 15, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"T&T's property management services have been outstanding. They've handled everything from tenant screening to maintenance with professionalism. My rental properties are in good hands, and I appreciate their detailed monthly reports and prompt responses to any issues."</p>
                    </div>
                </div>

                <!-- Review Item 9 -->
                <div class="review-item" data-aos="fade-up" data-service="visa" data-rating="5">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="../images/developer.jpg" alt="Client" class="rounded-circle">
                            <div>
                                <h5>Mohammed Al-Farsi</h5>
                                <div class="service-badge visa">Visa Consultation</div>
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
                            <span class="review-date">March 12, 2025</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>"T&T's visa consultation service is simply excellent. They guided me through a complex business visa application with expertise and patience. What impressed me most was their knowledge of specific regional requirements and their personalized approach to my situation."</p>
                    </div>
                </div>
            </div>


            <!-- Pagination -->
            <div class="reviews-pagination" data-aos="fade-up">
                <nav aria-label="Reviews pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" tabindex="-1" aria-disabled="true">1</a>
                        </li>
                        <li class="page-item active"><a class="page-link">2</a></li>
                        <li class="page-item"><a class="page-link">3</a></li>
                        <li class="page-item"><a class="page-link">4</a></li>
                    </ul>
                </nav>
            </div>

        </div>
    </section>

    <!-- Write Review Section -->
    <section class="write-review-section">
        <div class="container">
            <div class="write-review-container" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="write-review-content">
                            <h2 style="color: #3498db;">Share Your Experience</h2>
                            <p>We value your feedback and would love to hear about your experience with T&T Business Solutions. Your review helps us improve our services and assists other clients in making informed decisions.</p>
                            <ul class="review-benefits">
                                <li><i class="fas fa-check-circle"></i> Help others make informed decisions</li>
                                <li><i class="fas fa-check-circle"></i> Share your unique experience</li>
                                <li><i class="fas fa-check-circle"></i> Contribute to our service improvements</li>
                                <li><i class="fas fa-check-circle"></i> Recognize our team members who went above and beyond</li>
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
                                <label for="service-type">Service Used</label>
                                <select class="form-control" id="service-type" required>
                                    <option value="">Select a service</option>
                                    <option value="accounting">Accounting & Bookkeeping</option>
                                    <option value="tax">Tax Advisory</option>
                                    <option value="business">Business Setup</option>
                                    <option value="real-estate">Real Estate</option>
                                    <option value="visa">Visa Consultation</option>
                                    <option value="other">Other Services</option>
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
                                <small class="text-muted">Minimum 50 characters</small>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="consent-checkbox" required>
                                <label class="form-check-label" for="consent-checkbox">I consent to having my review and name displayed on the T&T website</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="review-faq-section bg-light">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Frequently Asked Questions</h2>
                <p>Common questions about our review process and services</p>
            </div>
            <div class="row" data-aos="fade-up">
                <div class="col-lg-6">
                    <div class="accordion" id="reviewFaqAccordion1">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How are reviews verified?
                                </button>
                            </h3>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#reviewFaqAccordion1">
                                <div class="accordion-body">
                                    <p>All reviews on our site come from verified clients who have used our services. We verify client status through our internal records before publishing reviews to ensure authenticity and reliability.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Can I update my review later?
                                </button>
                            </h3>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#reviewFaqAccordion1">
                                <div class="accordion-body">
                                    <p>Yes, you can update your review at any time. Simply contact our customer service team with your request, and they will assist you in updating your existing review to reflect your latest experience with our services.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="accordion" id="reviewFaqAccordion2">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    How long does it take for my review to appear?
                                </button>
                            </h3>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#reviewFaqAccordion2">
                                <div class="accordion-body">
                                    <p>Reviews typically appear on our website within 1-2 business days after submission. Each review goes through a brief verification process to ensure it meets our community guidelines before being published.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Do you remove negative reviews?
                                </button>
                            </h3>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#reviewFaqAccordion2">
                                <div class="accordion-body">
                                    <p>No, we do not remove reviews based on rating alone. We believe in transparency and publish all authentic reviews that comply with our guidelines, regardless of whether they are positive or negative. This helps us improve our services and provides honest insights to potential clients.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="footer-info">
                        <img src="../images/logo.png" alt="Logo" class="footer-logo mb-3">
                        <p>Your trusted partner for real estate, air ticket booking, visa consultation, and more.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <div class="footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="../index.php">Home</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="../index.php#services">Services</a></li>
                            <li><a href="contact.php">Contact Us</a></li>
                            <li><a href="#">Reviews</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="footer-links">
                        <h4>Our Services</h4>
                        <ul>
                            <li><a href="services.php">Real Estate</a></li>
                            <li><a href="airticketbooking.php">Air Ticket Booking</a></li>
                            <li><a href="consultation.php">Visa Consultation</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="footer-contact">
                        <h4>Contact Us</h4>
                        <p>
                            <i class="fas fa-map-marker-alt"></i> 123 Nabisunsa, Jinja Road<br>
                            Kampala, Uganda<br>
                            <i class="fas fa-phone"></i>+256 744 - 766 - 410<br>
                            <i class="fas fa-envelope"></i> info@ttbusinesssolution.com
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row d-flex align-items-center justify-content-between">
                    <div class="col-md-5 text-md-start text-center">
                        <p class="copyright">© 2025 T&T Business Solution. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-3 text-md-center text-center">
                        <p class="copyright">
                            Developed by <a href="https://namurindaalex.github.io/portfolio/" target="_blank" class="developer-link">namtechnologies.com</a>
                        </p>
                    </div>
                    <div class="col-md-3 text-md-end text-center">
                        <div class="footer-links-bottom">
                            <a href="">Privacy Policy</a>
                            <a href="">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" aria-label="Back to Top"><i class="fas fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

    <!-- Custom Script -->
    <script src="../scripts/main.js"></script>
    <script src="../scripts/reviews.js"></script>
</body>

</html>