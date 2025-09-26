<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coverage Areas | FastNetUG</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/coverage.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../images/FastNetUGbg.png" alt="FastNetUG Logo" class="logo-text">
                <!-- <span class="logo-text">FastNetUG</span> <i class="fas fa-wifi wifi-animation"></i> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Coverage Areas</a>
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
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section coverage-hero">
        <div class="container">
            <div class="row align-items-center h-100">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="hero-title">Our Coverage Areas</h1>
                    <p class="hero-text">Find your nearest hotspot and get connected today!</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <h3>25+</h3>
                            <p>Hotspot Locations</p>
                        </div>
                        <div class="stat-item">
                            <h3>10+</h3>
                            <p>Student Hostels</p>
                        </div>
                        <div class="stat-item">
                            <h3>5+</h3>
                            <p>Family Homes</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="coverage-map-container">
                        <div class="coverage-map">
                            <i class="fas fa-map-marked-alt"></i>
                            <div class="coverage-points">
                                <div class="point point-1" data-area="Nabisunsa"></div>
                                <div class="point point-2" data-area="Banda"></div>
                                <div class="point point-3" data-area="Ntinda"></div>
                                <div class="point point-4" data-area="Nakawa"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coverage Areas Section -->
    <section class="coverage-areas-section">
        <div class="container">
            <!-- Removed section header -->
             <div class="search-box">
            <input type="text" id="areaSearch" placeholder="Search areas or hostels...">
            <i class="fas fa-search"></i>
        </div>

            <div class="row">
                <!-- Nabisunsa Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Nabisunsa</h3>
                            <span class="coverage-badge">Full Coverage</span>
                        </div>
                        <div class="area-content">
                            <p>Our main service area with extensive network infrastructure and 24/7 support.</p>
                            <div class="area-features">
                                <span class="feature-tag">Fiber Backbone</span>
                                <span class="feature-tag">99.9% Uptime</span>
                                <span class="feature-tag">24/7 Support</span>
                            </div>
                            <div class="area-contact">
                                <p><strong>Area Contact:</strong> Alex Namurinda</p>
                                <div class="contact-buttons">
                                    <a href="tel:0756585769" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256744766410" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banda Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Banda</h3>
                            <span class="coverage-badge">High Coverage</span>
                        </div>
                        <div class="area-content">
                            <p>Strong coverage in Banda with multiple hotspots serving students and families.</p>
                            <div class="area-features">
                                <span class="feature-tag">Multiple Hotspots</span>
                                <span class="feature-tag">Student Focused</span>
                                <span class="feature-tag">Family Plans</span>
                            </div>
                            <div class="area-contact">
                                <p><strong>Area Contact:</strong> Kasumba Mark</p>
                                <div class="contact-buttons">
                                    <a href="tel:0752090648" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256752090648" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ntinda Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Ntinda</h3>
                            <span class="coverage-badge expanding">Expanding</span>
                        </div>
                        <div class="area-content">
                            <p>Growing coverage in Ntinda with new hotspots and improved infrastructure.</p>
                            <div class="area-features">
                                <span class="feature-tag">New Hotspots</span>
                                <span class="feature-tag">Growing Network</span>
                                <span class="feature-tag">Business Plans</span>
                            </div>
                            <div class="area-contact">
                                <p><strong>Area Contact:</strong> Alex Namurinda</p>
                                <div class="contact-buttons">
                                    <a href="tel:0756585769" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256744766410" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nakawa Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Nakawa</h3>
                            <span class="coverage-badge">Good Coverage</span>
                        </div>
                        <div class="area-content">
                            <p>Reliable coverage in Nakawa area with focus on educational institutions.</p>
                            <div class="area-features">
                                <span class="feature-tag">University Areas</span>
                                <span class="feature-tag">Student Packages</span>
                                <span class="feature-tag">Reliable Service</span>
                            </div>
                            <div class="area-contact">
                                <p><strong>Area Contact:</strong> Kasumba Mark</p>
                                <div class="contact-buttons">
                                    <a href="tel:0752090648" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256752090648" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Student Hostels Section -->
    <section class="hostels-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Student Hostels Connected</h2>
            </div>

            <div class="row">
                <!-- Namuli Hostel -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="hostel-card">
                        <div class="hostel-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>Namuli Hostel</h4>
                        <p>Our first and flagship hostel with complete WiFi coverage across all wings.</p>
                        <div class="hostel-features">
                            <span class="feature"><i class="fas fa-check"></i> Full Coverage</span>
                            <span class="feature"><i class="fas fa-check"></i> 70+ Students</span>
                            <span class="feature"><i class="fas fa-check"></i> 24/7 Support</span>
                        </div>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Kasumba Mark</p>
                            <div class="agent-contact">
                                <a href="tel:0752090648" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256752090648" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Herman Hostel -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="hostel-card">
                        <div class="hostel-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>Herman Hostel</h4>
                        <p>Modern hostel with high-speed internet and dedicated student support.</p>
                        <div class="hostel-features">
                            <span class="feature"><i class="fas fa-check"></i> High Speed</span>
                            <span class="feature"><i class="fas fa-check"></i> 50+ Students</span>
                            <span class="feature"><i class="fas fa-check"></i> Fast Support</span>
                        </div>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Prossie M</p>
                            <div class="agent-contact">
                                <a href="tel:0757951874" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256757951874" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kasalita Hostel -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="hostel-card">
                        <div class="hostel-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>Kasalita Hostel</h4>
                        <p>Popular hostel with reliable internet and affordable student packages.</p>
                        <div class="hostel-features">
                            <span class="feature"><i class="fas fa-check"></i> Affordable</span>
                            <span class="feature"><i class="fas fa-check"></i> 120+ Students</span>
                            <span class="feature"><i class="fas fa-check"></i> Reliable</span>
                        </div>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Achan K</p>
                            <div class="agent-contact">
                                <a href="tel:0775580790" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256775580790" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lower Herman Hostel -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="hostel-card">
                        <div class="hostel-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>Lower Herman</h4>
                        <p>Budget-friendly hostel with good internet coverage and student-focused services.</p>
                        <div class="hostel-features">
                            <span class="feature"><i class="fas fa-check"></i> Budget Plans</span>
                            <span class="feature"><i class="fas fa-check"></i> 10+ Students</span>
                            <span class="feature"><i class="fas fa-check"></i> Good Coverage</span>
                        </div>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Michael Kunta</p>
                            <div class="agent-contact">
                                <a href="tel:0708620852" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256708620852" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Hostel -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="hostel-card">
                        <div class="hostel-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>Chairman's Hostel</h4>
                        <p>Recently connected hostel with modern infrastructure and fast speeds.</p>
                        <div class="hostel-features">
                            <span class="feature"><i class="fas fa-check"></i> Modern</span>
                            <span class="feature"><i class="fas fa-check"></i> 80+ Students</span>
                            <span class="feature"><i class="fas fa-check"></i> Fast Speed</span>
                        </div>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Habib Ssemakula</p>
                            <div class="agent-contact">
                                <a href="tel:0767239404" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256767239404" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More Hostels -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="hostel-card coming-soon">
                        <div class="hostel-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h4>More Hostels</h4>
                        <p>We're continuously expanding to serve more student hostels across Kampala.</p>
                      
                        <div class="hostel-agent">
                            <p><strong>For New Hostels:</strong> Contact Us</p>
                            <div class="agent-contact">
                                <a href="tel:0756585769" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256744766410" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Family Connections Section -->
    <section class="family-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Family Connections</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="family-card">
                        <div class="family-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h4>Lilian's Family</h4>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Banda Area</p>
                        <div class="testimonial">
                            <p><em>"FastNetUG has transformed our home internet experience. The whole family stays connected!"</em></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="family-card">
                        <div class="family-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h4>Judith's Family</h4>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Nabisunsa Area</p>
                        
                        <div class="testimonial">
                            <p><em>"Excellent service! Our kids can study online while we work from home seamlessly."</em></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="family-card join-families">
                        <div class="family-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h4>Join Our Family Network</h4>
                        <p>Become part of our growing family of satisfied customers with reliable home internet.</p>
                       
                        <div>
                            <a href="../contact.php" class="btn btn-primary">Get Quote</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center justify-content-between text-center text-lg-start" data-aos="zoom-in">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h2 class="cta-title">Ready to Get Connected in Your Area?</h2>
                    <p class="cta-subtitle">Find your nearest FastNetUG agent or contact our support team to get started with high-speed internet today!</p>
                </div>
                <div class="col-lg-5 d-flex flex-column flex-sm-row justify-content-center justify-content-lg-end gap-3">
                    <a href="tel:0756585769" class="btn btn-light btn-lg">
                        <i class="fas fa-phone-alt"></i> Call Now
                    </a>
                    <a href="https://wa.me/256744766410" class="btn btn-success btn-lg" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp
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
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="../index.php">Home</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="#">Coverage Areas</a></li>
                            <li><a href="contact.php">Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="footer-links">
                        <h4>Services</h4>
                        <ul>
                            <li><a href="../index.php#packages">WiFi Packages</a></li>
                            <li><a href="contact.php">Installations</a></li>
                            <li><a href="contact.php">Web Development</a></li>
                            <li><a href="contact.php">Technical Support</a></li>
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
    <script src="../scripts/coverage.js"></script>
</body>

</html>