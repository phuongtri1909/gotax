<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $documents = $query->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.pages.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:documents,slug',
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

        Document::create($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Đã tạo tài liệu thành công!');
    }

    public function show(Document $document)
    {
        return view('admin.pages.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('admin.pages.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:documents,slug,' . $document->id,
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

        $document->update($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Đã cập nhật tài liệu thành công!');
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Đã xóa tài liệu thành công!');
    }
}
