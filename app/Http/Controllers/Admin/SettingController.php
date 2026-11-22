<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'admin_commission_pct' => Setting::get('admin_commission_pct', 10.00),
            'supplier_commission_pct' => Setting::get('supplier_commission_pct', 90.00),
            'store_name' => Setting::get('store_name', 'Suntrifting Thrift Consignment'),
            'store_tagline' => Setting::get('store_tagline', 'Curated Preloved & Vintage Streetwear'),
            'bank_bca_number' => Setting::get('bank_bca_number', '8271-9283-71'),
            'bank_bca_holder' => Setting::get('bank_bca_holder', 'PT Suntrifting Karya Mandiri'),
            'bank_mandiri_number' => Setting::get('bank_mandiri_number', '137-00-1928371-0'),
            'bank_mandiri_holder' => Setting::get('bank_mandiri_holder', 'PT Suntrifting Karya Mandiri'),
            'contact_whatsapp' => Setting::get('contact_whatsapp', '081234567890'),
            'contact_email' => Setting::get('contact_email', 'halo@suntrifting.com'),
        ];

        $shippingRates = ShippingRate::orderBy('province')->get();

        return view('admin.settings.index', compact('settings', 'shippingRates'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'admin_commission_pct' => ['required', 'numeric', 'between:0,100'],
            'supplier_commission_pct' => ['required', 'numeric', 'between:0,100'],
            'store_name' => ['required', 'string', 'max:100'],
            'store_tagline' => ['nullable', 'string', 'max:255'],
            'bank_bca_number' => ['required', 'string', 'max:50'],
            'bank_bca_holder' => ['required', 'string', 'max:100'],
            'bank_mandiri_number' => ['required', 'string', 'max:50'],
            'bank_mandiri_holder' => ['required', 'string', 'max:100'],
            'contact_whatsapp' => ['nullable', 'string', 'max:30'],
            'contact_email' => ['nullable', 'email', 'max:100'],
        ]);

        if (round($validated['admin_commission_pct'] + $validated['supplier_commission_pct'], 2) != 100) {
            return back()->withInput()->with('error', 'Total persentase komisi Admin dan Hak Supplier harus tepat 100%.');
        }

        Setting::set('admin_commission_pct', $validated['admin_commission_pct'], 'number', 'financial');
        Setting::set('supplier_commission_pct', $validated['supplier_commission_pct'], 'number', 'financial');
        Setting::set('store_name', $validated['store_name'], 'string', 'general');
        Setting::set('store_tagline', $validated['store_tagline'], 'string', 'general');
        Setting::set('bank_bca_number', $validated['bank_bca_number'], 'string', 'bank');
        Setting::set('bank_bca_holder', $validated['bank_bca_holder'], 'string', 'bank');
        Setting::set('bank_mandiri_number', $validated['bank_mandiri_number'], 'string', 'bank');
        Setting::set('bank_mandiri_holder', $validated['bank_mandiri_holder'], 'string', 'bank');
        Setting::set('contact_whatsapp', $validated['contact_whatsapp'], 'string', 'contact');
        Setting::set('contact_email', $validated['contact_email'], 'string', 'contact');

        return back()->with('success', 'Pengaturan sistem berhasil disimpan.');
    }

    public function updateShippingRate(Request $request, int $id)
    {
        $rate = ShippingRate::findOrFail($id);

        $validated = $request->validate([
            'cost' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $rate->update([
            'cost' => $validated['cost'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Ongkos kirim wilayah ' . $rate->province . ' berhasil diperbarui.');
    }
}

