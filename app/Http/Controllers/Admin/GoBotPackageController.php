<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\GoBotPackage;
use Illuminate\Http\Request;
use App\Models\GoBotPurchase;
use App\Http\Controllers\Controller;

class GoBotPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = GoBotPackage::ordered()->paginate(10);
        return view('admin.pages.go-bot-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.go-bot-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mst_limit' => 'required|integer|min:1',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên gói.',
            'name.string' => 'Tên gói phải là chuỗi ký tự.',
            'name.max' => 'Tên gói không được vượt quá 255 ký tự.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'mst_limit.required' => 'Vui lòng nhập giới hạn MST.',
            'mst_limit.integer' => 'Giới hạn MST phải là số nguyên.',
            'mst_limit.min' => 'Giới hạn MST phải lớn hơn hoặc bằng 1.',
            'discount_percent.integer' => 'Phần trăm giảm giá phải là số nguyên.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_percent.max' => 'Phần trăm giảm giá không được vượt quá 100.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        GoBotPackage::create($data);

        return redirect()->route('admin.go-bot-packages.index')
            ->with('success', 'Gói GoBot đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GoBotPackage $goBotPackage)
    {
        $purchases = GoBotPurchase::where('package_id', $goBotPackage->id)
            ->with(['user', 'bank'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.pages.go-bot-packages.show', compact('goBotPackage', 'purchases'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GoBotPackage $goBotPackage)
    {
        return view('admin.pages.go-bot-packages.edit', compact('goBotPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GoBotPackage $goBotPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mst_limit' => 'required|integer|min:1',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên gói.',
            'name.string' => 'Tên gói phải là chuỗi ký tự.',
            'name.max' => 'Tên gói không được vượt quá 255 ký tự.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'mst_limit.required' => 'Vui lòng nhập giới hạn MST.',
            'mst_limit.integer' => 'Giới hạn MST phải là số nguyên.',
            'mst_limit.min' => 'Giới hạn MST phải lớn hơn hoặc bằng 1.',
            'discount_percent.integer' => 'Phần trăm giảm giá phải là số nguyên.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_percent.max' => 'Phần trăm giảm giá không được vượt quá 100.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
        ]);

        $data = $request->all();
        if ($request->name !== $goBotPackage->name) {
            $data['slug'] = Str::slug($request->name);
        }

        $goBotPackage->update($data);

        return redirect()->route('admin.go-bot-packages.index')
            ->with('success', 'Gói GoBot đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoBotPackage $goBotPackage)
    {
        $goBotPackage->delete();

        return redirect()->route('admin.go-bot-packages.index')
            ->with('success', 'Gói GoBot đã được xóa thành công.');
    }
}
