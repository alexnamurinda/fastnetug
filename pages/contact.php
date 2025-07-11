<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - T&T Business Solutions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/contact.css" rel="stylesheet">
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
                        <a class="nav-link active" href="#">Contact</a>
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
                    <h1>Contact Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-info-card text-center h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                        </div>
                        <h3>Our Location</h3>
                        <p>123 Nabisunsa, Jinja Road<br>Nakawa Division<br>Kampala, Uganda</p>
                        <a href="https://maps.google.com" target="_blank" class="btn btn-outline-primary mt-2">Get Directions</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card text-center h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-envelope fa-3x text-primary"></i>
                        </div>
                        <h3>Email Us</h3>
                        <p>General Inquiries:<br><a href="mailto:info@tandbsolutions.com">info@ttbusinesssolution.com</a></p>
                        <p>Support:<br><a href="mailto:support@tandbsolutions.com">support@ttbusinesssolution.com</a></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card text-center h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-phone-alt fa-3x text-primary"></i>
                        </div>
                        <h3>Call Us</h3>
                        <p>Office: +256 744 - 766 - 410</p>
                        <p>Customer Service: +256 744 - 766 - 410</p>
                        <p>Mon-Fri: 8:00 AM - 5:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5" id="inquiryForm">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-form-wrapper p-4 rounded shadow-sm">
                        <h3 class="mb-4" style="text-align: center;">Send Us a Message</h3>
                        <form id="contactForm" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                    <div class="invalid-feedback">
                                        Please provide your name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid email.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject</label>
                                    <select class="form-select" id="subject" required>
                                        <option value="" selected disabled>Select a subject</option>
                                        <option value="Real Estate">Real Estate Inquiry</option>
                                        <option value="Air Tickets">Air Ticket Booking</option>
                                        <option value="Visa Consultation">Visa Consultation</option>
                                        <option value="Documentation">Documentation Services</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a subject.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Your Message</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                    <div class="invalid-feedback">
                                        Please enter your message.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletter">
                                        <label class="form-check-label" for="newsletter">
                                            Subscribe to our newsletter
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg" style="text-align: center; width: 100%;">Send Message</button>
                                    <div id="formSubmitSuccess" class="alert alert-success mt-3 d-none">
                                        Thank you! Your message has been sent successfully.
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="map-container h-100">
                        <h2 class="mb-4" style="text-align: center;">Find Us</h2>
                        <!-- Google Maps iframe - Replace with your actual map embed code -->
                        <div class="ratio ratio-4x3 rounded shadow-sm overflow-hidden">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7473709418027!2d32.631307072863564!3d0.34169299965496724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177db9001473c987%3A0x6f3e93f5084de1b0!2sBanda%20Nabisunsa!5e0!3m2!1sen!2sug!4v1744117366299!5m2!1sen!2sug" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="business-hours mt-4 p-3 rounded shadow-sm">
                            <h4><i class="far fa-clock me-2"></i>Business Hours</h4>
                            <ul class="list-unstyled mt-3">
                                <li><strong>Monday - Friday:</strong> 9:00 AM - 5:00 PM</li>
                                <li><strong>Saturday:</strong> 10:00 AM - 2:00 PM</li>
                                <li><strong>Sunday:</strong> Closed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Frequently Asked Questions</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="contactFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How quickly can I expect a response?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    We strive to respond to all inquiries within 24 hours during business days. For urgent matters, we recommend calling our customer service line for immediate assistance.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Do you offer consultations for first-time clients?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    Yes, we offer a complimentary 30-minute initial consultation for all new clients. This allows us to understand your needs and explain how our services can help you.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    How can I schedule an appointment?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    You can schedule an appointment by filling out our contact form, calling our office directly, or sending us an email. Please provide your preferred date and time, and we will confirm availability.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    What areas do your real estate services cover?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    Our real estate services cover residential and commercial properties in the metropolitan area and surrounding suburbs. We can also connect you with our partner agencies for properties in other regions.
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
                            <li><a href="#">Contact Us</a></li>
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
                            <li><a href="consultation.php">Visa Consultation</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <h5 class="footer-links">Newsletter</h5>
                    <p>Subscribe to our newsletter for the latest updates and offers.</p>
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your Email">
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
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
    <a href="#" class="back-to-top btn btn-primary" role="button">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <!-- Custom JS -->
    <script src="../scripts/main.js"></script>
    <script src="../scripts/contact.js"></script>
    <script src="../scripts/about.js"></script>
</body>

</html>