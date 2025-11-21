@extends('admin.layouts.sidebar')

@php
    use App\Models\Trial;
@endphp

@section('title', 'Chỉnh sửa cấu hình dùng thử')

@section('main-content')
    <div class="category-form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-cog icon-title"></i>
                    <h5>Chỉnh sửa cấu hình dùng thử</h5>
                </div>
                <div class="category-meta">
                    <div class="category-badge name">
                        <i class="fas fa-toolbox"></i>
                        <span>{{ $toolNames[$trial->tool_type] ?? $trial->tool_type }}</span>
                    </div>
                    <div class="category-badge created">
                        <i class="fas fa-calendar"></i>
                        <span>Ngày tạo: {{ $trial->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.trials.update', $trial) }}" method="POST" class="category-form" id="trial-form">
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
                                           value="{{ $toolNames[$trial->tool_type] ?? $trial->tool_type }}" 
                                           disabled>
                                    <small class="form-text text-muted">Tool không thể thay đổi.</small>
                                </div>
                            </div>
                        </div>

                        @php
                            $showMstLimit = in_array($trial->tool_type, [Trial::TOOL_INVOICE, Trial::TOOL_BOT, Trial::TOOL_SOFT]);
                            $showCccdLimit = $trial->tool_type === Trial::TOOL_QUICK;
                            $showExpiresDays = in_array($trial->tool_type, [Trial::TOOL_INVOICE, Trial::TOOL_SOFT]);
                        @endphp

                        @if($showMstLimit)
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="mst_limit" class="form-label-custom">
                                        MST Limit
                                    </label>
                                    <input type="number" id="mst_limit" name="mst_limit" min="0"
                                           class="custom-input {{ $errors->has('mst_limit') ? 'input-error' : '' }}"
                                           value="{{ old('mst_limit', $trial->mst_limit) }}"
                                           placeholder="Nhập số lượng MST">
                                    <div class="error-message" id="error-mst_limit">
                                        @error('mst_limit')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($showCccdLimit)
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="cccd_limit" class="form-label-custom">
                                        CCCD Limit
                                    </label>
                                    <input type="number" id="cccd_limit" name="cccd_limit" min="0"
                                           class="custom-input {{ $errors->has('cccd_limit') ? 'input-error' : '' }}"
                                           value="{{ old('cccd_limit', $trial->cccd_limit) }}"
                                           placeholder="Nhập số lượng CCCD">
                                    <div class="error-message" id="error-cccd_limit">
                                        @error('cccd_limit')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($showExpiresDays)
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="expires_days" class="form-label-custom">
                                        Thời hạn (ngày)
                                    </label>
                                    <input type="number" id="expires_days" name="expires_days" min="0"
                                           class="custom-input {{ $errors->has('expires_days') ? 'input-error' : '' }}"
                                           value="{{ old('expires_days', $trial->expires_days) }}"
                                           placeholder="Nhập số ngày (để trống nếu không giới hạn)">
                                    <div class="error-message" id="error-expires_days">
                                        @error('expires_days')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Để trống nếu không giới hạn thời gian.</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label-custom">
                                        Trạng thái <span class="required-mark">*</span>
                                    </label>
                                    <select id="status" name="status" 
                                            class="custom-input {{ $errors->has('status') ? 'input-error' : '' }}" required>
                                        <option value="1" {{ old('status', $trial->status) ? 'selected' : '' }}>Kích hoạt</option>
                                        <option value="0" {{ !old('status', $trial->status) ? 'selected' : '' }}>Tắt</option>
                                    </select>
                                    <div class="error-message" id="error-status">
                                        @error('status')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label-custom">
                                        Mô tả
                                    </label>
                                    <textarea id="description" name="description" rows="5"
                                              class="custom-input {{ $errors->has('description') ? 'input-error' : '' }}"
                                              placeholder="Nhập mô tả chính sách dùng thử">{{ old('description', $trial->description) }}</textarea>
                                    <div class="error-message" id="error-description">
                                        @error('description')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Mô tả sẽ hiển thị trên trang đăng ký dùng thử.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="action-button primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('admin.trials.index') }}" class="action-button secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

