<?php
$basePath = '../';
$activePage = 'coverage';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coverage Areas | FastNetUG</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/coverage.css" rel="stylesheet">
</head>

<body>
    <?php include '../includes/nav.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section coverage-hero">
        <div class="container">
            <div class="row align-items-center" style="min-height: 100vh;">
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
                <div class="col-lg-6 d-flex justify-content-center mt-5 mt-lg-0" data-aos="fade-left">
                    <div class="coverage-map-container">
                        <div class="coverage-map">
                            <i class="fas fa-map-marked-alt"></i>
                            <div class="coverage-points">
                                <div class="point point-1" data-area="Nabisunsa"></div>
                                <div class="point point-2" data-area="Kiwanga"></div>
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
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Service Zones</h2>
                <p>Tap an area to find your local agent</p>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="areaSearch" placeholder="Search areas or hostels...">
            </div>

            <div class="row">
                <!-- Nabisunsa Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Nabisunsa Close</h3>
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
                                <p><strong>Area Contact:</strong> Moses</p>
                                <div class="contact-buttons">
                                    <a href="tel:0780393671" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256780393671" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kiwanga Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Kiwanga</h3>
                            <span class="coverage-badge">High Coverage</span>
                        </div>
                        <div class="area-content">
                            <p>Strong coverage in Kiwanga with multiple hotspots serving Business units and families.</p>
                            <div class="area-features">
                                <span class="feature-tag">Multiple Hotspots</span>
                                <span class="feature-tag">Community based connection</span>
                                <span class="feature-tag">Family Plans</span>
                            </div>
                            <div class="area-contact">
                                <p><strong>Area Contact:</strong> Kasumba Mark</p>
                                <div class="contact-buttons">
                                    <a href="tel:0756585769" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256756585769" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Arua Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Arua</h3>
                            <span class="coverage-badge expanding">Expanding</span>
                        </div>
                        <div class="area-content">
                            <p>Growing coverage in Arua with new hotspots and improved infrastructure.</p>
                            <div class="area-features">
                                <span class="feature-tag">New Hotspots</span>
                                <span class="feature-tag">Growing Network</span>
                                <span class="feature-tag">Business Plans</span>
                            </div>
                            <div class="area-contact">
                                <p><strong>Area Contact:</strong> Eng. Alex</p>
                                <div class="contact-buttons">
                                    <a href="tel:0745685794" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256745685794" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ibanda Area -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="area-card">
                        <div class="area-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Ibanda</h3>
                            <span class="coverage-badge expanding">Pipeline</span>
                        </div>
                        <div class="area-content">
                            <p>Reliable coverage with focus on educational institutions.</p>
                            <div class="area-features">
                                <span class="feature-tag">New Hotspots</span>
                                <span class="feature-tag">Business Plans</span>
                            </div>
                            <div class="area-contact">
                                <p><strong>Area Contact:</strong> Eng. Alex</p>
                                <div class="contact-buttons">
                                    <a href="tel:0745685794" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="https://wa.me/256745685794" class="btn btn-sm btn-success" target="_blank">
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
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="hostel-card">
                        <div class="hostel-icon"><i class="fas fa-building"></i></div>
                        <h4>Namuli Hostel</h4>
                        <p>Our first and flagship hostel with complete WiFi coverage across all wings.</p>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Kasumba Mark</p>
                            <div class="agent-contact">
                                <a href="tel:0756585769" class="btn btn-sm btn-outline-primary" title="Call">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256756585769" class="btn btn-sm btn-outline-success" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="hostel-card">
                        <div class="hostel-icon"><i class="fas fa-building"></i></div>
                        <h4>Harmony Hostels</h4>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Erick</p>
                            <div class="agent-contact">
                                <a href="tel:0744115919" class="btn btn-sm btn-outline-primary" title="Call">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256744115919" class="btn btn-sm btn-outline-success" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="hostel-card">
                        <div class="hostel-icon"><i class="fas fa-building"></i></div>
                        <h4>Opp-Kasalita Hostel</h4>
                        <div class="hostel-agent">
                            <p><strong>Hostel Agent:</strong> Wimsey</p>
                            <div class="agent-contact">
                                <a href="tel:0775580790" class="btn btn-sm btn-outline-primary" title="Call">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256775580790" class="btn btn-sm btn-outline-success" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="hostel-card">
                        <div class="hostel-icon"><i class="fas fa-building"></i></div>
                        <h4>Lower wing</h4>
                        <div class="hostel-agent">
                            <p><strong>Section Agent:</strong> Moses</p>
                            <div class="agent-contact">
                                <a href="tel:0780393671" class="btn btn-sm btn-outline-primary" title="Call">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256780393671" class="btn btn-sm btn-outline-success" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="hostel-card">
                        <div class="hostel-icon"><i class="fas fa-building"></i></div>
                        <h4>Rentals</h4>
                        <div class="hostel-agent">
                            <p><strong>Project Agent:</strong> Andy</p>
                            <div class="agent-contact">
                                <a href="tel:0743929780" class="btn btn-sm btn-outline-primary" title="Call">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256743929780" class="btn btn-sm btn-outline-success" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="hostel-card coming-soon">
                        <div class="hostel-icon"><i class="fas fa-plus-circle"></i></div>
                        <h4>More Hostels</h4>
                        <p>We're continuously expanding to serve more student hostels across Kampala.</p>
                        <div class="hostel-agent">
                            <p><strong>For New Hostels:</strong> Contact Us</p>
                            <div class="agent-contact">
                                <a href="tel:0756585769" class="btn btn-sm btn-outline-primary" title="Call">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <a href="https://wa.me/256756585769" class="btn btn-sm btn-outline-success" target="_blank" title="WhatsApp">
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
                        <div class="family-icon"><i class="fas fa-home"></i></div>
                        <h4>Lilian's Family</h4>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Kiwanga Area</p>
                        <div class="testimonial">
                            <p><em>"FastNetUG has transformed our home internet experience. The whole family stays connected!"</em></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="family-card">
                        <div class="family-icon"><i class="fas fa-home"></i></div>
                        <h4>Judith's Family</h4>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Nabisunsa Area</p>
                        <div class="testimonial">
                            <p><em>"Excellent service! Our kids can study online while we work from home seamlessly."</em></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="family-card join-families">
                        <div class="family-icon"><i class="fas fa-user-plus"></i></div>
                        <h4>Join Our Family Network</h4>
                        <p>Become part of our growing family of satisfied customers with reliable home internet.</p>
                        <a href="https://wa.me/256756585769" class="btn btn-light" target="_blank">
                            <i class="fab fa-whatsapp"></i> Get Quote
                        </a>
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
                    <a href="https://wa.me/256756585769" class="btn btn-success btn-lg" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <script src="../scripts/main.js"></script>
    <script src="../scripts/coverage.js"></script>
</body>

</html>
