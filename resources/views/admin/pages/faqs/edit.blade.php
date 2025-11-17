@extends('admin.layouts.sidebar')

@section('title', 'Chỉnh sửa câu hỏi thường gặp')

@section('main-content')
    <div class="category-form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-question-circle icon-title"></i>
                    <h5>Chỉnh sửa câu hỏi thường gặp</h5>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="category-form">
                    @csrf
                    @method('PUT')

                    <div class="form-tabs">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="question" class="form-label-custom">Câu hỏi <span class="required-mark">*</span></label>
                                    <textarea id="question" name="question" rows="3" class="custom-input {{ $errors->has('question') ? 'input-error' : '' }}" required>{{ old('question', $faq->question) }}</textarea>
                                    <div class="error-message" id="error-question">@error('question') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="answer" class="form-label-custom">Câu trả lời <span class="required-mark">*</span></label>
                                    <textarea id="answer" name="answer" rows="6" class="custom-input {{ $errors->has('answer') ? 'input-error' : '' }}" required>{{ old('answer', $faq->answer) }}</textarea>
                                    <div class="error-message" id="error-answer">@error('answer') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order" class="form-label-custom">Thứ tự</label>
                                    <input type="number" id="order" name="order" class="custom-input {{ $errors->has('order') ? 'input-error' : '' }}" value="{{ old('order', $faq->order) }}" min="0" step="1">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Thứ tự hiển thị (số nhỏ hơn sẽ hiển thị trước).</span>
                                    </div>
                                    <div class="error-message" id="error-order">@error('order') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-custom">Trạng thái</label>
                                    <label class="d-flex align-items-center" style="gap:6px;">
                                        <input type="checkbox" name="status" value="1" {{ old('status', $faq->status) ? 'checked' : '' }}> Kích hoạt
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.faqs.index') }}" class="back-button">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="save-button">
                            <i class="fas fa-save"></i> Cập nhật câu hỏi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

