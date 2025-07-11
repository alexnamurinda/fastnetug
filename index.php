<!DOCTYPE html>
<html lang="en"> 

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastNetUG | Premium WiFi Solutions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/mainstyles.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span class="logo-text">FastNetUG</span> <i class="fas fa-wifi wifi-animation"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            Services <i class="fas fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="pages/packages.php">WiFi Packages</a></li>
                            <li><a class="dropdown-item" href="pages/installation.php">Installation Services</a></li>
                            <li><a class="dropdown-item" href="pages/support.php">Technical Support</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/coverage.php">Coverage Areas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/reviews.php">Reviews</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">

        <!-- Sliding background images -->
        <div class="background-slider">
            <img src="images/testim1.avif" alt="">
            <img src="images/testim3.avif" alt="">
            <img src="images/testim2.avif" alt="">
            <img src="images/testim5.avif" alt="">
            <!-- Repeat for smooth looping -->
            <img src="images/testim4.avif" alt="">
            <img src="images/testim5.avif" alt="">
            <img src="images/testim4.avif" alt="">
        </div>

        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-md-6" data-aos="zoom-in" duration="1000">
                    <h1 class="hero-title">Seamless Connectivity for Every Need</h1>
                    <p class="hero-text">Stay connected with fast, secure, and affordable WiFi plans tailored for student hostels, homes, and businesses across Uganda.</p>
                    <div class="hero-buttons">
                        <a href="#packages" class="btn btn-primary btn-lg">View Packages</a>
                        <a href="tel:0744766410" class="btn btn-primary btn-lg" style="background: transparent; border: 1px solid white;">
                            <i class="fas fa-phone-alt me-2" style="color: #fff;"></i>Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <!-- Packages Section -->
    <section id="packages" class="featured-properties-carousel">
        <div class="container">
            <div class="section-header text-center text-black" data-aos="fade-up">
                <h2>Our WiFi Packages</h2>
                <p>Choose the perfect plan that fits your internet needs and budget</p>
            </div>

            <div class="row">
                <!-- Daily Package -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="package-card">
                        <h4>Daily Pass</h4>
                        <div class="package-price">1,000 <small>UGX</small></div>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> 24-hour unlimited access</li>
                            <li><i class="fas fa-check text-success"></i> Up to 1 device</li>
                            <li><i class="fas fa-check text-success"></i> Perfect for travelers</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Choose Plan</button>
                    </div>
                </div>

                <!-- Weekly Package -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="package-card">
                        <h4>Weekly Plan</h4>
                        <div class="package-price">6,000 <small>UGX</small></div>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> 7 days unlimited access</li>
                            <li><i class="fas fa-check text-success"></i> Up to 1 device</li>
                            <li><i class="fas fa-check text-success"></i> Ideal for short stays</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Choose Plan</button>
                    </div>
                </div>

                <!-- Monthly Package -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="package-card featured">
                        <div class="badge bg-success position-absolute top-0 start-50 translate-middle">Most Popular</div>
                        <h4>Monthly Plan</h4>
                        <div class="package-price">20,000 <small>UGX</small></div>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> 30 days unlimited access</li>
                            <li><i class="fas fa-check text-success"></i> Up to 2 devices</li>
                            <li><i class="fas fa-check text-success"></i> Priority support</li>
                        </ul>
                        <button class="btn btn-success w-100">Choose Plan</button>
                    </div>
                </div>

                <!-- Semester Package -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="package-card">
                        <h4>Semester Plan</h4>
                        <div class="package-price">50,000 <small>UGX</small></div>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> 105 days unlimited access</li>
                            <li><i class="fas fa-check text-success"></i> Up to 3 devices</li>
                            <li><i class="fas fa-check text-success"></i> Best value for students</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Choose Plan</button>
                    </div>
                </div>
            </div>

            <!-- Special Packages -->
            <div class="row mt-5">
                <div class="section-header text-center text-black" data-aos="fade-up">
                    <h3>special Packages</h3>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="package-card">
                        <h4><i class="fas fa-users"></i> Family Bundle</h4>
                        <div class="package-price">50,000 <small>UGX</small></div>
                        <p class="text-muted">per month</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Up to 100 Mbps shared speed</li>
                            <li><i class="fas fa-check text-success"></i> maximum 4 devices</li>
                            <li><i class="fas fa-check text-success"></i> Parental controls included</li>
                            <li style="color: red;"><i class="fas fa-check text-success"></i> Free installation</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Press Order</button>
                    </div>
                </div>

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="package-card">
                        <h4><i class="fas fa-briefcase"></i> Business Pro</h4>
                        <div class="package-price">Custom <small>Pricing</small></div>
                        <p class="text-muted">Enterprise Solutions</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Dedicated bandwidth</li>
                            <li><i class="fas fa-check text-success"></i> 24/7 technical support</li>
                            <li><i class="fas fa-check text-success"></i> On-site installation</li>
                            <li>.</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Get Quote</button>
                    </div>
                </div>
            </div>
        </div>
    </section> 

    <!-- Services Section -->
    <!-- <section id="services" class="services-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Our WiFi Services</h2>
                <p>Comprehensive internet solutions tailored to your connectivity needs</p>
            </div>
            <div class="row">
                Home WiFi
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3>Home WiFi</h3>
                        <p>Reliable high-speed internet for your home, perfect for streaming, gaming, and working from home.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check"></i> Up to 100 Mbps Speed</li>
                            <li><i class="fas fa-check"></i> Unlimited Data</li>
                            <li><i class="fas fa-check"></i> Multiple Device Support</li>
                        </ul>
                        <a href="pages/packages.php" class="btn btn-outline-primary">View Plans</a>
                    </div>
                </div>

                Business WiFi
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Business WiFi</h3>
                        <p>Enterprise-grade internet solutions with priority support and guaranteed uptime for your business.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check"></i> Dedicated Bandwidth</li>
                            <li><i class="fas fa-check"></i> 24/7 Business Support</li>
                            <li><i class="fas fa-check"></i> Custom Solutions</li>
                        </ul>
                        <a href="pages/business.php" class="btn btn-outline-primary">Learn More</a>
                    </div>
                </div>

                Student WiFi
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Student WiFi</h3>
                        <p>Affordable internet packages designed specifically for students and educational institutions.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check"></i> Student-Friendly Pricing</li>
                            <li><i class="fas fa-check"></i> Educational Content Priority</li>
                            <li><i class="fas fa-check"></i> Semester Packages</li>
                        </ul>
                        <a href="pages/student.php" class="btn btn-outline-primary">Explore Options</a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Network Stats -->
    <!-- <section class="network-stats">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <span class="stat-number counter">2500</span>
                    <p>Active Users</p>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <span class="stat-number counter">99.9</span>%
                    <p>Network Uptime</p>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <span class="stat-number counter">50</span>+
                    <p>Coverage Areas</p>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <span class="stat-number counter">24</span>/7
                    <p>Customer Support</p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Coverage Areas -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="zoom-in">
                    <div class="coverage-map text-center">
                        <i class="fas fa-map-marked-alt" style="font-size: 5rem; margin-bottom: 20px;"></i>
                        <h3>Wide Coverage Across Kampala</h3>
                        <p>Our network covers major areas including Nabisunsa, Ntinda, Kibuli, Banda, and surrounding areas.</p>
                        <div class="row mt-4">
                            <div class="col-6">
                                <h4>50+</h4>
                                <p>Hotspot Locations</p>
                            </div>
                            <div class="col-6">
                                <h4>25km</h4>
                                <p>Coverage Radius</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="about-content">
                        <h2>Why Choose FastNetUG?</h2>
                        <p>We're Uganda's fastest-growing WiFi provider, committed to delivering reliable, high-speed internet access to students, families, and businesses across Kampala.</p>
                        <p>Our network is built on cutting-edge technology with multiple redundancies to ensure you stay connected when it matters most.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="fas fa-bolt feature-icon"></i>
                                    <h4>Lightning Fast</h4>
                                    <p>Up to 100 Mbps speeds for seamless browsing and streaming</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt feature-icon"></i>
                                    <h4>Secure Network</h4>
                                    <p>Advanced security protocols to protect your data</p>
                                </div>
                            </div>
                        </div>
                        <a href="pages/about.php" class="btn btn-primary" style="width: 80%; margin-left: 10%;">Learn More ></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>What Our Customers Say</h2>
                <p>Hear from satisfied customers who trust FastNetUG for their internet needs</p>
            </div>
            <div class="testimonial-slider" data-aos="fade-up">
                <div class="row">
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"FastNetUG saved my semester! Their WiFi is incredibly fast and reliable. I can stream lectures, download research papers, and video call my family without any interruptions."</p>
                            <div class="testimonial-author">
                                <img src="images/developer.jpg" alt="Student" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Sarah Namuli</h5>
                                    <span>Makerere University Student</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"As a family of six, we needed reliable internet for everyone. FastNetUG's family bundle gives us amazing speeds and their customer service is exceptional!"</p>
                            <div class="testimonial-author">
                                <img src="images/micheal.jpg" alt="Family Customer" class="rounded-circle">
                                <div class="author-info">
                                    <h5>David Ssekandi</h5>
                                    <span>Family Plan Customer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"Our business depends on reliable internet. FastNetUG's business package has been a game-changer with 99.9% uptime and lightning-fast speeds."</p>
                            <div class="testimonial-author">
                                <img src="images/sara.jpg" alt="Business Customer" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Grace Mukisa</h5>
                                    <span>Small Business Owner</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="pages/reviews.php" class="btn btn-outline-primary">View All Reviews</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center" data-aos="zoom-in" duration="1000">
                <div class="col-lg-8">
                    <h2>Ready to Get Connected?</h2>
                    <p>Join thousands of satisfied customers enjoying fast, reliable internet across Kampala. Get connected today!</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="tel:0744766410" class="btn btn-light btn-lg me-2" title="Call Us">
                        <i class="fas fa-phone-alt"></i> Call Now
                    </a>
                    or
                    <a href="https://wa.me/256744766410" class="btn btn-success btn-lg" title="Chat on WhatsApp" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
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
                        <div class="footer-logo mb-3">
                            <h3>FastNetUG <i class="fas fa-wifi"></i></h3>
                        </div>
                        <p>Uganda's premier WiFi service provider delivering fast, reliable internet to homes, businesses, and students.</p>
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
                            <li><a href="#">Home</a></li>
                            <li><a href="pages/about.php">About Us</a></li>
                            <li><a href="pages/coverage.php">Coverage Areas</a></li>
                            <li><a href="pages/contact.php">Contact Us</a></li>
                            <li><a href="pages/reviews.php">Reviews</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="footer-links">
                        <h4>Our Services</h4>
                        <ul>
                            <li><a href="pages/packages.php">WiFi Packages</a></li>
                            <li><a href="pages/installation.php">Installation</a></li>
                            <li><a href="pages/support.php">Technical Support</a></li>
                            <li><a href="pages/business.php">Business Solutions</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="footer-contact">
                        <h4>Contact Info</h4>
                        <p>
                            <i class="fas fa-map-marker-alt"></i> Makerere University Area<br>
                            Wandegeya, Kampala<br>
                            <i class="fas fa-phone"></i> +256 744 766 410<br>
                            <i class="fas fa-envelope"></i> info@fastnetug.com<br>
                            <i class="fas fa-wifi"></i> Network: FastNetUG
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row d-flex align-items-center justify-content-between">
                    <div class="col-md-5 text-md-start text-center">
                        <p class="copyright">© 2025 FastNetUG. All Rights Reserved.</p>
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

    <!-- Speed Test Modal -->
    <div class="modal fade" id="speedTestModal" tabindex="-1" aria-labelledby="speedTestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="speedTestModalLabel">Test Your Speed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="speed-test-widget">
                        <div class="speed-gauge">
                            <h3 id="speedResult">Click to Test</h3>
                            <button class="btn btn-primary" id="startSpeedTest">
                                <i class="fas fa-play"></i> Start Speed Test
                            </button>
                        </div>
                        <div class="speed-details mt-4">
                            <div class="row">
                                <div class="col-4">
                                    <h6>Download</h6>
                                    <span id="downloadSpeed">-- Mbps</span>
                                </div>
                                <div class="col-4">
                                    <h6>Upload</h6>
                                    <span id="uploadSpeed">-- Mbps</span>
                                </div>
                                <div class="col-4">
                                    <h6>Ping</h6>
                                    <span id="pingSpeed">-- ms</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="shareSpeed">Share Results</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Selection Modal -->
    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageModalLabel">Select Your Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="packageForm">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="customerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="customerPhone" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerLocation" class="form-label">Location</label>
                            <select class="form-select" id="customerLocation" required>
                                <option value="">Select your area</option>
                                <option value="Makerere">Makerere</option>
                                <option value="Wandegeya">Wandegeya</option>
                                <option value="Kikoni">Kikoni</option>
                                <option value="Ntinda">Ntinda</option>
                                <option value="Najera">Najera</option>
                                <option value="Other">Other (Specify in notes)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="selectedPackage" class="form-label">Choose Package</label>
                            <select class="form-select" id="selectedPackage" required>
                                <option value="">Select a package</option>
                                <option value="daily">Daily Pass - 1,000 UGX</option>
                                <option value="weekly">Weekly Plan - 6,000 UGX</option>
                                <option value="monthly">Monthly Plan - 20,000 UGX</option>
                                <option value="semester">Semester Plan - 50,000 UGX</option>
                                <option value="family">Family Bundle - 35,000 UGX</option>
                                <option value="business">Business Pro - Custom</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="customerNotes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="customerNotes" rows="3" placeholder="Any special requirements or questions?"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitPackageRequest">Submit Request</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <!-- Floating Action Buttons -->
    <div class="floating-buttons">
        <a href="#" class="float-btn speed-test-btn" data-bs-toggle="modal" data-bs-target="#speedTestModal" title="Test Speed">
            <i class="fas fa-tachometer-alt"></i>
        </a>
        <a href="https://wa.me/256744766410" class="float-btn whatsapp-btn" title="WhatsApp" target="_blank">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="scripts/main.js"></script>
    <script src="scripts/services.js"></script>
    <script src="scripts/contact.js"></script>
    <script src="scripts/consultation.js"></script>


    <script>
        // Initialize AOS
        AOS.init();

        // Package selection
        $('.package-card button').on('click', function() {
            const packageName = $(this).closest('.package-card').find('h3').text();
            $('#selectedPackage').val(packageName.toLowerCase().replace(' ', ''));
            $('#packageModal').modal('show');
        });

        // Speed Test Simulation
        $('#startSpeedTest').on('click', function() {
            const btn = $(this);
            const originalText = btn.html();

            btn.html('<i class="fas fa-spinner fa-spin"></i> Testing...').prop('disabled', true);

            // Simulate speed test
            setTimeout(() => {
                const downloadSpeed = (Math.random() * 50 + 25).toFixed(1);
                const uploadSpeed = (Math.random() * 20 + 10).toFixed(1);
                const ping = Math.floor(Math.random() * 50 + 10);

                $('#downloadSpeed').text(downloadSpeed + ' Mbps');
                $('#uploadSpeed').text(uploadSpeed + ' Mbps');
                $('#pingSpeed').text(ping + ' ms');
                $('#speedResult').text('Test Complete!');

                btn.html(originalText).prop('disabled', false);
            }, 3000);
        });

        // Package form submission
        $('#submitPackageRequest').on('click', function() {
            const name = $('#customerName').val();
            const phone = $('#customerPhone').val();
            const location = $('#customerLocation').val();
            const package = $('#selectedPackage option:selected').text();

            if (name && phone && location && package) {
                // In a real application, you would send this data to your server
                alert('Thank you! We will contact you shortly to complete your WiFi setup.');
                $('#packageModal').modal('hide');
                $('#packageForm')[0].reset();
            } else {
                alert('Please fill in all required fields.');
            }
        });
    </script>

</body>

</html>