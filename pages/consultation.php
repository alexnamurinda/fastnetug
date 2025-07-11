<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Consultation Services - T&T Business Solutions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/consultation.css" rel="stylesheet">
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
                            <li><a class="dropdown-item" href="services.php">Real Estate</a></li>
                            <li><a class="dropdown-item" href="airticketbooking.php">Air Ticket Booking</a></li>
                            <li><a class="dropdown-item active" href="#">Visa Consultation</a></li>
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
                    <h1>Expert Visa Consultation Services</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="../index.php#services">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Visa Consultation Services</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section id="services-overview" class="py-5">
        <div class="container">
            <div class="row service-tabs">
                <div class="col-md-4" data-aos="fade-up">
                    <ul class="nav flex-column service-nav" id="serviceNav" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="initial-tab" data-bs-toggle="tab" href="#initial" role="tab">Initial Assessment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="visa-type-tab" data-bs-toggle="tab" href="#visa-type" role="tab">Visa Type Selection</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="eligibility-tab" data-bs-toggle="tab" href="#eligibility" role="tab">Eligibility Review</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="documentation-tab" data-bs-toggle="tab" href="#documentation" role="tab">Documentation Guidance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="interview-tab" data-bs-toggle="tab" href="#interview" role="tab">Interview Preparation</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="tab-content service-content" id="serviceContent">
                        <div class="tab-pane fade show active" id="initial" role="tabpanel">
                            <h3>Initial Assessment</h3>
                            <p>Our expert consultants conduct a thorough evaluation of your profile, travel history, and immigration goals to provide personalized guidance for your visa application.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i> Complete profile evaluation</li>
                                <li><i class="fas fa-check-circle"></i> Travel history assessment</li>
                                <li><i class="fas fa-check-circle"></i> Goal-oriented recommendations</li>
                            </ul>
                            <div class="price-box">
                                <span class="price">$99</span>
                                <span class="duration">60-minute session</span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="visa-type" role="tabpanel">
                            <h3>Visa Type Selection</h3>
                            <p>We help you navigate through various visa options available for your destination country and identify the most suitable visa category based on your purpose of travel.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i> Comprehensive visa options review</li>
                                <li><i class="fas fa-check-circle"></i> Purpose-aligned recommendations</li>
                                <li><i class="fas fa-check-circle"></i> Success probability assessment</li>
                            </ul>
                            <div class="price-box">
                                <span class="price">$129</span>
                                <span class="duration">60-minute session</span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="eligibility" role="tabpanel">
                            <h3>Eligibility Review</h3>
                            <p>Our consultants analyze your qualifications against visa requirements to determine your eligibility and suggest ways to strengthen your application.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i> Requirement matching</li>
                                <li><i class="fas fa-check-circle"></i> Gap analysis</li>
                                <li><i class="fas fa-check-circle"></i> Improvement recommendations</li>
                            </ul>
                            <div class="price-box">
                                <span class="price">$149</span>
                                <span class="duration">90-minute session</span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="documentation" role="tabpanel">
                            <h3>Documentation Guidance</h3>
                            <p>We provide detailed guidance on preparing and organizing your application documents to ensure compliance with visa requirements.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i> Document checklist</li>
                                <li><i class="fas fa-check-circle"></i> Format guidelines</li>
                                <li><i class="fas fa-check-circle"></i> Translation requirements</li>
                            </ul>
                            <div class="price-box">
                                <span class="price">$119</span>
                                <span class="duration">60-minute session</span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="interview" role="tabpanel">
                            <h3>Interview Preparation</h3>
                            <p>Our mock interview sessions help you prepare for visa interviews with practice questions, feedback, and strategies to present your case confidently.</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle"></i> Mock interview sessions</li>
                                <li><i class="fas fa-check-circle"></i> Personalized feedback</li>
                                <li><i class="fas fa-check-circle"></i> Confidence-building techniques</li>
                            </ul>
                            <div class="price-box">
                                <span class="price">$179</span>
                                <span class="duration">90-minute session</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking" class="bg-light py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Book a Consultation</h2>

            <div class="row">
                <div class="col-lg-6" data-aos="zoom-in">
                    <div class="booking-info p-4 rounded shadow-sm">
                        <h3>How It Works</h3>
                        <ol class="booking-steps">
                            <li>Select your preferred consultation service</li>
                            <li>Choose a convenient date and time</li>
                            <li>Fill in your details and requirements</li>
                            <li>Receive confirmation and pre-consultation materials</li>
                            <li>Meet with your consultant (online or in-person)</li>
                        </ol>

                        <div class="consultation-options mt-4">
                            <h4>Consultation Options</h4>
                            <div class="option-item">
                                <span class="option-label"><i class="fas fa-video"></i> Online:</span>
                                <span class="option-detail">Via Zoom, Teams, or Google Meet</span>
                            </div>
                            <div class="option-item">
                                <span class="option-label"><i class="fas fa-building"></i> In-person:</span>
                                <span class="option-detail">At our office (by appointment)</span>
                            </div>
                            <div class="option-item">
                                <span class="option-label"><i class="fas fa-globe"></i> Languages:</span>
                                <span class="option-detail">English, Spanish, French, Mandarin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <form id="consultationForm" class="booking-form p-4 rounded shadow-sm">
                        <div class="mb-3">
                            <label for="serviceType" class="form-label">Service Type</label>
                            <select class="form-select" id="serviceType" required>
                                <option value="" selected disabled>Select a service</option>
                                <option value="initial">Initial Assessment</option>
                                <option value="visa-type">Visa Type Selection</option>
                                <option value="eligibility">Eligibility Review</option>
                                <option value="documentation">Documentation Guidance</option>
                                <option value="interview">Interview Preparation</option>
                                <option value="comprehensive">Comprehensive Package</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="appointmentDate" class="form-label">Preferred Date</label>
                                <input type="date" class="form-control" id="appointmentDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentTime" class="form-label">Preferred Time</label>
                                <select class="form-select" id="appointmentTime" required>
                                    <option value="" selected disabled>Select time</option>
                                    <option value="9:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Consultation Mode</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="consultationMode" id="modeOnline" value="online" checked>
                                <label class="form-check-label" for="modeOnline">Online</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="consultationMode" id="modeInPerson" value="in-person">
                                <label class="form-check-label" for="modeInPerson">In-Person</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>

                        <div class="mb-3">
                            <label for="destinationCountry" class="form-label">Destination Country</label>
                            <input type="text" class="form-control" id="destinationCountry" required>
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Specific Requirements</label>
                            <textarea class="form-control" id="requirements" rows="3"></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Frequently Asked Questions</h2>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion faq-accordion" id="faqAccordion">
                        <!-- FAQ Item 1 -->
                        <div class="accordion-item" data-aos="fade-up">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How long before my travel should I schedule a consultation?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We recommend scheduling your initial consultation at least 3-6 months before your planned travel date. This allows sufficient time for visa processing, document preparation, and addressing any potential issues that may arise during the application process.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    What should I prepare before the consultation?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    To maximize your consultation session, please prepare: your passport details, travel history, purpose of travel, intended duration of stay, financial documents, any previous visa applications and their outcomes, and specific questions or concerns you may have.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Can you guarantee my visa approval?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    While we provide expert guidance to maximize your chances of approval, no visa consultant can guarantee approval as the final decision rests with immigration authorities. Our services focus on preparing the strongest possible application based on your individual circumstances and relevant immigration laws.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    What happens after the consultation?
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    After your consultation, you'll receive a detailed summary of the discussion, personalized recommendations, and a structured action plan. If you opt for additional services, we'll guide you through the next steps of documentation preparation or application processing.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="bg-light py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Client Testimonials</h2>

            <div class="testimonial-slider" id="testimonialCarousel">
                <div class="row">
                    <!-- Testimonial 1 -->
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p>"The visa consultation service was incredibly helpful. My consultant identified potential issues in my application that I would have never noticed. Thanks to their guidance, my visa was approved without any delays."</p>
                            </div>
                            <div class="testimonial-author">
                                <img src="../images/developer.jpg" alt="John D." class="testimonial-img">
                                <div class="author-info">
                                    <h4>John D.</h4>
                                    <p>Student Visa Applicant</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p>"After a previous visa rejection, I was worried about applying again. The interview preparation service boosted my confidence and equipped me with the right approach. My visa was approved on the second attempt!"</p>
                            </div>
                            <div class="testimonial-author">
                                <img src="../images/sara.jpg" alt="Sarah M." class="testimonial-img">
                                <div class="author-info">
                                    <h4>Sarah M.</h4>
                                    <p>Business Visa Applicant</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p>"The documentation guidance was worth every penny. The consultant helped me organize my complex financial documents in a way that clearly demonstrated my eligibility. Highly recommended!"</p>
                            </div>
                            <div class="testimonial-author">
                                <img src="../images/micheal.jpg" alt="Raj P." class="testimonial-img">
                                <div class="author-info">
                                    <h4>Raj P.</h4>
                                    <p>Investor Visa Applicant</p>
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
                            <li><a href="airticketbooking.php">Air Ticket Booking</a></li>
                            <li><a href="#">Visa Consultation</a></li>
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

    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Custom JavaScript -->
    <script src="../scripts/main.js"></script>
    <script src="../scripts/consultation.js"></script>
</body>

</html>