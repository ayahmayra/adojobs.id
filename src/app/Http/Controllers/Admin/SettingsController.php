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
        ]);

        // Update site name and description
        Setting::set('site_name', $request->site_name, 'string', 'general');
        Setting::set('site_description', $request->site_description, 'string', 'general');

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

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    /**
     * Delete logo or favicon.
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'type' => 'required|in:logo,favicon',
        ]);

        $key = $request->type === 'logo' ? 'site_logo' : 'site_favicon';
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
