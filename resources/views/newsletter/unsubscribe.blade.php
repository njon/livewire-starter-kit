@extends('layouts.app')

@section('title', 'Unsubscribe from Newsletter')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="#6B7280"/>
                        </svg>
                    </div>

                    <h2 class="mb-3">Unsubscribe from Newsletter</h2>
                    <p class="text-muted mb-4">
                        We're sorry to see you go! Enter your email address to unsubscribe from our newsletter.
                    </p>

                    <form id="unsubscribe-form">
                        @csrf
                        <div class="mb-3">
                            <input
                                type="email"
                                class="form-control form-control-lg"
                                id="email"
                                name="email"
                                placeholder="Enter your email address"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 mb-3">
                            <span class="btn-text">Unsubscribe</span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Processing...
                            </span>
                        </button>

                        <div id="message" class="alert" style="display: none;"></div>

                        <p class="text-muted small">
                            <a href="/" class="text-decoration-none">Return to homepage</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('unsubscribe-form');
    const messageEl = document.getElementById('message');
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = form.querySelector('#email').value.trim();

        if (!email) {
            showMessage('Please enter your email address.', 'danger');
            return;
        }

        setLoading(true);

        try {
            const response = await fetch('/newsletter/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (data.success) {
                showMessage(data.message, 'success');
                form.querySelector('#email').value = '';
            } else {
                showMessage(data.message || 'An error occurred. Please try again.', 'danger');
            }
        } catch (error) {
            console.error('Unsubscribe error:', error);
            showMessage('An error occurred. Please try again.', 'danger');
        } finally {
            setLoading(false);
        }
    });

    function setLoading(loading) {
        if (loading) {
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        } else {
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
        }
    }

    function showMessage(message, type) {
        messageEl.textContent = message;
        messageEl.className = `alert alert-${type}`;
        messageEl.style.display = 'block';

        setTimeout(() => {
            messageEl.style.display = 'none';
        }, 5000);
    }
});
</script>
@endsection