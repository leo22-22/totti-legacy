<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderByDesc('created_at')->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'                 => 'required|string|max:50|unique:coupons,code',
            'type'                 => 'required|in:percentage,fixed',
            'value'                => 'required|numeric|min:0',
            'minimum_order'        => 'nullable|numeric|min:0',
            'maximum_discount'     => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'starts_at'            => 'nullable|date',
            'expires_at'           => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data = $request->only([
            'code', 'description', 'type', 'value',
            'minimum_order', 'maximum_discount',
            'usage_limit', 'usage_limit_per_user',
            'starts_at', 'expires_at',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['free_shipping'] = $request->boolean('free_shipping');

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'                 => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'                 => 'required|in:percentage,fixed',
            'value'                => 'required|numeric|min:0',
            'minimum_order'        => 'nullable|numeric|min:0',
            'maximum_discount'     => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'starts_at'            => 'nullable|date',
            'expires_at'           => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data = $request->only([
            'code', 'description', 'type', 'value',
            'minimum_order', 'maximum_discount',
            'usage_limit', 'usage_limit_per_user',
            'starts_at', 'expires_at',
        ]);

        $data['code']          = strtoupper($data['code']);
        $data['is_active']     = $request->boolean('is_active');
        $data['free_shipping'] = $request->boolean('free_shipping');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom atualizado!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Cupom excluído.');
    }
}
