<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings   = Setting::all()->keyBy('key');
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.settings.index', compact('settings', 'categories'));
    }

    public function update(Request $request, string $group)
    {
        $data = $request->except(['_token', '_method', 'footer_logo_file', 'popup_bg_image_file']);

        // Handle footer logo upload
        if ($request->hasFile('footer_logo_file')) {
            $request->validate(['footer_logo_file' => 'image|max:2048']);
            $old = Setting::get('theme_footer_logo');
            if ($old) Storage::disk('public')->delete($old);
            $data['theme_footer_logo'] = $request->file('footer_logo_file')->store('settings', 'public');
        }

        // Checkboxes (unchecked = absent from request)
        $checkboxKeys = ['site_maintenance', 'bar_enabled', 'popup_enabled', 'showcase2_enabled', 'showcase2_dark_bg'];
        foreach ($checkboxKeys as $k) {
            if (!isset($data[$k])) {
                $data[$k] = '0';
            }
        }

        Setting::setMany($data, $group);

        return back()->with('success', 'Configurações salvas com sucesso!');
    }
}
