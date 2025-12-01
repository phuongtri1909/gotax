@extends('admin.layouts.sidebar')

@section('title', 'Chỉnh sửa chính sách')

@section('main-content')
    <div class="category-form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-shield-alt icon-title"></i>
                    <h5>Chỉnh sửa chính sách</h5>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.policies.update', $policy) }}" method="POST" class="category-form" id="policyForm">
                    @csrf
                    @method('PUT')

                    <div class="form-tabs">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title" class="form-label-custom">Tiêu đề <span class="required-mark">*</span></label>
                                    <input type="text" id="title" name="title" class="custom-input {{ $errors->has('title') ? 'input-error' : '' }}" value="{{ old('title', $policy->title) }}" required>
                                    <div class="error-message" id="error-title">@error('title') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="slug" class="form-label-custom">Slug</label>
                                    <input type="text" id="slug" name="slug" class="custom-input {{ $errors->has('slug') ? 'input-error' : '' }}" value="{{ old('slug', $policy->slug) }}">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Để trống sẽ tự động tạo từ tiêu đề.</span>
                                    </div>
                                    <div class="error-message" id="error-slug">@error('slug') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content" class="form-label-custom">Nội dung</label>
                                    <textarea id="content" name="content" class="custom-input {{ $errors->has('content') ? 'input-error' : '' }}">{{ old('content', $policy->content) }}</textarea>
                                    <div class="error-message" id="error-content">@error('content') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order" class="form-label-custom">Thứ tự</label>
                                    <input type="number" id="order" name="order" class="custom-input {{ $errors->has('order') ? 'input-error' : '' }}" value="{{ old('order', $policy->order) }}" min="0" step="1">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Thứ tự hiển thị (số nhỏ hơn sẽ hiển thị trước).</span>
                                    </div>
                                    <div class="error-message" id="error-order">@error('order') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-custom">Trạng thái <span class="required-mark">*</span></label>
                                    <select name="status" class="custom-input {{ $errors->has('status') ? 'input-error' : '' }}" required>
                                        <option value="active" {{ old('status', $policy->status) === 'active' ? 'selected' : '' }}>Kích hoạt</option>
                                        <option value="inactive" {{ old('status', $policy->status) === 'inactive' ? 'selected' : '' }}>Tắt</option>
                                    </select>
                                    <div class="error-message" id="error-status">@error('status') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.policies.index') }}" class="back-button">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="save-button">
                            <i class="fas fa-save"></i> Cập nhật chính sách
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CKEDITOR.replace('content', {
            extraPlugins: 'image2,uploadimage,justify',
            removePlugins: 'image',
            filebrowserImageUploadUrl: '{{ route("upload.image") }}?_token={{ csrf_token() }}',
            uploadUrl: '{{ route("upload.image") }}?_token={{ csrf_token() }}',
            fileTools_requestHeaders: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            height: 500,
            toolbarGroups: [
                { name: 'clipboard', groups: ['clipboard', 'undo'] },
                { name: 'editing', groups: ['find', 'selection'] },
                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'justify'] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'tools' }
            ],
            removeButtons: 'Underline,Subscript,Superscript',
            format_tags: 'p;h1;h2;h3;h4;h5;h6',
            allowedContent: true
        });

        document.getElementById('title').addEventListener('input', function() {
            if (!document.getElementById('slug').value) {
                const slug = this.value.toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');
                document.getElementById('slug').value = slug;
            }
        });

        document.getElementById('policyForm').addEventListener('submit', function(e) {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        });

        CKEDITOR.on('instanceReady', function(ev) {
            var editor = ev.editor;
            editor.on('fileUploadRequest', function(evt) {
                var fileLoader = evt.data.fileLoader;
                var xhr = fileLoader.xhr;
                var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            });
        });
    </script>
@endpush
