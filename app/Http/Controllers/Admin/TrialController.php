<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trial;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trials = Trial::orderBy('tool_type')->get();
        
        $toolNames = [
            Trial::TOOL_INVOICE => 'Go Invoice',
            Trial::TOOL_BOT => 'Go Bot',
            Trial::TOOL_SOFT => 'Go Soft',
            Trial::TOOL_QUICK => 'Go Quick',
        ];

        return view('admin.pages.trials.index', compact('trials', 'toolNames'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Trial $trial)
    {
        $toolNames = [
            Trial::TOOL_INVOICE => 'Go Invoice',
            Trial::TOOL_BOT => 'Go Bot',
            Trial::TOOL_SOFT => 'Go Soft',
            Trial::TOOL_QUICK => 'Go Quick',
        ];

        return view('admin.pages.trials.show', compact('trial', 'toolNames'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trial $trial)
    {
        $toolNames = [
            Trial::TOOL_INVOICE => 'Go Invoice',
            Trial::TOOL_BOT => 'Go Bot',
            Trial::TOOL_SOFT => 'Go Soft',
            Trial::TOOL_QUICK => 'Go Quick',
        ];

        return view('admin.pages.trials.edit', compact('trial', 'toolNames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trial $trial)
    {
        $request->validate([
            'mst_limit' => 'nullable|integer|min:0',
            'cccd_limit' => 'nullable|integer|min:0',
            'expires_days' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ], [
            'mst_limit.integer' => 'MST Limit phải là số nguyên.',
            'mst_limit.min' => 'MST Limit phải lớn hơn hoặc bằng 0.',
            'cccd_limit.integer' => 'CCCD Limit phải là số nguyên.',
            'cccd_limit.min' => 'CCCD Limit phải lớn hơn hoặc bằng 0.',
            'expires_days.integer' => 'Thời hạn phải là số nguyên.',
            'expires_days.min' => 'Thời hạn phải lớn hơn hoặc bằng 0.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $trial->update([
            'mst_limit' => $request->mst_limit ?: null,
            'cccd_limit' => $request->cccd_limit ?: null,
            'expires_days' => $request->expires_days ?: null,
            'description' => $request->description,
            'status' => (bool) $request->status,
        ]);

        return redirect()->route('admin.trials.index')
            ->with('success', 'Cấu hình dùng thử đã được cập nhật thành công.');
    }
}
