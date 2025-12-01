<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageUpgradeConfig;
use Illuminate\Http\Request;

class PackageUpgradeConfigController extends Controller
{
    public function index()
    {
        $configs = PackageUpgradeConfig::orderBy('tool_type')->get();
        
        $toolNames = [
            PackageUpgradeConfig::TOOL_TYPE_GO_INVOICE => 'Go Invoice',
            PackageUpgradeConfig::TOOL_TYPE_GO_SOFT => 'Go Soft',
            PackageUpgradeConfig::TOOL_TYPE_GO_BOT => 'Go Bot',
            PackageUpgradeConfig::TOOL_TYPE_GO_QUICK => 'Go Quick',
        ];

        return view('admin.pages.package-upgrade-configs.index', compact('configs', 'toolNames'));
    }

    public function show(PackageUpgradeConfig $packageUpgradeConfig)
    {
        $toolNames = [
            PackageUpgradeConfig::TOOL_TYPE_GO_INVOICE => 'Go Invoice',
            PackageUpgradeConfig::TOOL_TYPE_GO_SOFT => 'Go Soft',
            PackageUpgradeConfig::TOOL_TYPE_GO_BOT => 'Go Bot',
            PackageUpgradeConfig::TOOL_TYPE_GO_QUICK => 'Go Quick',
        ];

        return view('admin.pages.package-upgrade-configs.show', compact('packageUpgradeConfig', 'toolNames'));
    }

    public function edit(PackageUpgradeConfig $packageUpgradeConfig)
    {
        $toolNames = [
            PackageUpgradeConfig::TOOL_TYPE_GO_INVOICE => 'Go Invoice',
            PackageUpgradeConfig::TOOL_TYPE_GO_SOFT => 'Go Soft',
            PackageUpgradeConfig::TOOL_TYPE_GO_BOT => 'Go Bot',
            PackageUpgradeConfig::TOOL_TYPE_GO_QUICK => 'Go Quick',
        ];

        return view('admin.pages.package-upgrade-configs.edit', compact('packageUpgradeConfig', 'toolNames'));
    }

    public function update(Request $request, PackageUpgradeConfig $packageUpgradeConfig)
    {
        $request->validate([
            'first_upgrade_discount_first_month' => 'nullable|numeric|min:0|max:100',
            'second_upgrade_discount_first_month' => 'nullable|numeric|min:0|max:100',
            'subsequent_upgrade_discount_first_month' => 'nullable|numeric|min:0|max:100',
            'upgrade_discount_after_first_month' => 'nullable|numeric|min:0|max:100',
            'renewal_discount_after_expired' => 'nullable|numeric|min:0|max:100',
            'cross_product_discount' => 'nullable|numeric|min:0|max:100',
            'first_purchase_discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ], [
            'first_upgrade_discount_first_month.numeric' => 'Giảm giá lần đầu nâng cấp trong tháng đầu phải là số.',
            'first_upgrade_discount_first_month.min' => 'Giảm giá lần đầu nâng cấp trong tháng đầu phải lớn hơn hoặc bằng 0.',
            'first_upgrade_discount_first_month.max' => 'Giảm giá lần đầu nâng cấp trong tháng đầu không được vượt quá 100%.',
            'second_upgrade_discount_first_month.numeric' => 'Giảm giá lần 2 nâng cấp trong tháng đầu phải là số.',
            'second_upgrade_discount_first_month.min' => 'Giảm giá lần 2 nâng cấp trong tháng đầu phải lớn hơn hoặc bằng 0.',
            'second_upgrade_discount_first_month.max' => 'Giảm giá lần 2 nâng cấp trong tháng đầu không được vượt quá 100%.',
            'subsequent_upgrade_discount_first_month.numeric' => 'Giảm giá lần 3-4-5 nâng cấp trong tháng đầu phải là số.',
            'subsequent_upgrade_discount_first_month.min' => 'Giảm giá lần 3-4-5 nâng cấp trong tháng đầu phải lớn hơn hoặc bằng 0.',
            'subsequent_upgrade_discount_first_month.max' => 'Giảm giá lần 3-4-5 nâng cấp trong tháng đầu không được vượt quá 100%.',
            'upgrade_discount_after_first_month.numeric' => 'Giảm giá nâng cấp sau tháng đầu phải là số.',
            'upgrade_discount_after_first_month.min' => 'Giảm giá nâng cấp sau tháng đầu phải lớn hơn hoặc bằng 0.',
            'upgrade_discount_after_first_month.max' => 'Giảm giá nâng cấp sau tháng đầu không được vượt quá 100%.',
            'renewal_discount_after_expired.numeric' => 'Giảm giá gia hạn sau khi hết hạn phải là số.',
            'renewal_discount_after_expired.min' => 'Giảm giá gia hạn sau khi hết hạn phải lớn hơn hoặc bằng 0.',
            'renewal_discount_after_expired.max' => 'Giảm giá gia hạn sau khi hết hạn không được vượt quá 100%.',
            'cross_product_discount.numeric' => 'Giảm giá cross-product phải là số.',
            'cross_product_discount.min' => 'Giảm giá cross-product phải lớn hơn hoặc bằng 0.',
            'cross_product_discount.max' => 'Giảm giá cross-product không được vượt quá 100%.',
            'first_purchase_discount.numeric' => 'Giảm giá lần đầu mua phải là số.',
            'first_purchase_discount.min' => 'Giảm giá lần đầu mua phải lớn hơn hoặc bằng 0.',
            'first_purchase_discount.max' => 'Giảm giá lần đầu mua không được vượt quá 100%.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $data = [
            'status' => $request->status,
        ];

        // Chỉ cập nhật các trường time-based discounts nếu tool type là go-invoice hoặc go-soft
        if (in_array($packageUpgradeConfig->tool_type, [PackageUpgradeConfig::TOOL_TYPE_GO_INVOICE, PackageUpgradeConfig::TOOL_TYPE_GO_SOFT])) {
            $data['first_upgrade_discount_first_month'] = $request->filled('first_upgrade_discount_first_month') ? $request->first_upgrade_discount_first_month : null;
            $data['second_upgrade_discount_first_month'] = $request->filled('second_upgrade_discount_first_month') ? $request->second_upgrade_discount_first_month : null;
            $data['subsequent_upgrade_discount_first_month'] = $request->filled('subsequent_upgrade_discount_first_month') ? $request->subsequent_upgrade_discount_first_month : null;
            $data['upgrade_discount_after_first_month'] = $request->filled('upgrade_discount_after_first_month') ? $request->upgrade_discount_after_first_month : null;
            $data['renewal_discount_after_expired'] = $request->filled('renewal_discount_after_expired') ? $request->renewal_discount_after_expired : null;
        } else {
            // Đối với go-bot và go-quick, set các trường time-based discounts thành null
            $data['first_upgrade_discount_first_month'] = null;
            $data['second_upgrade_discount_first_month'] = null;
            $data['subsequent_upgrade_discount_first_month'] = null;
            $data['upgrade_discount_after_first_month'] = null;
            $data['renewal_discount_after_expired'] = null;
        }

        // Các trường này áp dụng cho tất cả tool types
        $data['cross_product_discount'] = $request->filled('cross_product_discount') ? $request->cross_product_discount : null;
        $data['first_purchase_discount'] = $request->filled('first_purchase_discount') ? $request->first_purchase_discount : null;

        $packageUpgradeConfig->update($data);

        return redirect()->route('admin.package-upgrade-configs.index')
            ->with('success', 'Cấu hình giảm giá đã được cập nhật thành công.');
    }
}

