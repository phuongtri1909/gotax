<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by name or text
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('text', 'like', '%' . $request->search . '%');
            });
        }

        $testimonials = $query->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(30);

        return view('admin.pages.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ], [
            'text.required' => 'Nội dung đánh giá không được để trống.',
            'text.max' => 'Nội dung đánh giá không được vượt quá 2000 ký tự.',
            'rating.required' => 'Đánh giá sao không được để trống.',
            'rating.integer' => 'Đánh giá sao phải là số nguyên.',
            'rating.min' => 'Đánh giá sao phải từ 1 đến 5.',
            'rating.max' => 'Đánh giá sao phải từ 1 đến 5.',
            'name.required' => 'Tên khách hàng không được để trống.',
            'name.max' => 'Tên khách hàng không được vượt quá 255 ký tự.',
            'avatar.image' => 'File phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create([
            'text' => $request->text,
            'rating' => $request->rating,
            'name' => $request->name,
            'avatar' => $avatarPath,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Đã thêm đánh giá khách hàng thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.pages.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'text' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ], [
            'text.required' => 'Nội dung đánh giá không được để trống.',
            'text.max' => 'Nội dung đánh giá không được vượt quá 2000 ký tự.',
            'rating.required' => 'Đánh giá sao không được để trống.',
            'rating.integer' => 'Đánh giá sao phải là số nguyên.',
            'rating.min' => 'Đánh giá sao phải từ 1 đến 5.',
            'rating.max' => 'Đánh giá sao phải từ 1 đến 5.',
            'name.required' => 'Tên khách hàng không được để trống.',
            'name.max' => 'Tên khách hàng không được vượt quá 255 ký tự.',
            'avatar.image' => 'File phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
        ]);

        $avatarPath = $testimonial->avatar;
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $avatarPath = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->update([
            'text' => $request->text,
            'rating' => $request->rating,
            'name' => $request->name,
            'avatar' => $avatarPath,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Đã cập nhật đánh giá khách hàng thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        // Delete avatar if exists
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Đã xóa đánh giá khách hàng thành công!');
    }
}
