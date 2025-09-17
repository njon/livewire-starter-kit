// Newsletter Modal JavaScript
class NewsletterModal {
    constructor() {
        this.modal = null;
        this.form = null;
        this.isVisible = false;
        this.hasBeenShown = false;
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.modal = document.getElementById('newsletter-modal');
        this.form = document.getElementById('newsletter-form');

        if (!this.modal || !this.form) {
            console.warn('Newsletter modal elements not found');
            return;
        }

        this.bindEvents();
        this.schedulePopup();
    }

    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isVisible) {
                this.close();
            }
        });

        // Prevent modal from closing when clicking inside content
        const modalContent = this.modal.querySelector('.newsletter-modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => e.stopPropagation());
        }
    }

    schedulePopup() {
        // Check if modal is enabled
        if (!window.newsletterConfig?.enabled) {
            return;
        }

        // Check if user has already subscribed or dismissed
        const hasSubscribed = localStorage.getItem('newsletter_subscribed');
        const hasDismissed = localStorage.getItem('newsletter_dismissed');
        const lastShown = localStorage.getItem('newsletter_last_shown');

        // Don't show if already subscribed
        if (hasSubscribed === 'true') {
            return;
        }

        // Don't show if dismissed recently
        if (hasDismissed && lastShown) {
            const hideDays = window.newsletterConfig?.hideDays || 7;
            const daysSinceLastShown = (Date.now() - parseInt(lastShown)) / (1000 * 60 * 60 * 24);
            if (daysSinceLastShown < hideDays) {
                return;
            }
        }

        // Show modal based on configured triggers
        this.setupTriggers();
    }

    setupTriggers() {
        let timeoutId;
        let scrollTriggered = false;

        const delay = window.newsletterConfig?.delay || 10000;
        const scrollPercent = window.newsletterConfig?.scrollPercent || 50;

        // Time-based trigger
        timeoutId = setTimeout(() => {
            if (!scrollTriggered && !this.hasBeenShown) {
                this.show();
            }
        }, delay);

        // Scroll-based trigger
        const scrollHandler = () => {
            const currentScrollPercent = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;

            if (currentScrollPercent >= scrollPercent && !scrollTriggered && !this.hasBeenShown) {
                scrollTriggered = true;
                clearTimeout(timeoutId);
                this.show();
                window.removeEventListener('scroll', scrollHandler);
            }
        };

        window.addEventListener('scroll', scrollHandler);

        // Exit intent trigger (desktop only)
        if (window.innerWidth > 768) {
            document.addEventListener('mouseleave', (e) => {
                if (e.clientY <= 0 && !this.hasBeenShown) {
                    clearTimeout(timeoutId);
                    this.show();
                }
            });
        }
    }

    show() {
        if (this.hasBeenShown || !this.modal) return;

        this.hasBeenShown = true;
        this.isVisible = true;
        this.modal.style.display = 'block';

        // Prevent body scroll
        document.body.style.overflow = 'hidden';

        // Store last shown timestamp
        localStorage.setItem('newsletter_last_shown', Date.now().toString());

        // Focus on email input after animation
        setTimeout(() => {
            const emailInput = this.modal.querySelector('#newsletter-email');
            if (emailInput) {
                emailInput.focus();
            }
        }, 300);
    }

    close() {
        if (!this.modal || !this.isVisible) return;

        this.isVisible = false;
        this.modal.style.display = 'none';

        // Restore body scroll
        document.body.style.overflow = '';

        // Mark as dismissed
        localStorage.setItem('newsletter_dismissed', 'true');
        localStorage.setItem('newsletter_last_shown', Date.now().toString());
    }

    async handleSubmit(e) {
        e.preventDefault();

        const email = this.form.querySelector('#newsletter-email').value.trim();
        const name = this.form.querySelector('#newsletter-name').value.trim();

        if (!email) {
            this.showMessage('Please enter your email address.', 'error');
            return;
        }

        if (!this.isValidEmail(email)) {
            this.showMessage('Please enter a valid email address.', 'error');
            return;
        }

        this.setLoading(true);

        try {
            const response = await fetch('/newsletter/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: email,
                    name: name
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess();
                localStorage.setItem('newsletter_subscribed', 'true');
                localStorage.removeItem('newsletter_dismissed');
            } else {
                this.showMessage(data.message || 'An error occurred. Please try again.', 'error');
            }
        } catch (error) {
            console.error('Newsletter subscription error:', error);
            this.showMessage('An error occurred. Please try again.', 'error');
        } finally {
            this.setLoading(false);
        }
    }

    setLoading(loading) {
        const submitBtn = this.form.querySelector('.newsletter-submit-btn');
        const btnText = submitBtn.querySelector('.newsletter-btn-text');
        const btnLoading = submitBtn.querySelector('.newsletter-btn-loading');

        if (loading) {
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'flex';
        } else {
            submitBtn.disabled = false;
            btnText.style.display = 'block';
            btnLoading.style.display = 'none';
        }
    }

    showMessage(message, type) {
        const messageEl = this.modal.querySelector('#newsletter-message');
        messageEl.textContent = message;
        messageEl.className = `newsletter-message ${type}`;
        messageEl.style.display = 'block';

        // Hide message after 5 seconds
        setTimeout(() => {
            messageEl.style.display = 'none';
        }, 5000);
    }

    showSuccess() {
        const form = this.modal.querySelector('.newsletter-form');
        const success = this.modal.querySelector('#newsletter-success');

        form.style.display = 'none';
        success.style.display = 'block';

        // Auto-close after 3 seconds
        setTimeout(() => {
            this.close();
        }, 3000);
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
}

// Global functions for inline event handlers
function closeNewsletterModal() {
    if (window.newsletterModal) {
        window.newsletterModal.close();
    }
}

function showNewsletterModal() {
    if (window.newsletterModal) {
        window.newsletterModal.show();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.newsletterModal = new NewsletterModal();
});

// Also initialize if DOM is already ready
if (document.readyState !== 'loading') {
    window.newsletterModal = new NewsletterModal();
}