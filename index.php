<?php
$basePath = '';
$activePage = 'home';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastNetUG | Premium WiFi Solutions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="css/mainstyles.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
</head>

<body>

    <?php include 'includes/nav.php'; ?>

    <header class="hero-section">
        <div id="heroCarousel" class="carousel carousel-fade hero-carousel"
             data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover">

            <!-- Dot indicators -->
            <div class="carousel-indicators hero-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0"
                        class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
            </div>
            <!-- Slide 1 -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="hero-designed-slide" style="background-image:url('images/flier1.png')">
                        <div class="hero-design-overlay">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-6 col-md-8 col-11">
                                        <div class="hero-design-content">
                                            <h1 class="hd-title">Stay Connected.<br>Stay <span class="hd-red">Ahead.</span></h1>
                                            <p class="hd-sub">
                                                Affordable High-Speed Wi-Fi for<br>
                                                <span class="hd-blue hd-bold">Students</span>,&nbsp;
                                                <span class="hd-red hd-bold">Homes</span> &amp;&nbsp;
                                                <span class="hd-blue hd-bold">Businesses.</span>
                                            </p>
                                            <div class="hd-wifi-badge">
                                                <i class="fas fa-wifi"></i>
                                                SELF-SERVICE PACKAGES AVAILABLE 24/7
                                            </div>
                                            <div class="hd-pkg-row">
                                                <div class="hd-pkg hd-pkg-blue">
                                                    <i class="fas fa-wifi hd-pkg-icon"></i>
                                                    <div class="hd-pkg-price">UGX 500</div>
                                                    <span class="hd-pkg-tag hd-tag-blue">5 HOURS</span>
                                                </div>
                                                <div class="hd-pkg hd-pkg-red">
                                                    <i class="fas fa-wifi hd-pkg-icon"></i>
                                                    <div class="hd-pkg-price hd-price-red">UGX 1,000</div>
                                                    <span class="hd-pkg-tag hd-tag-red">1 DAY</span>
                                                </div>
                                                <div class="hd-pkg hd-pkg-green">
                                                    <i class="fas fa-wifi hd-pkg-icon"></i>
                                                    <div class="hd-pkg-price">UGX 6,000</div>
                                                    <span class="hd-pkg-tag hd-tag-green">1 WEEK</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="images/flier2.png" class="hero-flier-img"
                         alt="FastNetUG — Promotion 2" loading="lazy">
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="hero-slide3-wrap">
                        <img src="images/flier3.png" class="hero-flier-img" alt="FastNetUG — 24/7 Support" loading="lazy">
                        <div class="slide3-bubble">
                            <span class="slide3-dot"></span>
                            <p class="slide3-online">We are online</p>
                            <p class="slide3-tagline">24/7 for your support</p>
                            <div class="slide3-actions">
                                <a href="https://wa.me/256756585769" target="_blank" rel="noopener" class="slide3-btn slide3-wa" aria-label="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="tel:0780393671" class="slide3-btn slide3-call" aria-label="Call">
                                    <i class="fas fa-phone-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prev arrow -->
            <button class="carousel-control-prev hero-arrow" type="button"
                    data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="hero-arrow-icon" aria-hidden="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="visually-hidden">Previous</span>
            </button>

            <!-- Next arrow -->
            <button class="carousel-control-next hero-arrow" type="button"
                    data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="hero-arrow-icon" aria-hidden="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
                <span class="visually-hidden">Next</span>
            </button>

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

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="package-card featured">
                        <div class="badge bg-success position-absolute top-0 start-50 translate-middle">Most Popular</div>
                        <h4>Monthly Plan</h4>
                        <div class="package-price">20,000 <small>UGX</small></div>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> 30 days unlimited</li>
                            <li><i class="fas fa-check text-success"></i> <span style="color:red;">Up to 25k for 2 devices</span></li>
                            <li><i class="fas fa-check text-success"></i> Priority support</li>
                        </ul>
                        <button class="btn btn-success w-100">Choose Plan</button>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="package-card">
                        <h4>Semester Pack</h4>
                        <div class="package-price">50,000 <small>UGX</small></div>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> 120 days unlimited access</li>
                            <li><i class="fas fa-check text-success"></i> Up to 1 device</li>
                            <li><i class="fas fa-check text-success"></i> Best value for students</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Choose Plan</button>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="section-header text-center text-black" data-aos="fade-up">
                    <h3>Special Packages</h3>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="package-card">
                        <h4><i class="fas fa-users"></i> Family Bundle</h4>
                        <div class="package-price">50,000 <small>UGX</small></div>
                        <p class="text-muted">per month</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Up to 500 Mbps shared speed</li>
                            <li><i class="fas fa-check text-success"></i> Up to 4 devices</li>
                            <li><i class="fas fa-check text-success"></i> Free installation</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Order Now</button>
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
                        </ul>
                        <button class="btn btn-outline-primary w-100">Get Quote</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coverage & About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="zoom-in">
                    <div class="coverage-map text-center">
                        <i class="fas fa-map-marked-alt" style="font-size: 5rem; margin-bottom: 20px;"></i>
                        <h3>Coverage Countrywide</h3>
                        <p>Our network covers major areas including Nabisunsa Close, Namuwongo, Kibuli, Kiwanga, +more.</p>
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
                        <h2>Why Choose FastNet UG?</h2>
                        <p style="text-align:justify;">We're Uganda's fastest-growing WiFi provider, committed to delivering reliable, high-speed internet to students, communities, and businesses across Kampala and beyond.</p>
                        <p style="text-align:justify;">Our network is backed by cutting-edge technology and round-the-clock support — day or night.</p>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 mb-4">
                                <div class="feature-item">
                                    <i class="fas fa-bolt feature-icon"></i>
                                    <h4>Fast Speeds</h4>
                                    <p>Up to 500 Mbps speeds for seamless browsing and streaming</p>
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
            </div>
            <div class="testimonial-slider" data-aos="fade-up">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-author">
                                <img src="images/profile_pic.png" alt="Student" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Henry B</h5>
                                    <span>Kyambogo University</span>
                                    <div class="testimonial-rating">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="testimonial-text">"FastNetUG saved my semester! Incredibly fast and reliable — I stream lectures and video call family without interruptions."</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-author">
                                <img src="images/sara.jpg" alt="Family Customer" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Lilian N</h5>
                                    <span>Family Package</span>
                                    <div class="testimonial-rating">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="testimonial-text">"FastNetUG's family bundle gives amazing speeds for all five of us, and their customer service is exceptional!"</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-author">
                                <img src="images/profile_pic.png" alt="Business Customer" class="rounded-circle">
                                <div class="author-info">
                                    <h5>Wandela S</h5>
                                    <span>Manager, Nabisunsa Savings Sacco</span>
                                    <div class="testimonial-rating">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="testimonial-text">"FastNetUG's business package has been a game-changer — reliable internet with 99% uptime for our operations."</p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="pages/reviews.php" class="btn btn-outline-primary">View All Reviews</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Package Selection Modal -->
    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageModalLabel">Enter your phone number to continue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="selectedPackageAlert" class="alert alert-info fw-bold text-center"></div>
                    <form id="packageForm">
                        <input type="hidden" id="selectedPackageName">
                        <input type="hidden" id="selectedPackageAmount">
                        <div class="mb-3">
                            <input type="tel" class="form-control" id="customerPhone" placeholder="Enter phone number" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" id="customerLocation" required>
                                <option value="">Select your location</option>
                                <option value="Nabisunsa">Nabisunsa Close</option>
                                <option value="Kiwanga">Kiwanga</option>
                                <option value="Ntinda">Ntinda</option>
                                <option value="Kibuli">Kibuli</option>
                                <option value="Other">Other</option>
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

    <!-- FAB Cluster: fixed bottom-right column (AI Chat → WhatsApp → Back to Top) -->
    <div class="fab-cluster">

        <!-- AI Chat Widget -->
        <div id="ai-chat-widget">
            <div id="ai-chat-panel" class="d-none">
                <div id="ai-chat-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-robot"></i>
                        <div>
                            <div class="fw-semibold">FastNet Assistant</div>
                            <small class="opacity-75">Ask me anything</small>
                        </div>
                    </div>
                    <button id="ai-chat-close" aria-label="Close chat"><i class="fas fa-times"></i></button>
                </div>
                <div id="ai-chat-messages">
                    <div class="ai-msg bot">
                        <i class="fas fa-robot ai-avatar"></i>
                        <div class="ai-bubble">Hello! I'm your FastNetUG assistant. Ask me about our packages, coverage, or how to connect. 😊</div>
                    </div>
                </div>
                <div id="ai-chat-input-area">
                    <input type="text" id="ai-chat-input" placeholder="Type your question..." maxlength="500" autocomplete="off">
                    <button id="ai-chat-send"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
            <button id="ai-chat-toggle" aria-label="Open AI Assistant">
                <i class="fas fa-robot" id="ai-toggle-icon"></i>
            </button>
        </div>

        <!-- WhatsApp -->
        <a href="https://wa.me/256756585769" class="fab-btn fab-whatsapp"
           aria-label="Chat on WhatsApp" title="WhatsApp Us" target="_blank" rel="noopener">
            <i class="fab fa-whatsapp"></i>
        </a>

        <!-- Back to Top (appears after scrolling) -->
        <a href="#" class="fab-btn back-to-top" aria-label="Back to Top">
            <i class="fas fa-chevron-up"></i>
        </a>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <script src="scripts/main.js"></script>

    <script>
        AOS.init();

        const packageMap = {
            "Daily Pass": "1,000 UGX",
            "Weekly Plan": "6,000 UGX",
            "Monthly Plan": "20,000 UGX",
            "Semester Pack": "50,000 UGX",
            "Family Bundle": "50,000 UGX/month",
            "Business Pro": "Custom Pricing"
        };

        $('.package-card button').on('click', function() {
            const card = $(this).closest('.package-card');
            const packageName = card.find('h4').text().trim();
            const price = packageMap[packageName] || 'Custom Pricing';
            $('#selectedPackageName').val(packageName);
            $('#selectedPackageAmount').val(price);
            $('#selectedPackageAlert').html('You chose <strong>' + packageName + '</strong> at <strong>' + price + '</strong>.');
            $('#packageModal').modal('show');
        });

        $('#submitPackageRequest').on('click', function() {
            const phone = $('#customerPhone').val().trim();
            const location = $('#customerLocation').val();
            const phoneRegex = /^(?:\+256|0)?7\d{8}$/;
            if (!phone || !phoneRegex.test(phone)) {
                alert('Please enter a valid Ugandan phone number.');
                return;
            }
            if (!location) {
                alert('Please select your location.');
                return;
            }
            alert('Connection request submitted. We will contact you shortly.');
            $('#packageModal').modal('hide');
            $('#packageForm')[0].reset();
        });

        // AI Chat Widget
        const chatPanel = document.getElementById('ai-chat-panel');
        const chatToggle = document.getElementById('ai-chat-toggle');
        const chatClose = document.getElementById('ai-chat-close');
        const chatInput = document.getElementById('ai-chat-input');
        const chatSend = document.getElementById('ai-chat-send');
        const chatMessages = document.getElementById('ai-chat-messages');

        chatToggle.addEventListener('click', () => {
            chatPanel.classList.toggle('d-none');
            if (!chatPanel.classList.contains('d-none')) chatInput.focus();
        });

        chatClose.addEventListener('click', () => chatPanel.classList.add('d-none'));

        function appendMessage(text, type) {
            const div = document.createElement('div');
            div.className = 'ai-msg ' + type;
            if (type === 'bot') {
                div.innerHTML = '<i class="fas fa-robot ai-avatar"></i><div class="ai-bubble">' + escapeHtml(text) + '</div>';
            } else {
                div.innerHTML = '<div class="ai-bubble">' + escapeHtml(text) + '</div>';
            }
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function escapeHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
        }

        function showTyping() {
            const div = document.createElement('div');
            div.className = 'ai-msg bot typing-indicator';
            div.id = 'ai-typing';
            div.innerHTML = '<i class="fas fa-robot ai-avatar"></i><div class="ai-bubble"><span></span><span></span><span></span></div>';
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function removeTyping() {
            const t = document.getElementById('ai-typing');
            if (t) t.remove();
        }

        async function sendMessage() {
            const msg = chatInput.value.trim();
            if (!msg) return;
            chatInput.value = '';
            chatSend.disabled = true;
            appendMessage(msg, 'user');
            showTyping();
            try {
                const res = await fetch('pages/ai_chat.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({message: msg})
                });
                const data = await res.json();
                removeTyping();
                appendMessage(data.reply || 'Sorry, something went wrong. Please try again.', 'bot');
            } catch (e) {
                removeTyping();
                appendMessage('Could not connect. Please WhatsApp us at 0756585769 for help.', 'bot');
            }
            chatSend.disabled = false;
            chatInput.focus();
        }

        chatSend.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
    </script>

</body>
</html>
