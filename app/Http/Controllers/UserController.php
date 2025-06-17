<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\InviteFriendsMail;
use Lunar\Models\Order;

class UserController extends Controller
{
    /**
     * Show user profile
     */
    public function showProfile()
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)->get();

        return view('profile.index', compact('orders')); // This would be your blade file with the menu structure
    }

    /**
     * Update profile details
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Show user orders
     */
    public function showOrders()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('profile.orders', compact('orders'));
    }

    /**
     * Show wishlist
     */
    public function showWishlist()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        return view('profile.wishlist', compact('wishlistItems'));
    }

    /**
     * Update newsletter subscription
     */
    public function updateNewsletter(Request $request)
    {
        $validated = $request->validate([
            'newsletter_subscribe' => 'string'
        ]);

        Auth::user()->update([
            'newsletter_subscribed' => $request->has('newsletter_subscribe')
        ]);


        $status = $request->newsletter_subscribe ? 'subscribed to' : 'unsubscribed from';
        
        return back()->with('success', "Successfully $status our newsletter!");
    }

    /**
     * Show bookings
     */
    public function showBookings()
    {
        $bookings = Auth::user()->bookings()->latest()->get();
        return view('profile.bookings', compact('bookings'));
    }

    /**
     * Send friend invitations
     */
    public function sendInvitations(Request $request)
    {
        $validated = $request->validate([
            'emails' => 'required|string',
            'message' => 'nullable|string|max:500'
        ]);

        $emails = array_map('trim', explode(',', $validated['emails']));

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email)->send(new InviteFriendsMail([
                    'sender' => Auth::user(),
                    'message' => $validated['message'] ?? null
                ]));
            }
        }

        return back()->with('success', 'Invitations sent successfully!');
    }

    /**
     * Show account deletion confirmation
     */
    public function showDeleteAccount()
    {
        return view('profile.delete-account');
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }
}