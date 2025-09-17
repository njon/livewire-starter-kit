# Newsletter System

This application includes a flexible newsletter subscription system with a beautiful modal popup and easy provider switching.

## Features

- ✅ **Beautiful Auto Modal Popup** - Shows for new visitors after 10 seconds or 50% scroll
- ✅ **Subscribe/Unsubscribe Functionality** - Full CRUD operations
- ✅ **Provider Interface** - Easy to switch between different email service providers
- ✅ **Local Storage** - Remembers user preferences and prevents modal spam
- ✅ **Responsive Design** - Works perfectly on mobile and desktop
- ✅ **Multiple Triggers** - Time-based, scroll-based, and exit-intent triggers
- ✅ **Admin Unsubscribe Page** - Standalone page for unsubscribing

## Quick Start

1. **Run the migration** (once PHP is working):
   ```bash
   php artisan migrate
   ```

2. **The modal will automatically appear** on your frontend for new visitors.

3. **Access the unsubscribe page** at `/newsletter/unsubscribe`

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Newsletter Provider (database or mailchimp)
NEWSLETTER_PROVIDER=database

# Modal Settings
NEWSLETTER_MODAL_ENABLED=true
NEWSLETTER_MODAL_DELAY=10
NEWSLETTER_MODAL_SCROLL_PERCENT=50
NEWSLETTER_MODAL_HIDE_DAYS=7

# Mailchimp Settings (if using mailchimp provider)
MAILCHIMP_API_KEY=your_api_key_here
MAILCHIMP_LIST_ID=your_list_id_here
```

### Provider Configuration

Edit `config/newsletter.php` to customize modal behavior and provider settings.

## Switching Providers

### Currently Available Providers

1. **Database Provider** (default) - Stores subscriptions in local database
2. **Mailchimp Provider** - Integrates with Mailchimp API (requires setup)

### To Switch to Mailchimp:

1. Set `NEWSLETTER_PROVIDER=mailchimp` in your `.env`
2. Add your Mailchimp credentials to `.env`
3. Install Guzzle HTTP client: `composer require guzzlehttp/guzzle`
4. Implement the actual HTTP calls in `MailchimpNewsletterProvider.php`

### Creating Custom Providers

1. Create a new class implementing `App\Contracts\NewsletterProviderInterface`
2. Add it to the match statement in `NewsletterServiceProvider.php`
3. Configure it in your `.env` file

Example:
```php
// app/Services/Newsletter/ConvertKitNewsletterProvider.php
class ConvertKitNewsletterProvider implements NewsletterProviderInterface
{
    public function subscribe(string $email, array $data = []): bool
    {
        // Your ConvertKit API implementation
    }

    // ... implement other methods
}
```

## API Endpoints

- `POST /newsletter/subscribe` - Subscribe an email
- `POST /newsletter/unsubscribe` - Unsubscribe an email
- `POST /newsletter/status` - Check subscription status
- `GET /newsletter/unsubscribe` - Unsubscribe page

## Modal Behavior

The modal will show for new visitors based on these triggers:

1. **Time-based**: After 10 seconds (configurable)
2. **Scroll-based**: When user scrolls 50% down the page (configurable)
3. **Exit-intent**: When mouse leaves the viewport (desktop only)

The modal respects user preferences:
- Won't show again if user has subscribed
- Won't show for 7 days if user has dismissed it
- Stores preferences in localStorage

## Database Schema

The `newsletters` table includes:
- `email` - Subscriber email (unique)
- `name` - Optional subscriber name
- `is_active` - Active subscription status
- `subscribed_at` - Subscription timestamp
- `unsubscribed_at` - Unsubscription timestamp

## JavaScript API

You can manually trigger the modal:

```javascript
// Show the modal
showNewsletterModal();

// Close the modal
closeNewsletterModal();

// Access the modal instance
window.newsletterModal.show();
window.newsletterModal.close();
```

## Customization

### Modal Styling

Edit `resources/views/components/newsletter-modal.blade.php` to customize the modal appearance.

### Modal Behavior

Edit `public/js/newsletter.js` to customize triggers and behavior.

### Backend Logic

Edit `app/Services/NewsletterService.php` to customize subscription logic.

## Testing

Test the newsletter functionality:

1. Visit the homepage
2. Wait 10 seconds or scroll down 50%
3. The modal should appear
4. Test subscribing with a valid email
5. Visit `/newsletter/unsubscribe` to test unsubscribing

## Troubleshooting

**Modal not showing:**
- Check if `NEWSLETTER_MODAL_ENABLED=true` in `.env`
- Clear localStorage to reset dismissal state
- Check browser console for JavaScript errors

**Subscription not working:**
- Verify CSRF token is properly set
- Check server logs for PHP errors
- Ensure the migration has been run

**Provider issues:**
- Verify your provider is correctly configured in `.env`
- Check API credentials for external providers
- Review provider-specific documentation