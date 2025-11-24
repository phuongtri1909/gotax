<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoQuickPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoQuickPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = GoQuickPackage::ordered()->paginate(10);
        return view('admin.pages.go-quick-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.go-quick-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cccd_limit' => 'required|integer|min:1',
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
            'cccd_limit.required' => 'Vui lòng nhập giới hạn CCCD.',
            'cccd_limit.integer' => 'Giới hạn CCCD phải là số nguyên.',
            'cccd_limit.min' => 'Giới hạn CCCD phải lớn hơn hoặc bằng 1.',
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

        GoQuickPackage::create($data);

        return redirect()->route('admin.go-quick-packages.index')
            ->with('success', 'Gói GoQuick đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GoQuickPackage $goQuickPackage)
    {
        return view('admin.pages.go-quick-packages.show', compact('goQuickPackage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GoQuickPackage $goQuickPackage)
    {
        return view('admin.pages.go-quick-packages.edit', compact('goQuickPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GoQuickPackage $goQuickPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cccd_limit' => 'required|integer|min:1',
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
            'cccd_limit.required' => 'Vui lòng nhập giới hạn CCCD.',
            'cccd_limit.integer' => 'Giới hạn CCCD phải là số nguyên.',
            'cccd_limit.min' => 'Giới hạn CCCD phải lớn hơn hoặc bằng 1.',
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
        if ($request->name !== $goQuickPackage->name) {
            $data['slug'] = Str::slug($request->name);
        }

        $goQuickPackage->update($data);

        return redirect()->route('admin.go-quick-packages.index')
            ->with('success', 'Gói GoQuick đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoQuickPackage $goQuickPackage)
    {
        $goQuickPackage->delete();

        return redirect()->route('admin.go-quick-packages.index')
            ->with('success', 'Gói GoQuick đã được xóa thành công.');
    }
}
