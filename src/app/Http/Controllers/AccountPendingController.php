<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountPendingController extends Controller
{
    /**
     * Display the account pending activation page.
     */
    public function index()
    {
        // If user is authenticated and active, redirect to dashboard
        if (Auth::check() && Auth::user()->is_active) {
            return redirect()->route('dashboard');
        }
        
        // If user is authenticated but not active, allow them to see this page
        // If user is not authenticated, also allow (e.g., from registration)

        // Get admin contact information from settings
        $adminPhone = Setting::get('site_phone', '+62 812-760-6351');
        $adminEmail = Setting::get('site_email', 'info@adojobs.id');
        
        // Format phone for WhatsApp (remove spaces, dashes, and other characters)
        $whatsappPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        // Remove country code if it starts with 62
        if (strpos($whatsappPhone, '62') === 0) {
            $whatsappPhone = substr($whatsappPhone, 2);
        }
        // Add WhatsApp link
        $whatsappLink = 'https://wa.me/' . $whatsappPhone;
        
        // Get user email from session or query parameter
        $userEmail = session('email') ?? request('email');
        
        return view('account.pending', compact('adminPhone', 'adminEmail', 'whatsappLink', 'userEmail'));
    }
}

