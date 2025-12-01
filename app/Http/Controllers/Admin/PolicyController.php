<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = Policy::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $policies = $query->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.pages.policies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:policies,slug',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'slug.unique' => 'Slug đã tồn tại.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ]);

        $data = $request->only(['title', 'content', 'order', 'status']);
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        Policy::create($data);

        return redirect()->route('admin.policies.index')
            ->with('success', 'Đã tạo chính sách thành công!');
    }

    public function show(Policy $policy)
    {
        return view('admin.pages.policies.show', compact('policy'));
    }

    public function edit(Policy $policy)
    {
        return view('admin.pages.policies.edit', compact('policy'));
    }

    public function update(Request $request, Policy $policy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:policies,slug,' . $policy->id,
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'slug.unique' => 'Slug đã tồn tại.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ]);

        $data = $request->only(['title', 'content', 'order', 'status']);
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $policy->update($data);

        return redirect()->route('admin.policies.index')
            ->with('success', 'Đã cập nhật chính sách thành công!');
    }

    public function destroy(Policy $policy)
    {
        $policy->delete();

        return redirect()->route('admin.policies.index')
            ->with('success', 'Đã xóa chính sách thành công!');
    }
}
