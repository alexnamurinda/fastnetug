<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Ticket Booking - T&T Business Solutions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/airticketbooking.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link active" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            Services <i class="fas fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="services.php">Real Estate</a></li>
                            <li><a class="dropdown-item active" href="#">Air Ticket Booking</a></li>
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
                    <h1>Air ticket Booking services</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="../index.php#services">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">air tickets</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="service-detail py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6" data-aos="zoom-in">
                    <img src="../images/flightservice.jpg" alt="booking service" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
                    <h2>Seamless Flight Booking Solutions</h2>
                    <p class="lead">Let us handle your travel arrangements with our expert air ticket booking services.</p>
                    <p>At T&T Business Solutions, we make flying easier by offering comprehensive flight booking services for both business and leisure travelers. Our dedicated team works with major airlines worldwide to ensure you get the best fares and optimal itineraries for your needs.</p>
                    <div class="mt-4">
                        <a href="contact.php#inquiryForm" class="btn btn-primary me-2">Book Now</a>
                        <a href="#flight-options" class="btn btn-outline-primary">Explore Options</a>
                    </div>
                </div>
            </div>

            <!-- Flight Booking Features -->
            <div class="row my-5" id="flight-options">
                <div class="col-12 text-center mb-4">
                    <h2 class="section-title">Our Booking Services</h2>
                    <p class="section-description">Comprehensive solutions for all your travel needs</p>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="fas fa-plane-departure fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title">International Flights</h4>
                            <p class="card-text">Access to all major international airlines with competitive prices and flexible booking options.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="fas fa-suitcase fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title">Business Travel</h4>
                            <p class="card-text">Specialized services for business travelers including priority boarding and lounge access arrangements.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="fas fa-users fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title">Group Bookings</h4>
                            <p class="card-text">Special rates and coordinated itineraries for family trips, corporate events, and tour groups.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us Section -->
            <div class="row my-5 py-4 bg-light rounded">
                <div class="col-12 text-center mb-4">
                    <h2 class="section-title" style="color: #3498db;">Why Choose Our Booking Service</h2>
                </div>

                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in">
                    <div class="text-center feature-box">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h5>Best Price Guarantee</h5>
                        <p>We offer competitive rates and price matching on all bookings.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="text-center feature-box">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5>24/7 Support</h5>
                        <p>Our team is available round the clock for any travel emergencies.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-center feature-box">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-route"></i>
                        </div>
                        <h5>Flexible Options</h5>
                        <p>Change or cancel your bookings with minimal hassle and fees.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-center feature-box">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Secure Booking</h5>
                        <p>SSL encrypted platform for safe payment processing.</p>
                    </div>
                </div>
            </div>

            <!-- Airlines Partners -->
            <div class="row my-5">
                <div class="col-12 text-center mb-4">
                    <h2 class="section-title">Our Airline Partners</h2>
                    <p class="section-description">We work with major airlines globally to provide you the best service</p>
                </div>

                <div class="col-12">
                    <div class="partners-carousel text-center py-4">
                        <div class="row">
                            <div class="col-4 col-md-2 mb-4">
                                <div class="partner-logo">
                                    <img src="../images/partner2.avif" alt="Airline 1" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-4 col-md-2 mb-4">
                                <div class="partner-logo">
                                    <img src="../images/partner3.jpg" alt="Airline 2" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-4 col-md-2 mb-4">
                                <div class="partner-logo">
                                    <img src="../images/partner5.png" alt="Airline 3" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-4 col-md-2 mb-4">
                                <div class="partner-logo">
                                    <img src="../images/partner6.jpeg" alt="Airline 4" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-4 col-md-2 mb-4">
                                <div class="partner-logo">
                                    <img src="../images/partner7.jpeg" alt="Airline 5" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-4 col-md-2 mb-4">
                                <div class="partner-logo">
                                    <img src="../images/partner8.avif" alt="Airline 6" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- book Process Section -->
            <section class="process-section" style="text-align: center;">
                <div class="container">
                    <div class="section-header text-center" data-aos="fade-up">
                        <h3>Simple Booking Process</h3>
                        <p>Book your flights in just easy steps</p>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                            <div class="process-card">
                                <div class="process-icon">
                                    <i class="fas fa-comments"></i>
                                    <span class="process-number">1</span>
                                </div>
                                <h3>Contact Us</h3>
                                <p>Reach out with your travel requirements through our contact form or phone.</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                            <div class="process-card">
                                <div class="process-icon">
                                    <i class="fas fa-search"></i>
                                    <span class="process-number">2</span>
                                </div>
                                <h3>Get Quotations</h3>
                                <p>We'll provide you with multiple options based on your preferences and budget.</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="300">
                            <div class="process-card">
                                <div class="process-icon">
                                    <i class="fas fa-eye"></i>
                                    <span class="process-number">3</span>
                                </div>
                                <h3>Confirm & Fly</h3>
                                <p>Choose your preferred option, make the payment, and receive your e-tickets.</p>

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="400">
                            <div class="process-card">
                                <div class="process-icon">
                                    <i class="fas fa-eye"></i>
                                    <span class="process-number">4</span>
                                </div>
                                <h3>Confirm & Fly</h3>
                                <p>Choose your preferred option, make the payment, and receive your e-tickets.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <div class="row my-5">
                <div class="col-12 text-center mb-4">
                    <h2 class="section-title">Frequently Asked Questions</h2>
                </div>

                <div class="col-12">
                    <div class="accordion" id="flightFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How far in advance should I book my flight?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#flightFAQ">
                                <div class="accordion-body">
                                    For domestic flights, we recommend booking 1-3 months in advance. For international flights, 2-6 months is generally ideal to get the best rates. However, last-minute deals are sometimes available, so don't hesitate to contact us even for urgent travel needs.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What if I need to change my flight date?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#flightFAQ">
                                <div class="accordion-body">
                                    We offer flexible booking options. Contact us as soon as you know about the change, and we'll help you navigate the airline's change policies to find the most cost-effective solution. In many cases, we can reduce or eliminate change fees through our special arrangements with airlines.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Do you offer travel insurance?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#flightFAQ">
                                <div class="accordion-body">
                                    Yes, we offer comprehensive travel insurance options that can cover flight cancellations, medical emergencies, lost luggage, and more. Our advisors will help you select the right coverage level for your specific trip requirements.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Can you help with special services like wheelchair assistance?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#flightFAQ">
                                <div class="accordion-body">
                                    Absolutely! We can arrange any special assistance you require, including wheelchair service, special meals, bassinet for infants, extra legroom seats, and more. Just let us know your requirements when you contact us, and we'll make all the necessary arrangements.
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
                            <li><a href="reviews.php">Reviews</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="footer-links">
                        <h4>Our Services</h4>
                        <ul>
                            <li><a href="services.php">Real Estate</a></li>
                            <li><a href="#">Air Ticket Booking</a></li>
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
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Library JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Custom JS -->
    <script src="js/main.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>

</html>