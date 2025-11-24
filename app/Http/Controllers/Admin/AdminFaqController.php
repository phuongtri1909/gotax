<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Faq::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by question
        if ($request->has('search') && $request->search) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        $faqs = $query->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(30);

        return view('admin.pages.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'answer' => 'required|string|max:5000',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ], [
            'question.required' => 'Câu hỏi không được để trống.',
            'question.max' => 'Câu hỏi không được vượt quá 1000 ký tự.',
            'answer.required' => 'Câu trả lời không được để trống.',
            'answer.max' => 'Câu trả lời không được vượt quá 5000 ký tự.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
        ]);

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Đã thêm câu hỏi thường gặp thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return view('admin.pages.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'answer' => 'required|string|max:5000',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ], [
            'question.required' => 'Câu hỏi không được để trống.',
            'question.max' => 'Câu hỏi không được vượt quá 1000 ký tự.',
            'answer.required' => 'Câu trả lời không được để trống.',
            'answer.max' => 'Câu trả lời không được vượt quá 5000 ký tự.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Đã cập nhật câu hỏi thường gặp thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Đã xóa câu hỏi thường gặp thành công!');
    }
}
