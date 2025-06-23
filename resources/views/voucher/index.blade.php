<style>
    :root {
        --primary: rgb(97, 127, 83);
        --secondary: #98B68A;
        --dark: #1a1a2e;
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Inter', sans-serif;
    }

    .voucher-card {
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .header-gradient {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .voucher-code {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px dashed white;
    }

    .checklist-item {
        position: relative;
        padding-left: 2rem;
    }

    .checklist-item:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--primary);
    }
</style>
<div class="container p-0">
    <div class="row justify-content-center">
        <div>
            <!-- Voucher Card -->
            <div class="voucher-card bg-white">
                <!-- Header -->
                <div class="header-gradient text-center p-4 ">
                    <h1>Ellada Experiences</h1>
                    <p class="lead mb-0">Congratulations on Investing in Your Digital Future!</p>
                </div>

                <!-- Body -->
                <div class="p-4 p-md-5">
                    <p class="mb-4">We are thrilled to partner with you on this exciting journey. This voucher confirms
                        your purchase and is the first step towards creating a powerful and beautiful online presence
                        for your brand. We can't wait to bring your vision to life.</p>

                    <!-- Service Details Card - Massage Package -->
                    <div class="bg-white p-0 rounded-2 overflow-hidden mb-4"
                        style="box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                        <!-- Service Header with Image -->
                        <div class="position-relative">
                            <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                                alt="Luxury Massage Therapy" class="img-fluid w-100"
                                style="height: 180px; object-fit: cover;">
                            <div class="position-absolute bottom-0 start-0 p-3">
                                <span class="badge bg-white text-dark fs-6 fw-normal px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-clock-history me-2"></i>Valid for until Dec 31, 2025
                                </span>
                            </div>
                        </div>

                        <!-- Service Content -->
                        <div class="p-4">
                            <h2 class="h5 mb-3 fw-bold">Premium Massage Experience</h2>

                            <div class="row g-3">
                                <!-- Service Type -->
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa fa-leaf classic-color fa-lg me-2"></i>
                                            <h3 class="h6 text-muted mb-0">Treatment</h3>
                                        </div>
                                        <p class="h5 fw-bold mb-0">90min Hot Stone Massage</p>
                                        <p class="text-muted small mt-1">Includes aromatherapy oils</p>
                                    </div>
                                </div>

                                <!-- Date & Time -->
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa fa-calendar-check-o classic-color fa-lg me-2"></i>
                                            <h3 class="h6 text-muted mb-0">Scheduled For</h3>
                                        </div>
                                        <p class="h5 fw-bold mb-0">Nov 15, 2023</p>
                                        <p class="text-muted small mt-1">3:00 PM at Downtown Spa</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Features -->
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle text-success me-1"></i> Free rescheduling
                                    </span>
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-gift text-primary me-1"></i> Complimentary tea
                                    </span>
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-shield-check text-info me-1"></i> Certified therapist
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Voucher Code Highlight -->
                    <div class="text-center mb-5">
                        <div class="position-relative d-inline-block">
                            <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-primary rounded-pill opacity-10"
                                style="transform: rotate(-2deg);"></div>
                            <div
                                class="voucher-code position-relative px-5 py-3 rounded-pill bg-white border border-2 border-primary">
                                <h3 class="h6 text-muted mb-1 text-uppercase small">Your Voucher Code</h3>
                                <p class="display-5 fw-bold mb-0 classic-color" style="letter-spacing: 2px;">
                                    WEBDESIGN2025</p>
                            </div>
                        </div>
                        <p class="text-muted mt-2 small">Present this code at the beginning of our collaboration</p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 mb-3 fw-bold">Locations:</h2>
                        <p>
                            <a class="classic-color" href="https://en.wikipedia.org/wiki/Plaka">Plaka</a>,
                            <a class="classic-color" href="https://en.wikipedia.org/wiki/Monastiraki">Monastiraki</a>,
                            <a class="classic-color" href="https://en.wikipedia.org/wiki/Syntagma_Square">Syntagma</a>,
                            <a class="classic-color" href="https://en.wikipedia.org/wiki/Kolonaki">Kolonaki</a>,
                            <a class="classic-color" href="https://en.wikipedia.org/wiki/Exarcheia">Exarchia</a>,
                            <a class="classic-color" href="https://en.wikipedia.org/wiki/Omonia_Square">Omonia</a>
                        </p>

                        <p class="mb-0">
                            Voucher can be used at any locations mentioned above. Please register your <a
                                href="#">appointment online</a> or by phone call ( +30 669 1214 888 ).</p>
                    </div>

                    <div class="row mb-5">
                        <!-- Things to Consider (70%) -->
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <div class="pe-lg-4">
                                <h2 class="h4 mb-3 fw-bold">Preparing For Your Massage</h2>
                                <ul class="list-unstyled">
                                    <li class="checklist-item mb-3 d-flex align-items-start">
                                        <div>
                                            <strong>Health Information:</strong>
                                            <p class="mb-0 small text-muted">Please inform us about any medical
                                                conditions or areas needing special attention.</p>
                                        </div>
                                    </li>
                                    <li class="checklist-item mb-3 d-flex align-items-start">
                                        <div>
                                            <strong>Preferences:</strong>
                                            <p class="mb-0 small text-muted">Let us know your desired pressure level and
                                                any aromatherapy preferences.</p>
                                        </div>
                                    </li>
                                    <li class="checklist-item mb-3 d-flex align-items-start">
                                        <div>
                                            <strong>Preparation:</strong>
                                            <p class="mb-0 small text-muted">Hydrate well before your session and avoid
                                                heavy meals 2 hours prior.</p>
                                        </div>
                                    </li>
                                    <li class="checklist-item d-flex align-items-start">
                                        <div>
                                            <strong>Arrival Time:</strong>
                                            <p class="mb-0 small text-muted">Please arrive 15 minutes early to complete
                                                paperwork and relax.</p>
                                        </div>
                                    </li>
                                </ul>

                                <div class="alert alert-light mt-4 border-start border-3 border-primary">
                                    <i class="fa fa-info-circle text-primary me-2"></i>
                                    <span class="small">Wear comfortable clothing. We provide all necessary towels and
                                        oils.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Provider Details (30%) -->
                        <div class="col-lg-4">
                            <div class="border-start ps-4">
                                <h2 class="h4 mb-3 fw-bold">Service Provider</h2>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa fa-spa text-primary me-2"></i>
                                    <p class="fw-bold mb-0">Le Massage</p>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa fa-phone text-primary me-2"></i>
                                    <p class="mb-0">+30 669 1214 888</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-envelope text-primary me-2"></i>
                                    <p class="mb-0">email@email.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Footer -->
                    <div class="bg-light p-4 rounded-3 border">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="h5 mb-2 fw-bold text-primary">
                                    <i class="fa fa-life-ring me-2"></i> Need Help With the Platform?
                                </h3>
                                <p class="mb-md-0">Our support team is ready to assist you with any technical questions.
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="tel:+306691214888" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fa fa-phone me-1"></i> Call
                                </a>
                                <a href="mailto:support@experiences.com" class="btn btn-sm btn-primary">
                                    <i class="fa fa-envelope me-1"></i> Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>