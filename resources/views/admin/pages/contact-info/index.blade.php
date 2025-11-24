@extends('admin.layouts.sidebar')

@section('title', 'Thông tin liên hệ')

@section('main-content')
    <div class="category-form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-address-card icon-title"></i>
                    <h5>Thông tin liên hệ</h5>
                </div>
            </div>

            <div class="form-body">
                @include('components.toast-main')
                @include('components.toast')

                <form action="{{ route('admin.contact-info.update') }}" method="POST" class="category-form">
                    @csrf
                    @method('PUT')

                    <div class="form-tabs">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label-custom">Số điện thoại</label>
                                    <input type="text" id="phone" name="phone" class="custom-input {{ $errors->has('phone') ? 'input-error' : '' }}" value="{{ old('phone', $contactInfo->phone) }}">
                                    <div class="error-message" id="error-phone">@error('phone') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label-custom">Email</label>
                                    <input type="email" id="email" name="email" class="custom-input {{ $errors->has('email') ? 'input-error' : '' }}" value="{{ old('email', $contactInfo->email) }}">
                                    <div class="error-message" id="error-email">@error('email') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address" class="form-label-custom">Địa chỉ</label>
                                    <textarea id="address" name="address" rows="3" class="custom-input {{ $errors->has('address') ? 'input-error' : '' }}">{{ old('address', $contactInfo->address) }}</textarea>
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Địa chỉ này sẽ hiển thị ở footer và trang liên hệ.</span>
                                    </div>
                                    <div class="error-message" id="error-address">@error('address') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="map_url" class="form-label-custom">URL Bản đồ (Google Maps Embed)</label>
                                    <input type="url" id="map_url" name="map_url" class="custom-input {{ $errors->has('map_url') ? 'input-error' : '' }}" value="{{ old('map_url', $contactInfo->map_url) }}" placeholder="https://www.google.com/maps/embed?pb=...">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>URL embed từ Google Maps. Nếu không có, có thể dùng tọa độ Latitude và Longitude bên dưới.</span>
                                    </div>
                                    <div class="error-message" id="error-map_url">@error('map_url') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude" class="form-label-custom">Latitude (Vĩ độ)</label>
                                    <input type="text" id="latitude" name="latitude" class="custom-input {{ $errors->has('latitude') ? 'input-error' : '' }}" value="{{ old('latitude', $contactInfo->latitude) }}" placeholder="21.028127">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Chỉ dùng nếu không có URL bản đồ.</span>
                                    </div>
                                    <div class="error-message" id="error-latitude">@error('latitude') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="form-label-custom">Longitude (Kinh độ)</label>
                                    <input type="text" id="longitude" name="longitude" class="custom-input {{ $errors->has('longitude') ? 'input-error' : '' }}" value="{{ old('longitude', $contactInfo->longitude) }}" placeholder="105.780523">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Chỉ dùng nếu không có URL bản đồ.</span>
                                    </div>
                                    <div class="error-message" id="error-longitude">@error('longitude') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="save-button">
                            <i class="fas fa-save"></i> Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

