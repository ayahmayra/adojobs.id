<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'AdoJobs.id'),
            'site_description' => Setting::get('site_description', 'Platform pencarian kerja terbaik di Pulau Bengkalis'),
            'site_logo' => Setting::get('site_logo'),
            'site_favicon' => Setting::get('site_favicon'),
            'site_banner' => Setting::get('site_banner'),
            // Contact Information
            'site_phone' => Setting::get('site_phone', '+62 812-3456-7890'),
            'site_email' => Setting::get('site_email', 'info@adojobs.id'),
            'site_address' => Setting::get('site_address', "Jl. Raya Bengkalis No. 123\nKecamatan Bengkalis\nKabupaten Bengkalis, Riau 28711"),
            'site_whatsapp' => Setting::get('site_whatsapp', '6281234567890'),
            'site_hours_monday_friday' => Setting::get('site_hours_monday_friday', '08:00 - 17:00 WIB'),
            'site_hours_saturday' => Setting::get('site_hours_saturday', '08:00 - 12:00 WIB'),
            'site_hours_sunday' => Setting::get('site_hours_sunday', 'Tutup'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg|max:1024',
            'site_banner' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            // Contact Information
            'site_phone' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_address' => 'nullable|string|max:500',
            'site_whatsapp' => 'nullable|string|max:50',
            'site_hours_monday_friday' => 'nullable|string|max:100',
            'site_hours_saturday' => 'nullable|string|max:100',
            'site_hours_sunday' => 'nullable|string|max:100',
        ]);

        // Update site name and description
        Setting::set('site_name', $request->site_name, 'string', 'general');
        Setting::set('site_description', $request->site_description, 'string', 'general');
        
        // Update contact information
        Setting::set('site_phone', $request->site_phone, 'string', 'contact');
        Setting::set('site_email', $request->site_email, 'string', 'contact');
        Setting::set('site_address', $request->site_address, 'string', 'contact');
        Setting::set('site_whatsapp', $request->site_whatsapp, 'string', 'contact');
        Setting::set('site_hours_monday_friday', $request->site_hours_monday_friday, 'string', 'contact');
        Setting::set('site_hours_saturday', $request->site_hours_saturday, 'string', 'contact');
        Setting::set('site_hours_sunday', $request->site_hours_sunday, 'string', 'contact');

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath, 'string', 'general');
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            // Delete old favicon if exists
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            // Store new favicon
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $faviconPath, 'string', 'general');
        }

        // Handle banner upload
        if ($request->hasFile('site_banner')) {
            // Delete old banner if exists
            $oldBanner = Setting::get('site_banner');
            if ($oldBanner && Storage::disk('public')->exists($oldBanner)) {
                Storage::disk('public')->delete($oldBanner);
            }

            // Store new banner
            $bannerPath = $request->file('site_banner')->store('settings', 'public');
            Setting::set('site_banner', $bannerPath, 'string', 'general');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    /**
     * Delete logo or favicon.
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'type' => 'required|in:logo,favicon,banner',
        ]);

        $key = match($request->type) {
            'logo' => 'site_logo',
            'favicon' => 'site_favicon',
            'banner' => 'site_banner',
            default => 'site_logo',
        };
        $filePath = Setting::get($key);

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            Setting::set($key, null, 'string', 'general');

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' berhasil dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => ucfirst($request->type) . ' tidak ditemukan.'
        ], 404);
    }
}
