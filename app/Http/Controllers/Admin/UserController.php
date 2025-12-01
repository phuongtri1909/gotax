<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GoInvoiceUse;
use App\Models\GoSoftUse;
use App\Models\GoBotUse;
use App\Models\GoQuickUse;
use App\Models\GoInvoicePurchase;
use App\Models\GoSoftPurchase;
use App\Models\GoBotPurchase;
use App\Models\GoQuickPurchase;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        if ($request->has('active') && $request->active !== '') {
            $query->where('active', $request->active);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.pages.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $goInvoiceUse = GoInvoiceUse::where('user_id', $user->id)->with('package')->first();
        $goSoftUse = GoSoftUse::where('user_id', $user->id)->with('package')->first();
        $goBotUse = GoBotUse::where('user_id', $user->id)->with('package')->first();
        $goQuickUse = GoQuickUse::where('user_id', $user->id)->with('package')->first();

        $purchases = collect();
        
        $invoicePurchases = GoInvoicePurchase::where('user_id', $user->id)
            ->with(['package', 'bank'])
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($invoicePurchases as $purchase) {
            $purchase->tool_type = 'go-invoice';
            $purchases->push($purchase);
        }

        $softPurchases = GoSoftPurchase::where('user_id', $user->id)
            ->with(['package', 'bank'])
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($softPurchases as $purchase) {
            $purchase->tool_type = 'go-soft';
            $purchases->push($purchase);
        }

        $botPurchases = GoBotPurchase::where('user_id', $user->id)
            ->with(['package', 'bank'])
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($botPurchases as $purchase) {
            $purchase->tool_type = 'go-bot';
            $purchases->push($purchase);
        }

        $quickPurchases = GoQuickPurchase::where('user_id', $user->id)
            ->with(['package', 'bank'])
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($quickPurchases as $purchase) {
            $purchase->tool_type = 'go-quick';
            $purchases->push($purchase);
        }

        $purchases = $purchases->sortByDesc('created_at')->values();
        
        // Paginate purchases manually
        $perPage = 20;
        $currentPage = request()->get('page', 1);
        $currentPage = max(1, (int) $currentPage);
        $total = $purchases->count();
        $items = $purchases->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        // Create a LengthAwarePaginator manually
        $paginatedPurchases = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        $toolNames = [
            'go-invoice' => 'Go Invoice',
            'go-soft' => 'Go Soft',
            'go-bot' => 'Go Bot',
            'go-quick' => 'Go Quick',
        ];

        return view('admin.pages.users.show', compact('user', 'goInvoiceUse', 'goSoftUse', 'goBotUse', 'goQuickUse', 'paginatedPurchases', 'toolNames'));
    }
}

