@extends('admin.layouts.sidebar')

@section('title', 'Chỉnh sửa cấu hình giảm giá nâng cấp')

@section('main-content')
    <div class="category-form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-percent icon-title"></i>
                    <h5>Chỉnh sửa cấu hình giảm giá nâng cấp</h5>
                </div>
                <div class="category-meta">
                    <div class="category-badge name">
                        <i class="fas fa-toolbox"></i>
                        <span>{{ $toolNames[$packageUpgradeConfig->tool_type] ?? $packageUpgradeConfig->tool_type }}</span>
                    </div>
                    <div class="category-badge created">
                        <i class="fas fa-calendar"></i>
                        <span>Ngày tạo: {{ $packageUpgradeConfig->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.package-upgrade-configs.update', $packageUpgradeConfig) }}" method="POST" class="category-form" id="config-form">
                    @csrf
                    @method('PUT')

                    <div class="form-tabs">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="tool_type" class="form-label-custom">
                                        Tool
                                    </label>
                                    <input type="text" id="tool_type" name="tool_type" 
                                           class="custom-input" 
                                           value="{{ $toolNames[$packageUpgradeConfig->tool_type] ?? $packageUpgradeConfig->tool_type }}" 
                                           disabled>
                                    <small class="form-text text-muted">Tool không thể thay đổi.</small>
                                </div>
                            </div>
                        </div>

                        @php
                            $isTimeBased = in_array($packageUpgradeConfig->tool_type, [
                                \App\Models\PackageUpgradeConfig::TOOL_TYPE_GO_INVOICE,
                                \App\Models\PackageUpgradeConfig::TOOL_TYPE_GO_SOFT
                            ]);
                        @endphp

                        @if($isTimeBased)
                        <div class="row">
                            <div class="col-12">
                                <h6 class="section-title" style="margin-top: 20px; margin-bottom: 15px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-calendar-check"></i> Giảm giá nâng cấp trong tháng đầu
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="first_upgrade_discount_first_month" class="form-label-custom">
                                        Lần đầu nâng cấp (%)
                                    </label>
                                    <input type="number" id="first_upgrade_discount_first_month" name="first_upgrade_discount_first_month" 
                                           step="0.01" min="0" max="100"
                                           class="custom-input {{ $errors->has('first_upgrade_discount_first_month') ? 'input-error' : '' }}"
                                           value="{{ old('first_upgrade_discount_first_month', $packageUpgradeConfig->first_upgrade_discount_first_month) }}"
                                           placeholder="0.00">
                                    <div class="error-message" id="error-first_upgrade_discount_first_month">
                                        @error('first_upgrade_discount_first_month')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="second_upgrade_discount_first_month" class="form-label-custom">
                                        Lần 2 nâng cấp (%)
                                    </label>
                                    <input type="number" id="second_upgrade_discount_first_month" name="second_upgrade_discount_first_month" 
                                           step="0.01" min="0" max="100"
                                           class="custom-input {{ $errors->has('second_upgrade_discount_first_month') ? 'input-error' : '' }}"
                                           value="{{ old('second_upgrade_discount_first_month', $packageUpgradeConfig->second_upgrade_discount_first_month) }}"
                                           placeholder="0.00">
                                    <div class="error-message" id="error-second_upgrade_discount_first_month">
                                        @error('second_upgrade_discount_first_month')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="subsequent_upgrade_discount_first_month" class="form-label-custom">
                                        Lần 3-4-5 nâng cấp (%)
                                    </label>
                                    <input type="number" id="subsequent_upgrade_discount_first_month" name="subsequent_upgrade_discount_first_month" 
                                           step="0.01" min="0" max="100"
                                           class="custom-input {{ $errors->has('subsequent_upgrade_discount_first_month') ? 'input-error' : '' }}"
                                           value="{{ old('subsequent_upgrade_discount_first_month', $packageUpgradeConfig->subsequent_upgrade_discount_first_month) }}"
                                           placeholder="0.00">
                                    <div class="error-message" id="error-subsequent_upgrade_discount_first_month">
                                        @error('subsequent_upgrade_discount_first_month')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="section-title" style="margin-top: 20px; margin-bottom: 15px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-calendar-times"></i> Giảm giá nâng cấp sau tháng đầu
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="upgrade_discount_after_first_month" class="form-label-custom">
                                        Nâng cấp sau tháng đầu (%)
                                    </label>
                                    <input type="number" id="upgrade_discount_after_first_month" name="upgrade_discount_after_first_month" 
                                           step="0.01" min="0" max="100"
                                           class="custom-input {{ $errors->has('upgrade_discount_after_first_month') ? 'input-error' : '' }}"
                                           value="{{ old('upgrade_discount_after_first_month', $packageUpgradeConfig->upgrade_discount_after_first_month) }}"
                                           placeholder="0.00">
                                    <div class="error-message" id="error-upgrade_discount_after_first_month">
                                        @error('upgrade_discount_after_first_month')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="renewal_discount_after_expired" class="form-label-custom">
                                        Gia hạn sau khi hết hạn (%)
                                    </label>
                                    <input type="number" id="renewal_discount_after_expired" name="renewal_discount_after_expired" 
                                           step="0.01" min="0" max="100"
                                           class="custom-input {{ $errors->has('renewal_discount_after_expired') ? 'input-error' : '' }}"
                                           value="{{ old('renewal_discount_after_expired', $packageUpgradeConfig->renewal_discount_after_expired) }}"
                                           placeholder="0.00">
                                    <div class="error-message" id="error-renewal_discount_after_expired">
                                        @error('renewal_discount_after_expired')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <h6 class="section-title" style="margin-top: 20px; margin-bottom: 15px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-shopping-cart"></i> Giảm giá mua hàng
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="cross_product_discount" class="form-label-custom">
                                        Cross-product discount (%)
                                    </label>
                                    <input type="number" id="cross_product_discount" name="cross_product_discount" 
                                           step="0.01" min="0" max="100"
                                           class="custom-input {{ $errors->has('cross_product_discount') ? 'input-error' : '' }}"
                                           value="{{ old('cross_product_discount', $packageUpgradeConfig->cross_product_discount) }}"
                                           placeholder="0.00">
                                    <div class="error-message" id="error-cross_product_discount">
                                        @error('cross_product_discount')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        @if($isTimeBased)
                                            Áp dụng khi mua sản phẩm khác hoặc gia hạn.
                                        @else
                                            Áp dụng khi mua thêm gói hoặc gói khác.
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="first_purchase_discount" class="form-label-custom">
                                        Lần đầu mua (%)
                                    </label>
                                    <input type="number" id="first_purchase_discount" name="first_purchase_discount" 
                                           step="0.01" min="0" max="100"
                                           class="custom-input {{ $errors->has('first_purchase_discount') ? 'input-error' : '' }}"
                                           value="{{ old('first_purchase_discount', $packageUpgradeConfig->first_purchase_discount) }}"
                                           placeholder="0.00">
                                    <div class="error-message" id="error-first_purchase_discount">
                                        @error('first_purchase_discount')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Áp dụng cho lần đầu mua gói (khác với lần đầu nâng cấp).</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label-custom">
                                        Trạng thái <span class="required-mark">*</span>
                                    </label>
                                    <select id="status" name="status" 
                                            class="custom-input {{ $errors->has('status') ? 'input-error' : '' }}" required>
                                        <option value="active" {{ old('status', $packageUpgradeConfig->status) === 'active' ? 'selected' : '' }}>Kích hoạt</option>
                                        <option value="inactive" {{ old('status', $packageUpgradeConfig->status) === 'inactive' ? 'selected' : '' }}>Tắt</option>
                                    </select>
                                    <div class="error-message" id="error-status">
                                        @error('status')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="action-button primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('admin.package-upgrade-configs.index') }}" class="action-button secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

