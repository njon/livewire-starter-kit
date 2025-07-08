<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdventureHub | Partner With Us</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>

        .value-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .value-card:hover {
            transform: translateY(-10px);
        }
        .step-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
            height: 100%;
        }
        .step-card:hover {
            transform: scale(1.03);
        }
        .step-number {
            font-size: 3rem;
            font-weight: 800;
            color: rgba(13, 110, 253, 0.15);
            line-height: 1;
        }
        .pricing-box {
            background-color: rgba(13, 110, 253, 0.05);
            border-left: 4px solid var(--primary);
            border-radius: 0 8px 8px 0;
        }
        .registration-form {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .form-progress {
            height: 8px;
        }
        .testimonial-card {
            background-color: var(--light);
            border-radius: 10px;
        }
        .partner-badge {
            height: 60px;
            width: auto;
            margin: 10px 15px;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: all 0.3s;
        }
        .partner-badge:hover {
            filter: grayscale(0);
            opacity: 1;
        }
        .stats-counter {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .faq-item {
            border-bottom: 1px solid #dee2e6;
            padding: 20px 0;
        }
        .faq-item:last-child {
            border-bottom: none;
        }
        .sticky-cta {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 15px 0;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
            display: none;
        }
        @media (max-width: 768px) {
            .sticky-cta {
                display: block;
            }
        }
        
        :root {
            --primary: #2ecc71;  /* Adventure green */
            --secondary: #3498db; /* Trust blue */
            --dark: #2c3e50;     /* Professional navy */
            --light: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1506929562872-bb421503ef21?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            padding: 120px 0;
        }
        
        .benefit-card {
            border-left: 4px solid var(--primary);
            transition: all 0.3s;
            height: 100%;
        }
        
        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .brand-story-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
        }
        
        .timeline {
            position: relative;
            padding-left: 3rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 1.25rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary);
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -3rem;
            top: 0.25rem;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid var(--light);
        }
        
        .faq-item {
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem 0;
        }
        
        .partner-badge {
            height: 60px;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: all 0.3s;
        }
        
        .partner-badge:hover {
            filter: grayscale(0);
            opacity: 1;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-compass me-2"></i>AdventureHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#story">Our Story</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#benefits">Benefits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light" href="#register">Join Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Turn Your Adventure Business Into a Thriving Success</h1>
                    <p class="lead mb-4">Join Lithuania's premier marketplace for experience providers. Focus on creating memories - we'll handle the rest.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#register" class="btn btn-primary btn-lg px-4 py-3">Get Started - It's Free</a>
                        <a href="#how-it-works" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="bi bi-play-circle me-2"></i>Watch Demo
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="card brand-story-card text-white p-4">
                        <h3><i class="bi bi-lightbulb me-2"></i>Why We Exist</h3>
                        <p class="mb-0">"After missing out on a kayak tour because of outdated booking systems, we built AdventureHub to connect adventurers with hidden local gems like yours."</p>
                        <div class="mt-3 text-end">
                            <small>- Founder, Rokas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Story Section -->
    <section id="story" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="fw-bold mb-4">Born to Make Memories</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <h4 class="h5 fw-bold">2023: The Frustration</h4>
                            <p>Our founder waited 3 hours to book a last-minute kayak tour, missing the sunset.</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="h5 fw-bold">2024: The Solution</h4>
                            <p>AdventureHub was born to connect explorers with authentic local experiences.</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="h5 fw-bold">Future: The Vision</h4>
                            <p>Every adventure in Lithuania starts with passionate providers like you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-heart-fill display-4 text-primary mb-3"></i>
                                    <h3 class="h5 fw-bold">For Explorers, By Explorers</h3>
                                    <p class="mb-0">We vet every partner to ensure unforgettable moments.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-shield-lock-fill display-4 text-primary mb-3"></i>
                                    <h3 class="h5 fw-bold">Trust First</h3>
                                    <p class="mb-0">Verified reviews and secure payments for all.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                                    <h3 class="h5 fw-bold">Community Powered</h3>
                                    <p class="mb-0">Partners help shape our platform.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-globe2 display-4 text-primary mb-3"></i>
                                    <h3 class="h5 fw-bold">Local Focus</h3>
                                    <p class="mb-0">Showcasing Lithuania's hidden gems.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Focus on Creating Memories – We Handle the Rest</h2>
                <p class="lead text-muted">Everything you need to grow your adventure business</p>
            </div>
            
            <div class="row g-4">
                <!-- Benefit 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="benefit-card card p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-rocket display-6 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Zero-Risk Onboarding</h3>
                        <p class="text-muted">No signup fees. No monthly costs. Only pay when guests pay you.</p>
                        <small class="text-primary fw-bold">Ideal for new activity providers</small>
                    </div>
                </div>
                
                <!-- Benefit 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="benefit-card card p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-lightning display-6 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Stress-Free Payments</h3>
                        <p class="text-muted">Reliable automated payouts within 24 hours – even for last-minute cancellations.</p>
                        <a href="#" class="small text-primary fw-bold">See terms →</a>
                    </div>
                </div>
                
                <!-- Benefit 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="benefit-card card p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-calendar-check display-6 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Free Booking HQ</h3>
                        <p class="text-muted">Your personal dashboard handles scheduling, reminders, and customer chats – no software costs.</p>
                        <small class="text-primary fw-bold">Perfect for busy guides</small>
                    </div>
                </div>
                
                <!-- Benefit 4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="benefit-card card p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-megaphone display-6 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Done-For-You Marketing</h3>
                        <p class="text-muted">We promote you across Instagram Reels, travel blogs, and Google – no ad spend needed.</p>
                        <small class="text-primary fw-bold">Best for small teams</small>
                    </div>
                </div>
                
                <!-- Benefit 5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="benefit-card card p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-cash-coin display-6 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Keep 100% of Extras</h3>
                        <p class="text-muted">All tips, equipment rentals, and add-ons go straight to you.</p>
                        <small class="text-primary fw-bold">Critical for premium upgrades</small>
                    </div>
                </div>
                
                <!-- Benefit 6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="benefit-card card p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-headset display-6 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">24/7 Guest Support</h3>
                        <p class="text-muted">We handle complaints and FAQs so you can focus on delivering fun.</p>
                        <small class="text-primary fw-bold">Essential for adventure activities</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Builders -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Trusted by Experience Creators</h2>
                <p class="lead text-muted">Join the movement redefining Lithuanian adventures</p>
            </div>
            
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <div class="card bg-light border-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <img src="https://randomuser.me/api/portraits/women/32.jpg" class="rounded-circle img-fluid" width="100" alt="Testimonial">
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="fst-italic">"As a new paintball park, AdventureHub filled our calendar when no one knew we existed. Their marketing team is incredible!"</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="fw-bold mb-0">Gabija</p>
                                            <p class="text-muted small mb-0">Vilnius Paintball Adventures</p>
                                        </div>
                                        <small class="text-muted">Future Partner</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <h4 class="h5 fw-bold mb-4">Featured In</h4>
                <div class="d-flex flex-wrap justify-content-center gap-4">
                    <img src="https://via.placeholder.com/120x60?text=Travel+Lithuania" class="partner-badge" alt="Travel Lithuania">
                    <img src="https://via.placeholder.com/120x60?text=Adventure+Mag" class="partner-badge" alt="Adventure Magazine">
                    <img src="https://via.placeholder.com/120x60?text=Go+Vilnius" class="partner-badge" alt="Go Vilnius">
                    <img src="https://via.placeholder.com/120x60?text=Outdoor+LT" class="partner-badge" alt="Outdoor LT">
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-5">
        <div class="container" bis_skin_checked="1">
            <div class="row justify-content-center mb-5" bis_skin_checked="1">
                <div class="col-lg-8 text-center" bis_skin_checked="1">
                    <h2 class="fw-bold mb-3">Start Earning in 4 Simple Steps</h2>
                    <p class="lead text-muted">Our streamlined process takes just minutes to set up</p>
                </div>
            </div>
            
            <div class="row g-4" bis_skin_checked="1">
                <div class="col-md-6 col-lg-3" bis_skin_checked="1">
                    <div class="card step-card p-4 h-100" bis_skin_checked="1">
                        <div class="step-number mb-3" bis_skin_checked="1">1</div>
                        <h3 class="h5 fw-bold">Sign Up</h3>
                        <p class="text-muted">5-minute registration. No documents needed.</p>
                        <div class="mt-3" bis_skin_checked="1">
                            <div class="ratio ratio-16x9 bg-light rounded" bis_skin_checked="1"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" bis_skin_checked="1">
                    <div class="card step-card p-4 h-100" bis_skin_checked="1">
                        <div class="step-number mb-3" bis_skin_checked="1">2</div>
                        <h3 class="h5 fw-bold">List Services</h3>
                        <p class="text-muted">Upload services with photos/prices. Our AI helps optimize listings.</p>
                        <div class="mt-3" bis_skin_checked="1">
                            <div class="ratio ratio-16x9 bg-light rounded" bis_skin_checked="1"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" bis_skin_checked="1">
                    <div class="card step-card p-4 h-100" bis_skin_checked="1">
                        <div class="step-number mb-3" bis_skin_checked="1">3</div>
                        <h3 class="h5 fw-bold">Get Booked</h3>
                        <p class="text-muted">Customers book/pay securely. You get instant notifications.</p>
                        <div class="mt-3" bis_skin_checked="1">
                            <div class="ratio ratio-16x9 bg-light rounded" bis_skin_checked="1"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" bis_skin_checked="1">
                    <div class="card step-card p-4 h-100" bis_skin_checked="1">
                        <div class="step-number mb-3" bis_skin_checked="1">4</div>
                        <h3 class="h5 fw-bold">Get Paid</h3>
                        <p class="text-muted">We handle invoicing. Money hits your account in 1 day.</p>
                        <div class="mt-3" bis_skin_checked="1">
                            <div class="ratio ratio-16x9 bg-light rounded" bis_skin_checked="1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration CTA -->
    <section id="register" class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-3">Ready to Grow Your Adventure Business?</h2>
                    <p class="lead mb-4">Join our founding partners today. Limited spots available in each category.</p>
                    <a href="#register-form" class="btn btn-light btn-lg px-5 py-3">Start My Journey</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Form -->
    <section id="register-form" class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white p-4">
                            <h2 class="h4 mb-0">Become a Partner</h2>
                            <p class="mb-0">Complete your profile in 5 minutes</p>
                        </div>
                        <div class="card-body p-4">
                            <form>
                                <div class="mb-4">
                                    <label for="businessName" class="form-label">Business Name</label>
                                    <input type="text" class="form-control form-control-lg" id="businessName" required>
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control form-control-lg" id="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control form-control-lg" id="phone" required>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="serviceType" class="form-label">What type of experiences do you offer?</label>
                                    <select class="form-select form-select-lg" id="serviceType" required>
                                        <option value="" selected disabled>Select category</option>
                                        <option value="adventure">Adventure Activities</option>
                                        <option value="wellness">Wellness & Spa</option>
                                        <option value="tours">Tours & Sightseeing</option>
                                        <option value="other">Other Experiences</option>
                                    </select>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3">Join AdventureHub</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- FAQ Section -->
    <section id="faq" class="py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold">Your Questions Answered</h2>
                        <p class="lead text-muted">Everything partners ask before joining</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="h5 fw-bold">How do I get more bookings?</h3>
                        <p class="mb-0">Our algorithm prioritizes active partners with complete profiles. Tip: Upload at least 5 high-quality photos and set your availability 2 weeks in advance to appear in "Recommended" sections.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="h5 fw-bold">What about insurance?</h3>
                        <p class="mb-0">All bookings include €1M liability coverage. For extreme activities, we recommend supplemental insurance (we partner with providers for 20% discounts).</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="h5 fw-bold">Can I use my existing booking system?</h3>
                        <p class="mb-0">Yes! Sync via Google Calendar or our API. However, 87% of partners switch fully within 3 months for the automated features.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="h5 fw-bold">How do cancellations work?</h3>
                        <p class="mb-0">Guests pay upfront. You keep:<br>
                        - 100% if canceled ≤24h before<br>
                        - 50% if canceled ≤72h before<br>
                        - 0% for no-shows (we ban repeat offenders)</p>
                    </div>
                    
                    <div class="faq-item border-bottom-0 pb-0">
                        <h3 class="h5 fw-bold">What makes a great listing?</h3>
                        <p class="mb-0">Top-performing listings include:<br>
                        - A 30-sec intro video<br>
                        - 5+ action shots (no stock photos)<br>
                        - Clear "What Makes Us Unique" section<br>
                        - Evening/weekend availability</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h3 class="h5 fw-bold mb-3"><i class="bi bi-compass me-2"></i>AdventureHub</h3>
                    <p>Connecting adventure seekers with unforgettable experiences across Lithuania.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h3 class="h5 fw-bold mb-3">Quick Links</h3>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#story" class="text-white-50 text-decoration-none">Our Story</a></li>
                        <li class="mb-2"><a href="#benefits" class="text-white-50 text-decoration-none">Partner Benefits</a></li>
                        <li><a href="#faq" class="text-white-50 text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h3 class="h5 fw-bold mb-3">Contact Us</h3>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> partners@adventurehub.lt</li>
                        <li><i class="bi bi-telephone me-2"></i> +370 123 45678</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="text-center text-white-50">
                &copy; 2023 AdventureHub. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activate tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Animate stats
        function animateValue(id, start, end, duration) {
            var obj = document.getElementById(id);
            var range = end - start;
            var current = start;
            var increment = end > start ? 1 : -1;
            var stepTime = Math.abs(Math.floor(duration / range));
            var timer = setInterval(function() {
                current += increment;
                obj.innerHTML = current;
                if (current == end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }
        
        // Trigger animations when scrolling to sections
        window.addEventListener('scroll', function() {
            var statsSection = document.getElementById('stats');
            var position = statsSection.getBoundingClientRect().top;
            var screenPosition = window.innerHeight / 1.3;
            
            if(position < screenPosition) {
                animateValue("partners-count", 0, 850, 2000);
                animateValue("bookings-count", 0, 124, 1500);
                animateValue("payouts-count", 0, 28560, 2500);
            }
        });
    </script>
</body>
</html>