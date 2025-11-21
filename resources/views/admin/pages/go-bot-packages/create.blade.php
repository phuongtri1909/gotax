@extends('admin.layouts.sidebar')

@section('title', 'Thêm gói GoBot')

@section('main-content')
    <div class="category-form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-box icon-title"></i>
                    <h5>Thêm gói GoBot</h5>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.go-bot-packages.store') }}" method="POST" class="category-form">
                    @csrf

                    <div class="form-tabs">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label-custom">
                                        Tên gói <span class="required-mark">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" 
                                           class="custom-input {{ $errors->has('name') ? 'input-error' : '' }}"
                                           value="{{ old('name') }}" required>
                                    <div class="error-message" id="error-name">
                                        @error('name') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-label-custom">
                                        Giá (VNĐ) <span class="required-mark">*</span>
                                    </label>
                                    <input type="number" id="price" name="price" step="0.01" min="0"
                                           class="custom-input {{ $errors->has('price') ? 'input-error' : '' }}"
                                           value="{{ old('price') }}" required>
                                    <div class="error-message" id="error-price">
                                        @error('price') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="mst_limit" class="form-label-custom">
                                        Giới hạn MST <span class="required-mark">*</span>
                                    </label>
                                    <input type="number" id="mst_limit" name="mst_limit" min="1"
                                           class="custom-input {{ $errors->has('mst_limit') ? 'input-error' : '' }}"
                                           value="{{ old('mst_limit') }}" required>
                                    <div class="error-message" id="error-mst_limit">
                                        @error('mst_limit') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="discount_percent" class="form-label-custom">
                                        Giảm giá (%)
                                    </label>
                                    <input type="number" id="discount_percent" name="discount_percent" min="0" max="100"
                                           class="custom-input {{ $errors->has('discount_percent') ? 'input-error' : '' }}"
                                           value="{{ old('discount_percent', 0) }}">
                                    <div class="error-message" id="error-discount_percent">
                                        @error('discount_percent') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="order" class="form-label-custom">
                                        Thứ tự
                                    </label>
                                    <input type="number" id="order" name="order" min="0"
                                           class="custom-input {{ $errors->has('order') ? 'input-error' : '' }}"
                                           value="{{ old('order', 0) }}">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Số nhỏ hơn sẽ hiển thị trước</span>
                                    </div>
                                    <div class="error-message" id="error-order">
                                        @error('order') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label-custom">
                                        Trạng thái <span class="required-mark">*</span>
                                    </label>
                                    <select id="status" name="status" 
                                            class="custom-input {{ $errors->has('status') ? 'input-error' : '' }}" required>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tắt</option>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Kích hoạt</option>
                                    </select>
                                    <div class="error-message" id="error-status">
                                        @error('status') {{ $message }} @enderror
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
                                    <textarea id="description" name="description" rows="4"
                                              class="custom-input {{ $errors->has('description') ? 'input-error' : '' }}">{{ old('description') }}</textarea>
                                    <div class="error-message" id="error-description">
                                        @error('description') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.go-bot-packages.index') }}" class="back-button">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="save-button">
                            <i class="fas fa-save"></i> Tạo gói
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

