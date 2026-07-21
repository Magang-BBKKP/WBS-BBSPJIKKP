<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize('view-settings');

        $settings = [
            'site_name'        => Setting::get('site_name', 'WBS BBSPJIKKP'),
            'site_description' => Setting::get('site_description', 'Website Whistleblowing System BBSPJIKKP'),
            'contact_email'    => Setting::get('contact_email', ''),
            'contact_phone'    => Setting::get('contact_phone', ''),
            'contact_address'  => Setting::get('contact_address', ''),
            'logo_path'        => Setting::get('logo_path', ''),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        Gate::authorize('update-settings');

        $request->validate([
            'site_name'        => 'required|string|max:100',
            'site_description' => 'nullable|string|max:255',
            'contact_email'    => 'nullable|email|max:100',
            'contact_phone'    => 'nullable|string|max:30',
            'contact_address'  => 'nullable|string|max:500',
            'logo'             => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        // Save settings
        Setting::set('site_name',        $request->site_name);
        Setting::set('site_description', $request->site_description ?? '');
        Setting::set('contact_email',    $request->contact_email ?? '');
        Setting::set('contact_phone',    $request->contact_phone ?? '');
        Setting::set('contact_address',  $request->contact_address ?? '');

        // Upload logo
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo_path');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo_path', $path);
        }

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Update Pengaturan Sistem',
            'description' => 'Admin memperbarui pengaturan sistem.',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
