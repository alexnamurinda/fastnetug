<?php
$basePath = '../';
$activePage = 'contact';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FastNetUG</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/contact.css" rel="stylesheet">
</head>

<body>
    <?php include '../includes/nav.php'; ?>

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
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-info-card text-center h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                        </div>
                        <h3>Our Location</h3>
                        <p>Nabisunsa Close<br>Banda, Nakawa Division<br>Kampala, Uganda</p>
                        <a href="https://maps.google.com/?q=Banda+Nabisunsa+Kampala" target="_blank" class="btn btn-outline-primary mt-2">Get Directions</a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-info-card text-center h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-envelope fa-3x text-primary"></i>
                        </div>
                        <h3>Email Us</h3>
                        <p>General Inquiries:<br><a href="mailto:fastnetuganda@gmail.com">fastnetuganda@gmail.com</a></p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-info-card text-center h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-phone-alt fa-3x text-primary"></i>
                        </div>
                        <h3>Call Us</h3>
                        <p><a href="tel:0756585769">0756 585 769</a></p>
                        <p><a href="tel:0780393671">0780 393 671</a></p>
                        <p class="text-muted small">Mon–Sat: 9:00 AM – 5:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5" id="inquiryForm">
        <div class="container">
            <div class="row">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="contact-form-wrapper p-4 rounded shadow-sm">
                        <h3 class="mb-4 text-center">Send Us a Message</h3>
                        <form id="contactForm" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                    <div class="invalid-feedback">Please provide your name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" required>
                                    <div class="invalid-feedback">Please provide a valid email.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject</label>
                                    <select class="form-select" id="subject" required>
                                        <option value="" selected disabled>Select a subject</option>
                                        <option value="WiFi Package">WiFi Package Inquiry</option>
                                        <option value="Technical Support">Technical Support</option>
                                        <option value="Installation">Installation Request</option>
                                        <option value="Business Package">Business Package</option>
                                        <option value="Network Coverage">Network Coverage</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a subject.</div>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Your Message</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                    <div class="invalid-feedback">Please enter your message.</div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletter">
                                        <label class="form-check-label" for="newsletter">
                                            Subscribe to our newsletter for WiFi updates and promotions
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Send Message</button>
                                    <div id="formSubmitSuccess" class="alert alert-success mt-3 d-none">
                                        Thank you! Your message has been sent successfully.
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="map-container h-100">
                        <h2 class="mb-4 text-center">Find Us</h2>
                        <div class="ratio ratio-4x3 rounded shadow-sm overflow-hidden">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7473709418027!2d32.631307072863564!3d0.34169299965496724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177db9001473c987%3A0x6f3e93f5084de1b0!2sBanda%20Nabisunsa!5e0!3m2!1sen!2sug!4v1744117366299!5m2!1sen!2sug" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="business-hours mt-4 p-3 rounded shadow-sm">
                            <h4><i class="far fa-clock me-2"></i>Business Hours</h4>
                            <ul class="list-unstyled mt-3">
                                <li><strong>Monday – Friday:</strong> 9:00 AM – 5:00 PM</li>
                                <li><strong>Saturday:</strong> 10:00 AM – 2:00 PM</li>
                                <li><strong>Sunday:</strong> Closed</li>
                            </ul>
                            <p class="text-muted small mt-3">
                                <i class="fas fa-headset me-2"></i>24/7 Technical Support via WhatsApp
                            </p>
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
                                    How quickly can I get WiFi installed?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    We typically install WiFi within 24–48 hours of your request. For urgent needs, same-day installation is available in most coverage areas. Contact us to check availability.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What areas do you currently cover?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    Our network currently covers Nabisunsa, Kiwanga, Ntinda, Kibuli, and surrounding areas. We're constantly expanding — contact us to check your specific location.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Do you offer technical support?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    Yes! We provide 24/7 technical support via WhatsApp and phone. Call or message 0756585769 for immediate assistance with any connectivity issues.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    What WiFi packages do you offer?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    We offer Daily (1,000 UGX), Weekly (6,000 UGX), Monthly (20,000 UGX), and Semester (50,000 UGX) packages for individuals, plus Family Bundles and custom Business packages.
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script src="../scripts/contact.js"></script>
</body>

</html>
