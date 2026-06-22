<?php
$basePath = '../';
$activePage = 'about';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - FastNetUG</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/about.css" rel="stylesheet">
</head>

<body>
    <?php include '../includes/nav.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h1>About Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="our-story-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="story-content">
                        <h3>Our Story</h3>
                        <p class="lead">Connecting Uganda, One Hotspot at a Time</p>
                        <p>Founded in 2023, FastNetUG was born from a simple observation: students, families, and small businesses around Nabisunsa deserved affordable, reliable internet access without the complications of traditional ISPs.</p>
                        <p>What started as a small WiFi hotspot operation in Namuli Hostel has grown into the region's fastest-growing WiFi network, serving over 1,000 active users across Kampala and surrounding areas.</p>
                        <p>Our founder saw students struggling with expensive data bundles and unreliable home internet. This inspired the creation of FastNetUG — a service that puts connectivity first, with transparent pricing and no hidden fees.</p>
                        <p>Today, we're proud to be the go-to WiFi solution for student hostels, families, and small businesses who need fast, affordable internet that just works.</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="story-image">
                        <img src="../images/testim2.avif" alt="Our Network Coverage" class="img-fluid rounded mb-3">
                        <img src="../images/testim3.avif" alt="Our Network Coverage" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Meet Our Team</h2>
                <p>The tech-savvy experts behind your connectivity</p>
            </div>

            <!-- Leadership row -->
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-lg-4 col-md-6 col-sm-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="leader-wrap">
                        <img src="../images/developer.jpg" alt="Eng. Alex" class="leader-float-img">
                        <div class="leader-float-card">
                            <h5>Eng. Alex</h5>
                            <span class="leader-role">Founder &amp; CEO</span>
                            <div class="team-social">
                                <a href="https://www.linkedin.com/in/namurinda-alex-25217a255/" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="https://x.com/namurindaalex43" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="https://wa.me/256745685794" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-8" data-aos="fade-up" data-aos-delay="150">
                    <div class="leader-wrap">
                        <img src="../images/Mark.png" alt="Kasumba Mark" class="leader-float-img">
                        <div class="leader-float-card">
                            <h5>Kasumba Mark</h5>
                            <span class="leader-role">Head of Network Ops</span>
                            <div class="team-social">
                                <a href="tel:0752090648" aria-label="Call"><i class="fas fa-phone-alt"></i></a>
                                <a href="https://wa.me/256752090648" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="leader-wrap">
                        <img src="../images/developer.jpg" alt="Eng. Alex" class="leader-float-img">
                        <div class="leader-float-card">
                            <h5>Moses</h5>
                            <span class="leader-role">Head of Sales</span>
                            <div class="team-social">
                                <a href="#" aria-label="Call"><i class="fas fa-phone-alt"></i></a>
                                <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Field Agents divider -->
            <div class="agents-label text-center" data-aos="fade-up">
                <span>Field Agents</span>
            </div>

            <!-- Agent compact cards -->
            <div class="row g-3">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="agent-card">
                        <img src="../images/sara.jpg" alt="Achan K" class="agent-avatar">
                        <div class="agent-info">
                            <h5>Achan K</h5>
                            <span>Kasalita Hostel</span>
                            <div class="team-social">
                                <a href="tel:0775580790" aria-label="Call"><i class="fas fa-phone-alt"></i></a>
                                <a href="https://wa.me/256775580790" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="150">
                    <div class="agent-card">
                        <img src="../images/habib.png" alt="Habib Ssemakula" class="agent-avatar">
                        <div class="agent-info">
                            <h5>Habib Ssemakula</h5>
                            <span>New Hostel</span>
                            <div class="team-social">
                                <a href="tel:0767239404" aria-label="Call"><i class="fas fa-phone-alt"></i></a>
                                <a href="https://wa.me/256767239404" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="agent-card">
                        <img src="../images/prossie.png" alt="Prossie M" class="agent-avatar">
                        <div class="agent-info">
                            <h5>Prossie M</h5>
                            <span>Herman Hostel</span>
                            <div class="team-social">
                                <a href="tel:0757951874" aria-label="Call"><i class="fas fa-phone-alt"></i></a>
                                <a href="https://wa.me/256757951874" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="250">
                    <div class="agent-card">
                        <img src="../images/micheal.jpg" alt="Michael Kunta" class="agent-avatar">
                        <div class="agent-info">
                            <h5>Michael Kunta</h5>
                            <span>Lower Herman</span>
                            <div class="team-social">
                                <a href="tel:0708620852" aria-label="Call"><i class="fas fa-phone-alt"></i></a>
                                <a href="https://wa.me/256708620852" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Milestones Section -->
    <section class="milestones-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Our Journey</h2>
                <p>Key milestones in our network expansion</p>
            </div>
            <div class="timeline">
                <div class="timeline-item left" data-aos="zoom-in">
                    <div class="timeline-content">
                        <h3>Nov 2023</h3>
                        <p>FastNetUG launched with first WiFi hotspot in Namuli Hostel, serving 30 students</p>
                    </div>
                </div>
                <div class="timeline-item right" data-aos="zoom-in">
                    <div class="timeline-content">
                        <h3>May 2024</h3>
                        <p>Expanded to wing 2 of Namuli Hostel with 50+ active users</p>
                    </div>
                </div>
                <div class="timeline-item left" data-aos="zoom-in">
                    <div class="timeline-content">
                        <h3>Oct 2024</h3>
                        <p>Reached 1,000 users milestone and expanded to 2 more Hostels</p>
                    </div>
                </div>
                <div class="timeline-item right" data-aos="zoom-in">
                    <div class="timeline-content">
                        <h3>March 2025</h3>
                        <p>Upgraded to fiber backbone infrastructure and achieved 99.9% uptime</p>
                    </div>
                </div>
                <div class="timeline-item left" data-aos="zoom-in">
                    <div class="timeline-content">
                        <h3>May 2025</h3>
                        <p>Expanded to 10+ hotspot locations and mobilized a strong 24/7 customer support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="partners-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2>Our Technology Partners</h2>
            </div>
            <div class="partners-slider" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-md-3 col-6 text-center">
                        <div class="partner-logo">
                            <img src="../images/Savanna Fibre.png" alt="Savanna Fibre" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="partner-logo">
                            <img src="../images/liquid.png" alt="Liquid Intelligent Technologies" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="partner-logo">
                            <img src="../images/airtel.png" alt="Airtel" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="partner-logo">
                            <img src="../images/isbat.png" alt="ISBAT University" class="img-fluid">
                        </div>
                    </div>
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
</body>

</html>
