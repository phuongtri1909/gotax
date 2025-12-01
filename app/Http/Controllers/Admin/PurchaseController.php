<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoInvoicePurchase;
use App\Models\GoSoftPurchase;
use App\Models\GoBotPurchase;
use App\Models\GoQuickPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $toolType = $request->get('tool_type', 'all');
        $status = $request->get('status', 'success');
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

        $purchases = collect();
        $totalCount = 0;

        if ($toolType === 'all' || $toolType === 'go-invoice') {
            $query = GoInvoicePurchase::with(['user', 'package', 'bank']);
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }
            $invoicePurchases = $query->orderBy('created_at', 'desc')->get();
            foreach ($invoicePurchases as $purchase) {
                $purchase->tool_type = 'go-invoice';
                $purchases->push($purchase);
            }
            $totalCount += $query->count();
        }

        if ($toolType === 'all' || $toolType === 'go-soft') {
            $query = GoSoftPurchase::with(['user', 'package', 'bank']);
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }
            $softPurchases = $query->orderBy('created_at', 'desc')->get();
            foreach ($softPurchases as $purchase) {
                $purchase->tool_type = 'go-soft';
                $purchases->push($purchase);
            }
            $totalCount += $query->count();
        }

        if ($toolType === 'all' || $toolType === 'go-bot') {
            $query = GoBotPurchase::with(['user', 'package', 'bank']);
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }
            $botPurchases = $query->orderBy('created_at', 'desc')->get();
            foreach ($botPurchases as $purchase) {
                $purchase->tool_type = 'go-bot';
                $purchases->push($purchase);
            }
            $totalCount += $query->count();
        }

        if ($toolType === 'all' || $toolType === 'go-quick') {
            $query = GoQuickPurchase::with(['user', 'package', 'bank']);
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }
            $quickPurchases = $query->orderBy('created_at', 'desc')->get();
            foreach ($quickPurchases as $purchase) {
                $purchase->tool_type = 'go-quick';
                $purchases->push($purchase);
            }
            $totalCount += $query->count();
        }

        $purchases = $purchases->sortByDesc('created_at')->values();
        
        // Use Laravel's paginate helper for collections
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $purchases->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedPurchases = new LengthAwarePaginator(
            $items,
            $purchases->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('admin.pages.purchases.index', compact(
            'paginatedPurchases',
            'toolType',
            'status',
            'toolNames',
            'statusNames',
            'perPage'
        ));
    }

    public function show(Request $request, $toolType, $id)
    {
        $toolNames = [
            'go-invoice' => 'Go Invoice',
            'go-soft' => 'Go Soft',
            'go-bot' => 'Go Bot',
            'go-quick' => 'Go Quick',
        ];

        $purchase = null;
        $model = null;
        $isTimeBased = in_array($toolType, ['go-invoice', 'go-soft']);

        switch ($toolType) {
            case 'go-invoice':
                $model = GoInvoicePurchase::class;
                break;
            case 'go-soft':
                $model = GoSoftPurchase::class;
                break;
            case 'go-bot':
                $model = GoBotPurchase::class;
                break;
            case 'go-quick':
                $model = GoQuickPurchase::class;
                break;
            default:
                abort(404, 'Tool type không hợp lệ');
        }

        $relationships = ['user', 'package', 'bank'];
        if ($isTimeBased) {
            $relationships[] = 'oldPackage';
            $relationships[] = 'upgradeHistory';
        }

        $purchase = $model::with($relationships)
            ->findOrFail($id);
        $purchase->tool_type = $toolType;

        return view('admin.pages.purchases.show', compact('purchase', 'toolNames'));
    }
}

