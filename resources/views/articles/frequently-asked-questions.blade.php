@extends('layouts.app')

@section('title', 'Gift Experiences')

@section('top-content')

<div class="container py-5">
    <div class="row">
            <div class="container-fluid about-page">
                <!-- 1. The Heart of Our Brand -->
                <section class="row hero-section align-items-center py-5">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold mb-4">The Heart of Our Brand</h1>
                        <p class="lead mb-4">We started with a handshake between two local businesses—now we're a
                            network of 100+ experiences.</p>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://via.placeholder.com/600x400" alt="Founder with local partner"
                            class="img-fluid rounded shadow">
                    </div>
                </section>

                <!-- 2. Why Local Matters -->
                <section class="row py-5 bg-light">
                    <div class="col-12 text-center mb-5">
                        <h2 class="fw-bold">Why Local Matters</h2>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title">70% of travel dollars leave communities</h3>
                                <p class="card-text">We keep 100% of profits local by partnering directly with
                                    experience providers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title">3x more impact</h3>
                                <p class="card-text">Local businesses reinvest in their communities at triple the rate
                                    of chains.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. Our Impact in Action -->
                <section class="row py-5">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h2 class="fw-bold mb-4">Our Impact in Action</h2>
                        <div class="accordion" id="impactAccordion">
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#environmental">
                                        Environmental
                                    </button>
                                </h3>
                                <div id="environmental" class="accordion-collapse collapse show"
                                    data-bs-parent="#impactAccordion">
                                    <div class="accordion-body">
                                        <p>Reduced 15 tons of carbon through local experiences last year.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#social">
                                        Social
                                    </button>
                                </h3>
                                <div id="social" class="accordion-collapse collapse" data-bs-parent="#impactAccordion">
                                    <div class="accordion-body">
                                        <p>Created 42 new local jobs in the past 6 months.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/example" title="Impact video"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </section>

                <!-- 4. Partner Spotlight -->
                <section class="row py-5 bg-light">
                    <div class="col-12 text-center mb-5">
                        <h2 class="fw-bold">Partner Spotlight</h2>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Partner business">
                            <div class="card-body">
                                <h3 class="h5 card-title">Mountain Brew Co.</h3>
                                <p class="card-text">Family-owned microbrewery offering sustainable brewing workshops.
                                </p>
                                <a href="#" class="btn btn-outline-primary">View Experience</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Partner business">
                            <div class="card-body">
                                <h3 class="h5 card-title">Green Thumb Farms</h3>
                                <p class="card-text">Organic farm providing hands-on harvest-to-table programs.</p>
                                <a href="#" class="btn btn-outline-primary">View Experience</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Partner business">
                            <div class="card-body">
                                <h3 class="h5 card-title">Coastal Artisans</h3>
                                <p class="card-text">Collective of traditional craft makers preserving cultural
                                    heritage.</p>
                                <a href="#" class="btn btn-outline-primary">View Experience</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <a href="#" class="btn btn-primary">Meet All Our Partners</a>
                    </div>
                </section>

                <!-- 5. How We Curate Experiences -->
                <section class="row py-5">
                    <div class="col-lg-6 order-lg-2">
                        <h2 class="fw-bold mb-4">How We Curate Experiences</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-start">
                                <span class="badge bg-primary rounded-circle me-3 p-2">1</span>
                                <div>
                                    <h3 class="h6 mb-1">Local Connection</h3>
                                    <p class="mb-0">Must be locally owned and operated for 3+ years</p>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="badge bg-primary rounded-circle me-3 p-2">2</span>
                                <div>
                                    <h3 class="h6 mb-1">Sustainability Check</h3>
                                    <p class="mb-0">Minimum 5 eco-friendly practices in place</p>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <span class="badge bg-primary rounded-circle me-3 p-2">3</span>
                                <div>
                                    <h3 class="h6 mb-1">Quality Testing</h3>
                                    <p class="mb-0">Our team personally vets every experience</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <img src="https://via.placeholder.com/600x400" alt="Vetting process"
                            class="img-fluid rounded shadow">
                    </div>
                </section>

                <!-- 6. Join the Movement -->
                <section class="row py-5 bg-primary text-white text-center">
                    <div class="col-12 mb-4">
                        <h2 class="fw-bold">Join the Movement</h2>
                        <p class="lead">Be part of something bigger than just a trip</p>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card h-100 bg-white text-dark">
                            <div class="card-body p-4">
                                <h3 class="h4 card-title">Experience Seeker</h3>
                                <p class="card-text">Book authentic local experiences that give back</p>
                                <a href="#" class="btn btn-primary">Browse Experiences</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 bg-white text-dark">
                            <div class="card-body p-4">
                                <h3 class="h4 card-title">Local Business</h3>
                                <p class="card-text">Partner with us to grow sustainably</p>
                                <a href="#" class="btn btn-primary">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 7. Fast Facts -->
                <section class="row py-5">
                    <div class="col-12 text-center mb-5">
                        <h2 class="fw-bold">Fast Facts</h2>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="display-4 fw-bold text-primary" data-counter="200">0</div>
                        <p class="text-muted">Experiences</p>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="display-4 fw-bold text-primary" data-counter="120">0</div>
                        <p class="text-muted">Local Partners</p>
                    </div>
                    <div class="col-6 col-md-3 text-center mt-4 mt-md-0">
                        <div class="display-4 fw-bold text-primary" data-counter="5">0</div>
                        <p class="text-muted">Years Operating</p>
                    </div>
                    <div class="col-6 col-md-3 text-center mt-4 mt-md-0">
                        <div class="display-4 fw-bold text-primary" data-counter="98">0</div>
                        <p class="text-muted">% Satisfaction</p>
                    </div>
                </section>

                <!-- 8. Press & Recognition -->
                <section class="row py-5 bg-light">
                    <div class="col-12 text-center mb-5">
                        <h2 class="fw-bold">Press & Recognition</h2>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-5">
                            <img src="https://via.placeholder.com/150x80" alt="Press logo" class="img-fluid grayscale"
                                style="max-height: 60px;">
                            <img src="https://via.placeholder.com/150x80" alt="Press logo" class="img-fluid grayscale"
                                style="max-height: 60px;">
                            <img src="https://via.placeholder.com/150x80" alt="Press logo" class="img-fluid grayscale"
                                style="max-height: 60px;">
                            <img src="https://via.placeholder.com/150x80" alt="Press logo" class="img-fluid grayscale"
                                style="max-height: 60px;">
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple counter animation for Fast Facts section
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('[data-counter]');
        const speed = 200;
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-counter');
            const count = +counter.innerText;
            const increment = target / speed;
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target;
            }

            function updateCount() {
                const count = +counter.innerText;
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            }
        });
    });
