<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrialRegistration;
use Illuminate\Http\Request;

class TrialRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TrialRegistration::with('user');

        // Filter by tool type
        if ($request->has('tool_type') && $request->tool_type) {
            $query->where('tool_type', $request->tool_type);
        }

        // Filter by read status
        if ($request->has('is_read') && $request->is_read !== '') {
            $query->where('is_read', $request->is_read);
        }

        // Search by user name, email
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $registrations = $query->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        $toolTypes = [
            TrialRegistration::TOOL_INVOICE => 'Go Invoice',
            TrialRegistration::TOOL_BOT => 'Go Bot',
            TrialRegistration::TOOL_SOFT => 'Go Soft',
            TrialRegistration::TOOL_QUICK => 'Go Quick',
        ];

        return view('admin.pages.trial-registrations.index', compact('registrations', 'toolTypes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(TrialRegistration $trialRegistration)
    {
        // Mark as read when viewing
        if (!$trialRegistration->is_read) {
            $trialRegistration->update(['is_read' => true]);
        }

        $trialRegistration->load('user');

        $toolNames = [
            TrialRegistration::TOOL_INVOICE => 'Go Invoice',
            TrialRegistration::TOOL_BOT => 'Go Bot',
            TrialRegistration::TOOL_SOFT => 'Go Soft',
            TrialRegistration::TOOL_QUICK => 'Go Quick',
        ];

        return view('admin.pages.trial-registrations.show', compact('trialRegistration', 'toolNames'));
    }

    /**
     * Mark registration as read
     */
    public function markAsRead(TrialRegistration $trialRegistration)
    {
        $trialRegistration->update(['is_read' => true]);

        return redirect()->back()
            ->with('success', 'Đã đánh dấu đã đọc thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrialRegistration $trialRegistration)
    {
        $trialRegistration->delete();

        return redirect()->route('admin.trial-registrations.index')
            ->with('success', 'Đã xóa đăng ký dùng thử thành công!');
    }
}
