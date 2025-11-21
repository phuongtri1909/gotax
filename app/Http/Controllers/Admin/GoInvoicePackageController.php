<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoInvoicePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoInvoicePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = GoInvoicePackage::ordered()->paginate(10);
        return view('admin.pages.go-invoice-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.go-invoice-packages.create');
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
            'license_fee' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'badge' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'features_text' => 'nullable|string',
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
            'license_fee.numeric' => 'Phí bản quyền phải là số.',
            'license_fee.min' => 'Phí bản quyền phải lớn hơn hoặc bằng 0.',
            'discount_percent.integer' => 'Phần trăm giảm giá phải là số nguyên.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_percent.max' => 'Phần trăm giảm giá không được vượt quá 100.',
            'badge.string' => 'Badge phải là chuỗi ký tự.',
            'badge.max' => 'Badge không được vượt quá 255 ký tự.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'features_text.string' => 'Tính năng phải là chuỗi ký tự.',
        ]);

        $data = $request->except(['features_text']);
        $data['slug'] = Str::slug($request->name);
        
        if ($request->has('features_text') && !empty($request->features_text)) {
            $featuresArray = array_filter(array_map('trim', explode("\n", $request->features_text)));
            $data['features'] = $featuresArray;
        }

        GoInvoicePackage::create($data);

        return redirect()->route('admin.go-invoice-packages.index')
            ->with('success', 'Gói GoInvoice đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GoInvoicePackage $goInvoicePackage)
    {
        return view('admin.pages.go-invoice-packages.show', compact('goInvoicePackage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GoInvoicePackage $goInvoicePackage)
    {
        return view('admin.pages.go-invoice-packages.edit', compact('goInvoicePackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GoInvoicePackage $goInvoicePackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mst_limit' => 'required|integer|min:1',
            'license_fee' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'badge' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'features_text' => 'nullable|string',
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
            'license_fee.numeric' => 'Phí bản quyền phải là số.',
            'license_fee.min' => 'Phí bản quyền phải lớn hơn hoặc bằng 0.',
            'discount_percent.integer' => 'Phần trăm giảm giá phải là số nguyên.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_percent.max' => 'Phần trăm giảm giá không được vượt quá 100.',
            'badge.string' => 'Badge phải là chuỗi ký tự.',
            'badge.max' => 'Badge không được vượt quá 255 ký tự.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'features_text.string' => 'Tính năng phải là chuỗi ký tự.',
        ]);

        $data = $request->except(['features_text']);
        if ($request->name !== $goInvoicePackage->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        if ($request->has('features_text')) {
            if (!empty($request->features_text)) {
                $featuresArray = array_filter(array_map('trim', explode("\n", $request->features_text)));
                $data['features'] = $featuresArray;
            } else {
                $data['features'] = null;
            }
        }

        $goInvoicePackage->update($data);

        return redirect()->route('admin.go-invoice-packages.index')
            ->with('success', 'Gói GoInvoice đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoInvoicePackage $goInvoicePackage)
    {
        $goInvoicePackage->delete();

        return redirect()->route('admin.go-invoice-packages.index')
            ->with('success', 'Gói GoInvoice đã được xóa thành công.');
    }
}