</script>

<style>
    .accordion-button:not(.collapsed) {
        background-color: rgba(0, 123, 255, 0.1);
        color: #0d6efd;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0, 123, 255, 0.25);
    }

    .faq-header {
        background: linear-gradient(135deg, #3e7a2f 0%, #98b68a 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 10px 10px;
    }

    h2 {
        font-size: 1.2rem !important;
        font-weight: 600;
        color: black !important;
    }

    button.accordion-button {
        font-size: 15px !important;
    }
</style>
<div class="faq-header text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Frequently Asked Questions</h1>
        <p class="lead">Find quick answers to common questions about our gift vouchers</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion" id="faqAccordion">

                <!-- Order & Delivery Section -->
                <h2 class="mt-4 mb-3 text-primary">Order & Delivery</h2>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#deliveryTime">
                            How soon will my voucher arrive?
                        </button>
                    </h3>
                    <div id="deliveryTime" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <strong>Digital vouchers</strong> are delivered instantly via email. <strong>Physical
                                vouchers</strong> typically arrive within 3–5 business days (UK).
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#lastMinute">
                            Can I get a digital gift voucher last-minute?
                        </button>
                    </h3>
                    <div id="lastMinute" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! E-vouchers are sent immediately after purchase—perfect for same-day gifting.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#missingOrder">
                            What if my order hasn't arrived?
                        </button>
                    </h3>
                    <div id="missingOrder" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Check your spam folder for e-vouchers. For physical orders, contact us at <a
                                href="mailto:support@example.com">support@example.com</a> with your order number.
                        </div>
                    </div>
                </div>

                <!-- E-Vouchers & Gift Cards Section -->
                <h2 class="mt-5 mb-3 text-primary">E-Vouchers & Gift Cards</h2>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#voucherAppearance">
                            What does an e-voucher look like?
                        </button>
                    </h3>
                    <div id="voucherAppearance" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Our e-vouchers include a custom design, your personal message, and redemption instructions.
                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">View example</a>.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#voucherValidity">
                            How long is my voucher valid?
                        </button>
                    </h3>
                    <div id="voucherValidity" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Most vouchers valid for 12 months if not specified otherwise on the service page.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#extendVoucher">
                            Can I extend my voucher's expiration date?
                        </button>
                    </h3>
                    <div id="extendVoucher" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p><strong>Yes!</strong> You can extend your voucher's validity if requested before the
                                expiration date:</p>

                            <h6 class="mt-3">How to request an extension:</h6>
                            <ol>
                                <li>Email <a href="mailto:support@example.com">support@example.com</a> with your voucher
                                    number</li>
                                <li>Use subject line: "Voucher Extension Request - [Your Order Number]"</li>
                                <li>We'll process your request within 24 hours</li>
                            </ol>

                            <h6 class="mt-3">Extension fees:</h6>
                            <ul class="list-unstyled">
                                <li>• €10 for vouchers up to €30 value</li>
                                <li>• €20 for vouchers between €31-€60</li>
                                <li>• €30 for vouchers between €61-€100</li>
                                <li>• €40 for vouchers over €100</li>
                            </ul>

                        </div>
                    </div>
                </div>

                <!-- Continue with other sections following the same pattern -->
                <!-- Personalization & Gifting -->
                <h2 class="mt-5 mb-3 text-primary">Personalization & Gifting</h2>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#videoMessage">
                            Can I add a custom text or greeting card?
                        </button>
                    </h3>
                    <div id="videoMessage" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! Select "Add Greeting Card" and your text.
                        </div>
                    </div>
                </div>

                <!-- Discounts & Payments -->
                <h2 class="mt-5 mb-3 text-primary">Discounts & Payments</h2>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#promoCode">
                            Can I use a promo code with a gift card?
                        </button>
                    </h3>
                    <div id="promoCode" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            No, gift cards and promo codes can't be combined.
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Section -->
                <h2 class="mt-5 mb-3 text-primary">Payment Methods</h2>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#paymentOptions">
                            What payment methods do you accept?
                        </button>
                    </h3>
                    <div id="paymentOptions" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We accept all major payment options for your convenience:
                            <ul class="mt-2 mb-2">
                                <li>Credit/Debit Cards (Visa, Mastercard, American Express)</li>
                                <li>PayPal</li>
                                <li>Apple Pay</li>
                                <li>Google Pay</li>
                                <li>Gift Cards/Vouchers</li>
                                <li>Bank Transfers</li>
                            </ul>
                            All payments are processed securely through our PCI-compliant payment gateway.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#securePayments">
                            Is my payment information secure?
                        </button>
                    </h3>
                    <div id="securePayments" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <strong>Yes, absolutely.</strong> We use industry-standard 256-bit SSL encryption and never
                            store your full payment details on our servers. All transactions are processed through our
                            PCI-DSS compliant payment partners.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#multiPayment">
                            Can I use multiple payment methods for one order?
                        </button>
                    </h3>
                    <div id="multiPayment" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Unfortunately, we only accept one payment method per order. If you wish to split payment,
                            please place separate orders.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#currencyOptions">
                            What currencies do you accept?
                        </button>
                    </h3>
                    <div id="currencyOptions" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We currently accept payments only in
                            <span class="badge bg-primary">EUR (€)</span>

                        </div>
                    </div>
                </div>

                <!-- Refunds & Support -->
                <h2 class="mt-5 mb-3 text-primary">Refunds & Support</h2>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#refundPolicy">
                            What's your refund policy?
                        </button>
                    </h3>
                    <div id="refundPolicy" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Unused vouchers can be refunded within 30 days. Used vouchers are non-refundable. <br>Refund
                            will be processed to the original payment method.
                        </div>
                    </div>
                </div>

                <!-- Experiences -->
                <h2 class="mt-5 mb-3 text-primary">Experiences</h2>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accessibility">
                            Are experiences wheelchair accessible?
                        </button>
                    </h3>
                    <div id="accessibility" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Many are! Filter for "Accessible Experiences" or contact us for details.
                        </div>
                    </div>
                </div>

                <!-- Troubleshooting -->
                <h2 class="mt-5 mb-3 text-primary">Troubleshooting</h2>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#giftCardIssue">
                            My gift card isn't working
                        </button>
                    </h3>
                    <div id="giftCardIssue" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ensure the code is entered correctly. If issues persist, email us at <a
                                href="mailto:support@example.com">support@example.com</a>.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Example Modal for Voucher Preview -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">E-Voucher Example</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                @include('voucher.index')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection