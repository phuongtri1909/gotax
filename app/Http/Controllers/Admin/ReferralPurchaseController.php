<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralPurchase;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReferralPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $toolType = $request->get('tool_type', 'all');
        $status = $request->get('status', 'all');
        $referralCode = $request->get('referral_code', '');
        $perPage = $request->get('per_page', 20);

        $toolNames = [
            'go-invoice' => 'Go Invoice',
            'go-soft' => 'Go Soft',
            'go-bot' => 'Go Bot',
            'go-quick' => 'Go Quick',
        ];

        $statusNames = [
            'pending' => 'Chờ xử lý',
            'success' => 'Thành công',
            'failed' => 'Thất bại',
            'cancelled' => 'Đã hủy',
        ];

        $query = ReferralPurchase::with(['referrer', 'referredUser']);

        if ($toolType && $toolType !== 'all') {
            $query->where('tool_type', $toolType);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($referralCode) {
            $query->where('referral_code', 'like', '%' . $referralCode . '%');
        }

        $referrals = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.pages.referral-purchases.index', compact(
            'referrals',
            'toolType',
            'status',
            'referralCode',
            'toolNames',
            'statusNames',
            'perPage'
        ));
    }

    public function show(ReferralPurchase $referralPurchase)
    {
        $referralPurchase->load(['referrer', 'referredUser']);

        $toolNames = [
            'go-invoice' => 'Go Invoice',
            'go-soft' => 'Go Soft',
            'go-bot' => 'Go Bot',
            'go-quick' => 'Go Quick',
        ];

        $statusNames = [
            'pending' => 'Chờ xử lý',
            'success' => 'Thành công',
            'failed' => 'Thất bại',
            'cancelled' => 'Đã hủy',
        ];

        return view('admin.pages.referral-purchases.show', compact('referralPurchase', 'toolNames', 'statusNames'));
    }
}

