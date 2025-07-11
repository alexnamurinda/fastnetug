<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Services - T&T Business Solutions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/services.css" rel="stylesheet">
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
                        <a class="nav-link active" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            Services <i class="fas fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="#">Real Estate</a></li>
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
                        <a class="nav-link" href="reviews.php">Reviews</a>
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
                    <h1>Real Estate services</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="../index.php#services">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Real Estate</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Overview -->
    <section class="service-overview">
        <div class="container">
            <div class="row align-items-center">
                <!-- Image Carousel -->
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="zoom-in">
                    <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="1000">
                        <!-- Carousel Items -->
                        <div class="carousel-inner rounded overflow-hidden">
                            <div class="carousel-item active">
                                <div class="carousel-image-wrapper">
                                    <img src="../images/property1.jpg" class="d-block w-100" alt="Property 1">
                                    <div class="carousel-caption-custom">
                                        <h5>Modern City Apartment</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="carousel-image-wrapper">
                                    <img src="../images/property2.jpg" class="d-block w-100" alt="Property 2">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="carousel-image-wrapper">
                                    <img src="../images/property3.avif" class="d-block w-100" alt="Property 3">
                                    <div class="carousel-caption-custom">
                                        <h5>Peaceful Country Retreat</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="carousel-image-wrapper">
                                    <img src="../images/property4.jpg" class="d-block w-100" alt="Property 4">
                                </div>
                            </div>
                        </div>

                        <!-- Custom Dots -->
                        <div class="carousel-indicators custom-dots">
                            <button type="button" data-bs-target="#propertyCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                            <button type="button" data-bs-target="#propertyCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#propertyCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#propertyCarousel" data-bs-slide-to="3"></button>
                        </div>
                    </div>
                </div>

                <!-- Text Content -->
                <div class="col-lg-6">
                    <h2>Find Your Dream Property</h2>
                    <p class="lead">At T&T Business Solutions, we understand that finding the perfect property is more than just a transaction—it's about finding a place you can call home or an investment that secures your future.</p>
                    <p>Our experienced real estate professionals are dedicated to helping you navigate the complex property market with confidence and ease. Whether you're looking to buy, sell, or invest, we offer personalized services tailored to your unique needs.</p>
                    <div class="mt-4">
                        <a href="contact.php#inquiryForm" class="btn btn-primary">Schedule a Consultation</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Service Features -->
    <section class="service-features bg-light">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Our Real Estate Services</h2>
                <p>Comprehensive solutions for all your property needs</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3>Residential Properties</h3>
                        <p>Find your perfect home with our extensive portfolio of apartments, houses, and luxury properties in prime locations.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i> Single-family homes</li>
                            <li><i class="fas fa-check"></i> Apartments and condos</li>
                            <li><i class="fas fa-check"></i> Luxury villas</li>
                            <li><i class="fas fa-check"></i> Vacation homes</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Commercial Properties</h3>
                        <p>Expand your business with our selection of office spaces, retail locations, and industrial properties.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i> Office buildings</li>
                            <li><i class="fas fa-check"></i> Retail spaces</li>
                            <li><i class="fas fa-check"></i> Industrial properties</li>
                            <li><i class="fas fa-check"></i> Mixed-use developments</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Investment Properties</h3>
                        <p>Secure your financial future with high-yield investment properties carefully selected for optimal returns.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i> Rental properties</li>
                            <li><i class="fas fa-check"></i> Pre-construction projects</li>
                            <li><i class="fas fa-check"></i> Income-generating assets</li>
                            <li><i class="fas fa-check"></i> Market analysis</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3>Property Valuation</h3>
                        <p>Get an accurate assessment of your property's worth with our comprehensive valuation services.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i> Market analysis</li>
                            <li><i class="fas fa-check"></i> Comparable sales</li>
                            <li><i class="fas fa-check"></i> Investment potential</li>
                            <li><i class="fas fa-check"></i> Detailed reports</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Property Management</h3>
                        <p>Maximize your property's potential with our comprehensive management services for landlords and investors.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i> Tenant screening</li>
                            <li><i class="fas fa-check"></i> Rent collection</li>
                            <li><i class="fas fa-check"></i> Maintenance coordination</li>
                            <li><i class="fas fa-check"></i> Financial reporting</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h3>Legal Assistance</h3>
                        <p>Navigate property transactions with confidence with our expert legal assistance and documentation services.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i> Contract review</li>
                            <li><i class="fas fa-check"></i> Due diligence</li>
                            <li><i class="fas fa-check"></i> Closing assistance</li>
                            <li><i class="fas fa-check"></i> Legal compliance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Our Real Estate Process</h2>
                <p>How we help you find the perfect property</p>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-card">
                        <div class="process-icon">
                            <i class="fas fa-comments"></i>
                            <span class="process-number">1</span>
                        </div>
                        <h3>Consultation</h3>
                        <p>We begin with a detailed consultation to understand your property needs, preferences, and budget.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-card">
                        <div class="process-icon">
                            <i class="fas fa-search"></i>
                            <span class="process-number">2</span>
                        </div>
                        <h3>Property Search</h3>
                        <p>Our experts search for properties that match your criteria, saving you time and effort.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-card">
                        <div class="process-icon">
                            <i class="fas fa-eye"></i>
                            <span class="process-number">3</span>
                        </div>
                        <h3>Viewings</h3>
                        <p>We arrange and accompany you on property viewings, providing expert insights and advice.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="process-card">
                        <div class="process-icon">
                            <i class="fas fa-key"></i>
                            <span class="process-number">4</span>
                        </div>
                        <h3>Closing</h3>
                        <p>We handle negotiations, paperwork, and guide you through the closing process for a smooth transaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="featured-properties bg-light">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Featured Properties</h2>
                <!-- <p>Explore our selection of premium properties</p> -->
            </div>

            <div class="carousel-rows">
                <!-- Group 1 -->
                <div class="property-row row active">
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property2.jpg" alt="Luxury Apartment" class="img-fluid">
                                <span class="property-status">For Sale</span>
                            </div>
                            <div class="property-info">
                                <h3>Luxury Apartment</h3>
                                <div class="property-price">$450</div>
                                <div class="property-details">
                                    <span><i class="fas fa-bed"></i> 3 Bedrooms</span>
                                    <span><i class="fas fa-bath"></i> 2 Bathrooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 1,500 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Manhattan, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property2.jpg" alt="Luxury Apartment" class="img-fluid">
                                <span class="property-status">For Sale</span>
                            </div>
                            <div class="property-info">
                                <h3>Luxury Apartment</h3>
                                <div class="property-price">$450</div>
                                <div class="property-details">
                                    <span><i class="fas fa-bed"></i> 3 Bedrooms</span>
                                    <span><i class="fas fa-bath"></i> 2 Bathrooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 1,500 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Manhattan, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property2.jpg" alt="Luxury Apartment" class="img-fluid">
                                <span class="property-status">For Sale</span>
                            </div>
                            <div class="property-info">
                                <h3>Luxury Apartment</h3>
                                <div class="property-price">$450</div>
                                <div class="property-details">
                                    <span><i class="fas fa-bed"></i> 3 Bedrooms</span>
                                    <span><i class="fas fa-bath"></i> 2 Bathrooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 1,500 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Manhattan, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group 2 -->
                <div class="property-row row">
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property3.avif" alt="Commercial Office Space" class="img-fluid">
                                <span class="property-status">For Lease</span>
                            </div>
                            <div class="property-info">
                                <h3>Modern Office Space</h3>
                                <div class="property-price">$95 per month</div>
                                <div class="property-details">
                                    <span><i class="fas fa-users"></i> Up to 20 staff</span>
                                    <span><i class="fas fa-door-open"></i> 5 Rooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 2,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Financial District, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property3.avif" alt="Commercial Office Space" class="img-fluid">
                                <span class="property-status">For Lease</span>
                            </div>
                            <div class="property-info">
                                <h3>Modern Office Space</h3>
                                <div class="property-price">$95 per month</div>
                                <div class="property-details">
                                    <span><i class="fas fa-users"></i> Up to 20 staff</span>
                                    <span><i class="fas fa-door-open"></i> 5 Rooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 2,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Financial District, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property3.avif" alt="Commercial Office Space" class="img-fluid">
                                <span class="property-status">For Lease</span>
                            </div>
                            <div class="property-info">
                                <h3>Modern Office Space</h3>
                                <div class="property-price">$95 per month</div>
                                <div class="property-details">
                                    <span><i class="fas fa-users"></i> Up to 20 staff</span>
                                    <span><i class="fas fa-door-open"></i> 5 Rooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 2,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Financial District, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group 3 -->
                <div class="property-row row">
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property1.jpg" alt="Family Home" class="img-fluid">
                                <span class="property-status">For Sale</span>
                            </div>
                            <div class="property-info">
                                <h3>Spacious Family Home</h3>
                                <div class="property-price">$750</div>
                                <div class="property-details">
                                    <span><i class="fas fa-bed"></i> 5 Bedrooms</span>
                                    <span><i class="fas fa-bath"></i> 3.5 Bathrooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 3,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Brooklyn, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property1.jpg" alt="Family Home" class="img-fluid">
                                <span class="property-status">For Sale</span>
                            </div>
                            <div class="property-info">
                                <h3>Spacious Family Home</h3>
                                <div class="property-price">$750</div>
                                <div class="property-details">
                                    <span><i class="fas fa-bed"></i> 5 Bedrooms</span>
                                    <span><i class="fas fa-bath"></i> 3.5 Bathrooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 3,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Brooklyn, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property1.jpg" alt="Family Home" class="img-fluid">
                                <span class="property-status">For Sale</span>
                            </div>
                            <div class="property-info">
                                <h3>Spacious Family Home</h3>
                                <div class="property-price">$750</div>
                                <div class="property-details">
                                    <span><i class="fas fa-bed"></i> 5 Bedrooms</span>
                                    <span><i class="fas fa-bath"></i> 3.5 Bathrooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 3,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Brooklyn, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group 4 -->
                <div class="property-row row">
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property3.avif" alt="Commercial Office Space" class="img-fluid">
                                <span class="property-status">For Lease</span>
                            </div>
                            <div class="property-info">
                                <h3>Modern Office Space</h3>
                                <div class="property-price">$95 per month</div>
                                <div class="property-details">
                                    <span><i class="fas fa-users"></i> Up to 20 staff</span>
                                    <span><i class="fas fa-door-open"></i> 5 Rooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 2,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Financial District, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property3.avif" alt="Commercial Office Space" class="img-fluid">
                                <span class="property-status">For Lease</span>
                            </div>
                            <div class="property-info">
                                <h3>Modern Office Space</h3>
                                <div class="property-price">$95 per month</div>
                                <div class="property-details">
                                    <span><i class="fas fa-users"></i> Up to 20 staff</span>
                                    <span><i class="fas fa-door-open"></i> 5 Rooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 2,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Financial District, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="property-card">
                            <div class="property-img">
                                <img src="../images/property3.avif" alt="Commercial Office Space" class="img-fluid">
                                <span class="property-status">For Lease</span>
                            </div>
                            <div class="property-info">
                                <h3>Modern Office Space</h3>
                                <div class="property-price">$95 per month</div>
                                <div class="property-details">
                                    <span><i class="fas fa-users"></i> Up to 20 staff</span>
                                    <span><i class="fas fa-door-open"></i> 5 Rooms</span>
                                    <span><i class="fas fa-ruler-combined"></i> 2,200 sq ft</span>
                                </div>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Financial District, New York</p>
                                <a href="#" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-controls text-center mt-4">
                <button class="btn btn-outline-primary me-2" id="prevBtn">Previous</button>
                <button class="btn btn-outline-secondary page-btn" data-page="0">1</button>
                <button class="btn btn-outline-secondary page-btn" data-page="1">2</button>
                <button class="btn btn-outline-secondary page-btn" data-page="2">3</button>
                <button class="btn btn-outline-secondary page-btn" data-page="3">4</button>
                <button class="btn btn-outline-primary ms-2" id="nextBtn">Next</button>
            </div>
        </div>
    </section>


    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center" data-aos="zoom-in">
                <div class="col-lg-8">
                    <h2>Ready to Find Your Perfect Property?</h2>
                    <p>Contact our real estate experts today for a free consultation.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="contact.html" class="btn btn-light btn-lg">Contact Us Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Frequently Asked Questions</h2>
                <p>Common questions about our real estate services</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion" data-aos="fade-up">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    What services do you offer for property buyers?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our services for property buyers include property search and selection, market analysis, negotiation support, mortgage assistance, legal document review, and complete transaction management from offer to closing.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    How do you help property sellers?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    For sellers, we provide comprehensive property valuation, strategic marketing and advertising, property staging advice, qualified buyer screening, negotiation support, and complete transaction management to maximize your property's value.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What areas do you serve?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We primarily serve the greater New York area, including Manhattan, Brooklyn, Queens, Bronx, and Staten Island. We also have partner networks in other major U.S. cities and international markets for clients with broader property needs.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    What makes your real estate services different?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our approach combines local market expertise with personalized service. We prioritize understanding your unique needs and providing tailored solutions. Additionally, our integration with other T&T Business Solutions services provides a comprehensive approach that simplifies the entire process.
                                </div>
                            </div>
                        </div>
                        <!-- <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Do you offer investment advice for real estate?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, we provide comprehensive investment advisory services for real estate. This includes market analysis, investment strategy development, ROI projections, portfolio diversification recommendations, and ongoing property management for your investment properties.
                                </div>
                            </div>
                        </div> -->
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
                            <li><a href="reviews.php">Reviews</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="footer-links">
                        <h4>Our Services</h4>
                        <ul>
                            <li><a href="#">Real Estate</a></li>
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
    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="../scripts/main.js"></script>
    <script src="../scripts/services.js"></script>
</body>

</html>