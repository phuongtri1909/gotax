@extends('admin.layouts.sidebar')

@section('title', 'Chỉnh sửa đánh giá khách hàng')

@section('main-content')
    <div class="category-form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-star icon-title"></i>
                    <h5>Chỉnh sửa đánh giá khách hàng</h5>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" class="category-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-tabs">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="text" class="form-label-custom">Nội dung đánh giá <span class="required-mark">*</span></label>
                                    <textarea id="text" name="text" rows="4" class="custom-input {{ $errors->has('text') ? 'input-error' : '' }}" required placeholder="Nhập nội dung đánh giá...">{{ old('text', $testimonial->text) }}</textarea>
                                    <div class="error-message" id="error-text">@error('text') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label-custom">Tên khách hàng <span class="required-mark">*</span></label>
                                    <input type="text" id="name" name="name" class="custom-input {{ $errors->has('name') ? 'input-error' : '' }}" value="{{ old('name', $testimonial->name) }}" required placeholder="Nhập tên khách hàng">
                                    <div class="error-message" id="error-name">@error('name') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rating" class="form-label-custom">Đánh giá sao <span class="required-mark">*</span></label>
                                    <select id="rating" name="rating" class="custom-input {{ $errors->has('rating') ? 'input-error' : '' }}" required>
                                        <option value="">-- Chọn đánh giá --</option>
                                        <option value="1" {{ old('rating', $testimonial->rating) == '1' ? 'selected' : '' }}>1 sao</option>
                                        <option value="2" {{ old('rating', $testimonial->rating) == '2' ? 'selected' : '' }}>2 sao</option>
                                        <option value="3" {{ old('rating', $testimonial->rating) == '3' ? 'selected' : '' }}>3 sao</option>
                                        <option value="4" {{ old('rating', $testimonial->rating) == '4' ? 'selected' : '' }}>4 sao</option>
                                        <option value="5" {{ old('rating', $testimonial->rating) == '5' ? 'selected' : '' }}>5 sao</option>
                                    </select>
                                    <div class="error-message" id="error-rating">@error('rating') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="avatar" class="form-label-custom">Ảnh đại diện</label>
                                    @if($testimonial->avatar)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="Current avatar" style="max-width: 150px; max-height: 150px; border-radius: 50%; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="file" id="avatar" name="avatar" class="custom-input {{ $errors->has('avatar') ? 'input-error' : '' }}" accept="image/jpeg,image/png,image/jpg,image/gif">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Định dạng: JPEG, PNG, JPG, GIF. Kích thước tối đa: 2MB. Để trống nếu không muốn thay đổi.</span>
                                    </div>
                                    <div class="error-message" id="error-avatar">@error('avatar') {{ $message }} @enderror</div>
                                    <div id="avatar-preview" class="mt-3" style="display: none;">
                                        <img id="avatar-preview-img" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 50%; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order" class="form-label-custom">Thứ tự</label>
                                    <input type="number" id="order" name="order" class="custom-input {{ $errors->has('order') ? 'input-error' : '' }}" value="{{ old('order', $testimonial->order) }}" min="0" step="1">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Thứ tự hiển thị (số nhỏ hơn sẽ hiển thị trước).</span>
                                    </div>
                                    <div class="error-message" id="error-order">@error('order') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label-custom">Trạng thái</label>
                                    <label class="d-flex align-items-center" style="gap:6px;">
                                        <input type="checkbox" name="status" value="1" {{ old('status', $testimonial->status) ? 'checked' : '' }}> Kích hoạt
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.testimonials.index') }}" class="back-button">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="save-button">
                            <i class="fas fa-save"></i> Cập nhật đánh giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').style.display = 'block';
                    document.getElementById('avatar-preview-img').src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('avatar-preview').style.display = 'none';
            }
        });
    </script>
@endpush

