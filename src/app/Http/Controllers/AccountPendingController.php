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
        $adminPhone = Setting::get('site_phone', '+62 812-3456-7890');
        $adminEmail = Setting::get('site_email', 'info@adojobs.id');
        $siteWhatsapp = Setting::get('site_whatsapp', '6281234567890');
        
        // Format WhatsApp link - use site_whatsapp if available, otherwise format from phone
        if ($siteWhatsapp) {
            // Use site_whatsapp directly (should already be formatted as numbers only)
            $whatsappNumber = preg_replace('/[^0-9]/', '', $siteWhatsapp);
            $whatsappLink = 'https://wa.me/' . $whatsappNumber;
        } else {
            // Fallback to formatting from phone number
            $whatsappPhone = preg_replace('/[^0-9]/', '', $adminPhone);
            $whatsappLink = 'https://wa.me/' . $whatsappPhone;
        }
        
        // Get user email from session or query parameter
        $userEmail = session('email') ?? request('email');
        
        return view('account.pending', compact('adminPhone', 'adminEmail', 'whatsappLink', 'userEmail'));
    }
}

