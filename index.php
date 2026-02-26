<?php
?>
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
                <img src="images/FastNetUGbg.png" alt="FastNetUG Logo" class="logo-text">
                <!-- <span class="logo-text">FastNetUG</span> <i class="fas fa-wifi wifi-animation"></i> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            Services <i class="fas fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="pages/packages.php">WiFi Packages</a></li>
                            <li><a class="dropdown-item" href="pages/installation.php">Installation Services</a></li>
                            <li><a class="dropdown-item" href="pages/support.php">Technical Support</a></li>
                        </ul>
                    </li> -->
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
                        <!-- <a href="#packages" class="btn btn-primary btn-lg">Buy Voucher</a> -->
                        <a href="https://fastnetuganda.centipidtechnologies.com/buy/packages" class="btn btn-primary btn-lg">Buy Voucher</a>                        
                        <a href="tel:0756585769" class="btn btn-primary btn-lg" style="background: transparent; border: 1px solid white;">
                            <i class="fas fa-phone-alt me-2" style="color: #fff;"></i>Call Us Now
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
                        <button class="btn btn-outline-primary w-100">Buy Now</button>
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
                        <button class="btn btn-outline-primary w-100">Buy Now</button>
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
                            <li><i class="fas fa-check text-success"></i> Up to 2 devices</li>
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
                        <p>Our network covers major areas including Nabisunsa, Banda, Ntinda, Kibuli, and surrounding areas.</p>
                        <div class="row mt-4">
                            <div class="col-6">
                                <h4>25+</h4>
                                <p>Hotspot Locations</p>
                            </div>
                            <div class="col-6">
                                <h4>25km</h4>
                                <p>Coverage Radius</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex justify-content-center">
                    <div class="about-content text-center w-100 px-3 px-md-5">
                        <h2>Why Choose FastNetUG?</h2>
                        <p style="text-align:justify;">We're Uganda's fastest-growing WiFi provider, committed to delivering reliable, high-speed internet access to students, families, and businesses across Kampala and beyond.</p>
                        <p style="text-align:justify;">Our network is backed by cutting-edge technology and round-the-clock support. With our 24/7 customer service team, you’ll get the fastest assistance whenever you need it — day or night.</p>

                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 mb-4">
                                <div class="feature-item">
                                    <i class="fas fa-bolt feature-icon"></i>
                                    <h4>Fast Speeds</h4>
                                    <p>Up to 100 Mbps speeds for seamless browsing and streaming</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-4">
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt feature-icon"></i>
                                    <h4>Secure Network</h4>
                                    <p>Advanced security protocols to protect your data</p>
                                </div>
                            </div>
                        </div>

                        <a href="pages/about.php" class="btn btn-primary w-75 mx-auto">Learn More ></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>What Our Clients Say</h2>
                <!-- <p>Hear from satisfied customers who trust FastNetUG for their internet needs</p> -->
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

                            </div>
                            <p class="testimonial-text">"FastNetUG saved my semester! Their WiFi is incredibly fast and reliable. I can stream lectures, download research papers, and video call my family without any interruptions."</p>
                            <div class="testimonial-author">
                                <img src="images/developer.jpg" alt="Student" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Student</h5>
                                    <span>Kyambogo University</span>
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
                            <p class="testimonial-text">"As a family of five, we needed reliable internet for everyone. FastNetUG's family bundle gives us amazing speeds and their customer service is exceptional!"</p>
                            <div class="testimonial-author">
                                <img src="images/sara.jpg" alt="Family Customer" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Lilian T</h5>
                                    <span>Family Package</span>
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
                            </div>
                            <p class="testimonial-text">"Our business depends on reliable internet. FastNetUG's business package has been a game-changer with 99% uptime and lightning-fast speeds."</p>
                            <div class="testimonial-author">
                                <img src="images/profile_pic.png" alt="Business Customer" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Operations Manager</h5>
                                    <span>Nabisunsa savings sacco</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5" style="top: 20px;">
                    <a href="pages/reviews.php" class="btn btn-outline-primary">View All Reviews</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center justify-content-between text-center text-lg-start" data-aos="zoom-in" data-aos-duration="1000">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h2 class="cta-title">Ready to Get Connected?</h2>
                    <p class="cta-subtitle">Join thousands of satisfied customers enjoying fast, reliable internet across Kampala. Get connected today!</p>
                </div>
                <div class="col-lg-5 d-flex flex-column flex-sm-row justify-content-center justify-content-lg-end gap-3">
                    <a href="tel:0756585769" class="btn btn-light btn-lg" title="Call Us">
                        <i class="fas fa-phone-alt"></i>
                        <span>Call Now</span>
                    </a>
                    <a href="https://wa.me/256744766410" class="btn btn-success btn-lg" title="Chat on WhatsApp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row text-center text-md-start gy-4">
                <div class="col-md-3">
                    <div class="footer-info">
                        <div class="footer-logo mb-3">
                            <h3>FastNetUG <i class="fas fa-wifi"></i></h3>
                        </div>
                        <p>Follow us on all our socials</p>
                        <div class="social-links d-flex justify-content-center justify-content-md-start">
                            <a href=""><i class="fab fa-facebook-f"></i></a>
                            <a href=""><i class="fab fa-twitter"></i></a>
                            <a href=""><i class="fab fa-instagram"></i></a>
                            <a href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="pages/about.php">About Us</a></li>
                            <li><a href="pages/coverage.php">Coverage Areas</a></li>
                            <li><a href="pages/contact.php">Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="footer-links">
                        <h4>Other Services</h4>
                        <ul>
                            <li><a href="#">WiFi Packages</a></li>
                            <li><a href="pages/contact.php">Installations</a></li>
                            <li><a href="pages/contact.php">Web development</a></li>
                            <li><a href="pages/contact.php">Telecom services</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="footer-contact">
                        <h4>Contact Info</h4>
                        <p><i class="fas fa-map-marker-alt"></i> Nabisunsa Close<br>Banda, Kampala</p>
                        <p><i class="fas fa-phone"></i> +256 744 766 410</p>
                        <p><i class="fas fa-envelope"></i> info@fastnetug.com</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom mt-5">
            <div class="container">
                <div class="row align-items-center justify-content-between text-center text-md-start">
                    <div class="col-md-6">
                        <p class="copyright">© 2025 FastNetUG. All Rights Reserved.</p>
                    </div>

                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="footer-links-bottom">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Speed Test Modal -->
    <div class="modal fade" id="speedTestModal" tabindex="-1" aria-labelledby="speedTestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="speedTestModalLabel">Test Your Internet Speed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <iframe
                        src="https://librespeed.org/"
                        width="100%"
                        height="450"
                        style="border: none;"
                        title="LibreSpeed Internet Speed Test"
                        loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Selection Modal -->
    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageModalLabel">Enter your phone number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic message -->
                    <div id="selectedPackageAlert" class="alert alert-info fw-bold text-center"></div>
                    <form id="packageForm">
                        <input type="hidden" id="selectedPackageName" />
                        <input type="hidden" id="selectedPackageAmount" />
                        <div class="mb-3">
                            <input type="tel" class="form-control" id="customerPhone" placeholder="- enter phone number -" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" id="customerLocation" required>
                                <option value="">Select your location</option>
                                <option value="Kikoni">Nabisunsa Close</option>
                                <option value="Ntinda">Kiwanga</option>
                                <option value="Najera">Other</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitPackageRequest">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

    <script src="https://cdn.jsdelivr.net/gh/librespeed/speedtest/speedtest.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="scripts/main.js"></script>
    <script src="scripts/services.js"></script>
    <script src="scripts/contact.js"></script>
    <script src="scripts/consultation.js"></script>


    <script>
        // Initialize AOS
        AOS.init();

        // Mapping of package names to price
        const packageMap = {
            "Daily Pass": "1,000 UGX",
            "Weekly Plan": "6,000 UGX",
            "Monthly Plan": "20,000 UGX",
            "Semester Plan": "50,000 UGX",
            "Family Bundle": "50,000 UGX/month",
            "Business Pro": "Custom Pricing"
        };

        // Show modal and inject package info
        $('.package-card button').on('click', function() {
            const card = $(this).closest('.package-card');
            const packageName = card.find('h4').text().trim();
            const price = packageMap[packageName] || 'Custom Pricing';

            $('#selectedPackageName').val(packageName);
            $('#selectedPackageAmount').val(price);
            $('#selectedPackageAlert').html(`You have chosen <strong>${packageName}</strong> at <strong>${price}</strong>.`);

            $('#packageModal').modal('show');
        });

        // Submit handler
        $('#submitPackageRequest').on('click', function() {
            const phone = $('#customerPhone').val().trim();
            const location = $('#customerLocation').val();
            const packageName = $('#selectedPackageName').val();
            const amount = $('#selectedPackageAmount').val();

            // Phone validation
            const phoneRegex = /^(?:\+256|0)?7\d{8}$/;
            if (!phone) {
                alert("Please enter your phone number.");
                return;
            }
            if (!phoneRegex.test(phone)) {
                alert("Please enter a valid Ugandan phone number.");
                return;
            }

            if (!location) {
                alert("Please select your location.");
                return;
            }

            // Success message
            alert("Connection request submitted. We will contact you soon.");

            // Reset form
            $('#packageModal').modal('hide');
            $('#packageForm')[0].reset();
        });
    </script>

    <script>
        const s = new Speedtest();

        $('#startSpeedTest').on('click', function() {
            const btn = $(this);
            btn.html('<i class="fas fa-spinner fa-spin"></i> Testing...').prop('disabled', true);
            $('#speedResult').text('Testing...');

            // Reset display
            $('#downloadSpeed').text('-- Mbps');
            $('#uploadSpeed').text('-- Mbps');
            $('#pingSpeed').text('-- ms');

            s.onupdate = function(data) {
                if (data.download) $('#downloadSpeed').text((data.download / 1e6).toFixed(2) + ' Mbps');
                if (data.upload) $('#uploadSpeed').text((data.upload / 1e6).toFixed(2) + ' Mbps');
                if (data.ping) $('#pingSpeed').text(data.ping.toFixed(0) + ' ms');
            };

            s.onend = function(aborted) {
                $('#speedResult').text('Test Complete!');
                btn.html('<i class="fas fa-play"></i> Start Speed Test').prop('disabled', false);
            };

            s.start(); // Run the test
        });
    </script>


</body>

</html>