<div id="newsletter-modal" class="newsletter-modal" style="display: none;">
    <div class="newsletter-modal-overlay" onclick="closeNewsletterModal()"></div>
    <div class="newsletter-modal-content">
        <button class="newsletter-modal-close" onclick="closeNewsletterModal()">&times;</button>

        <div class="newsletter-modal-body">
            <div class="newsletter-icon">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="#4F46E5"/>
                </svg>
            </div>

            <h2 class="newsletter-title">Stay in the Loop!</h2>
            <p class="newsletter-description">
                Get exclusive offers, product updates, and insider tips delivered straight to your inbox.
                Join thousands of satisfied customers who never miss out on the best deals.
            </p>

            <form id="newsletter-form" class="newsletter-form">
                <div class="newsletter-input-group">
                    <input
                        type="text"
                        id="newsletter-name"
                        name="name"
                        placeholder="Your name (optional)"
                        class="newsletter-input"
                    >
                </div>

                <div class="newsletter-input-group">
                    <input
                        type="email"
                        id="newsletter-email"
                        name="email"
                        placeholder="Enter your email address"
                        class="newsletter-input"
                        required
                    >
                </div>

                <button type="submit" class="newsletter-submit-btn">
                    <span class="newsletter-btn-text">Subscribe Now</span>
                    <span class="newsletter-btn-loading" style="display: none;">
                        <svg class="newsletter-spinner" width="20" height="20" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/>
                            <path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" fill="currentColor"/>
                        </svg>
                        Subscribing...
                    </span>
                </button>

                <div id="newsletter-message" class="newsletter-message" style="display: none;"></div>

                <p class="newsletter-privacy">
                    <small>
                        We respect your privacy. <a href="{{ route('newsletter.unsubscribe.page') }}" target="_blank">Unsubscribe at any time</a>.
                        <a href="#" onclick="closeNewsletterModal(); return false;">No thanks</a>
                    </small>
                </p>
            </form>

            <div id="newsletter-success" class="newsletter-success" style="display: none;">
                <div class="newsletter-success-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="#10B981"/>
                    </svg>
                </div>
                <h3>Welcome aboard! 🎉</h3>
                <p>Thank you for subscribing! Check your inbox for a welcome email.</p>
                <button onclick="closeNewsletterModal()" class="newsletter-close-btn">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
.newsletter-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    animation: fadeIn 0.3s ease-out;
}

.newsletter-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
}

.newsletter-modal-content {
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    max-width: 480px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease-out;
}

.newsletter-modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: none;
    border: none;
    font-size: 28px;
    color: #6B7280;
    cursor: pointer;
    z-index: 1;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.newsletter-modal-close:hover {
    background: #F3F4F6;
    color: #374151;
}

.newsletter-modal-body {
    padding: 40px;
    text-align: center;
}

.newsletter-icon {
    margin-bottom: 24px;
}

.newsletter-title {
    font-size: 28px;
    font-weight: bold;
    color: #111827;
    margin-bottom: 16px;
    line-height: 1.2;
}

.newsletter-description {
    color: #6B7280;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 32px;
}

.newsletter-form {
    text-align: left;
}

.newsletter-input-group {
    margin-bottom: 16px;
}

.newsletter-input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.2s ease;
    background: #F9FAFB;
}

.newsletter-input:focus {
    outline: none;
    border-color: #4F46E5;
    background: white;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.newsletter-submit-btn {
    width: 100%;
    padding: 16px 20px;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    margin-bottom: 16px;
}

.newsletter-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
}

.newsletter-submit-btn:active {
    transform: translateY(0);
}

.newsletter-submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.newsletter-spinner {
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

.newsletter-message {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
}

.newsletter-message.success {
    background: #D1FAE5;
    color: #065F46;
    border: 1px solid #A7F3D0;
}

.newsletter-message.error {
    background: #FEE2E2;
    color: #991B1B;
    border: 1px solid #FECACA;
}

.newsletter-privacy {
    color: #9CA3AF;
    text-align: center;
    margin: 0;
    line-height: 1.5;
}

.newsletter-privacy a {
    color: #4F46E5;
    text-decoration: none;
}

.newsletter-privacy a:hover {
    text-decoration: underline;
}

.newsletter-success {
    text-align: center;
}

.newsletter-success-icon {
    margin-bottom: 20px;
}

.newsletter-success h3 {
    color: #111827;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 12px;
}

.newsletter-success p {
    color: #6B7280;
    font-size: 16px;
    margin-bottom: 24px;
    line-height: 1.5;
}

.newsletter-close-btn {
    padding: 12px 24px;
    background: #F3F4F6;
    color: #374151;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.newsletter-close-btn:hover {
    background: #E5E7EB;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translate(-50%, -40%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@media (max-width: 640px) {
    .newsletter-modal-content {
        width: 95%;
        margin: 20px;
    }

    .newsletter-modal-body {
        padding: 30px 24px;
    }

    .newsletter-title {
        font-size: 24px;
    }

    .newsletter-description {
        font-size: 15px;
    }
}
</style>