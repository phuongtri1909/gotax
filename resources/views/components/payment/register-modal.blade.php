@props([
    'modalId' => 'registerModal',
    'package' => null,
])

@php
    $user = auth()->user();
    $userFullName = $user ? $user->full_name : '';
    $userEmail = $user ? $user->email : '';
    $userPhone = $user ? $user->phone : '';
    
    // Parse full_name to first_name and last_name
    $firstName = '';
    $lastName = '';
    if ($userFullName) {
        $nameParts = explode(' ', $userFullName);
        $lastName = array_pop($nameParts);
        $firstName = implode(' ', $nameParts);
    }
@endphp

<!-- Modal -->
<div class="modal fade register-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content ">

            <div class="modal-body">
                <div class="container">
                    <div class="d-flex">
                        <button type="button" class="btn-back" aria-label="Back">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21"
                                fill="none">
                                <g clip-path="url(#clip0_1_49920)">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.48453 9.18836H19.6872C20.0353 9.18836 20.3692 9.32664 20.6153 9.57278C20.8614 9.81893 20.9997 10.1528 20.9997 10.5009C20.9997 10.849 20.8614 11.1828 20.6153 11.4289C20.3692 11.6751 20.0353 11.8134 19.6872 11.8134H4.48453L10.5102 17.839C10.7567 18.0853 10.8952 18.4194 10.8953 18.7678C10.8954 19.1162 10.7572 19.4504 10.5109 19.6969C10.2646 19.9433 9.9305 20.0819 9.58209 20.082C9.23368 20.0821 8.89948 19.9438 8.65303 19.6975L0.384282 11.4288C0.138226 11.1827 0 10.8489 0 10.5009C0 10.1528 0.138226 9.81905 0.384282 9.57292L8.65303 1.30417C8.89966 1.0579 9.23402 0.919676 9.58255 0.919922C9.93109 0.920168 10.2653 1.05886 10.5115 1.30549C10.7578 1.55211 10.896 1.88647 10.8958 2.23501C10.8955 2.58354 10.7568 2.91771 10.5102 3.16399L4.48453 9.18836Z"
                                        fill="#003875" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_1_49920">
                                        <rect width="21" height="21" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                        <div class="modal-logo">
                            <img src="{{ $logoPath }}" alt="GoTax Logo">
                        </div>
                    </div>
                    <!-- Progress Steps -->
                    <div class="progress-steps mb-5">
                        <div class="step-item active">
                            <span class="step-label">Thông tin</span>
                        </div>

                        <div class="step-item">
                            <div class="step-line"></div>
                            <div class="step-circle">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="step-line"></div>

                            <span class="step-label">Thanh toán</span>

                        </div>

                        <div class="step-item">
                            <div class="step-line"></div>
                            <div class="step-circle">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="step-line"></div>
                            <span class="step-label">Thành công</span>

                        </div>
                    </div>

                    <!-- Step 1: Registration Form -->
                    <div class="row g-4 px-0 p-md-5" id="step-1-content">
                        <!-- Left: Package Info -->
                        <div class="col-12 col-lg-6 col-xxl-5">
                            <div class="package-summary-card">
                                <h3 class="summary-title text-center">Công cụ đăng ký</h3>

                                <div class="package-info" data-package-info>
                                    <div class="d-flex justify-content-between">
                                        <div class="package-icon me-2">
                                            <img src="{{ asset('images/dev/package.png') }}" alt="Package Icon">
                                        </div>
                                        <div class="package-details d-flex flex-column justify-content-around">
                                            <h4 class="package-name mb-0" data-package-name>Go Quick</h4>
                                            <p class="package-desc" data-package-desc>
                                                {{ $package->name ?? 'Basic' }} -
                                                {{ $package->description ?? '1000 MST/ Năm' }}</p>
                                        </div>
                                        <div
                                            class="package-price d-flex flex-column align-items-end justify-content-around">
                                            <span class="price-amount"
                                                data-package-price>{{ $package->price ?? '200.000' }}đ</span>
                                            <span class="price-badge" data-package-badge style="display: none;"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn-remove" data-remove-package>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14"
                                                viewBox="0 0 13 14" fill="none">
                                                <path
                                                    d="M6.10965 1.52494C7.96264 1.52494 9.81564 1.52452 11.6686 1.52537C12.0066 1.52537 12.2277 1.74561 12.2192 2.06538C12.212 2.33391 12.0066 2.54907 11.7381 2.56177C11.6009 2.56813 11.4628 2.56643 11.3251 2.56347C11.2366 2.56135 11.1981 2.59862 11.2036 2.68629C11.2065 2.73373 11.2036 2.78159 11.2036 2.82903C11.2036 5.83617 11.1909 8.84331 11.2104 11.85C11.2171 12.8831 10.4611 13.8292 9.42767 13.9796C9.32856 13.994 9.22734 13.9982 9.12738 13.9982C7.11556 13.9991 5.10374 14.0008 3.09192 13.9982C1.93988 13.9965 1.01741 13.0698 1.01656 11.9136C1.01487 8.87466 1.01572 5.83575 1.01572 2.79726C1.01572 2.56389 1.01572 2.56431 0.783193 2.56389C0.677308 2.56389 0.570999 2.56855 0.465537 2.56093C0.198706 2.54229 -0.0041703 2.30892 6.51142e-05 2.03235C0.00387699 1.75959 0.203365 1.54485 0.471043 1.52706C0.534151 1.52283 0.598106 1.52494 0.661637 1.52494C2.47821 1.52494 4.29393 1.52494 6.10965 1.52494ZM2.05551 7.29273C2.05551 8.79588 2.05382 10.2986 2.05636 11.8018C2.05721 12.5116 2.49811 12.9584 3.20373 12.9593C5.14059 12.9623 7.07744 12.9606 9.01472 12.9593C9.17905 12.9593 9.34212 12.9398 9.49671 12.8767C9.93296 12.6988 10.1651 12.324 10.1655 11.7882C10.1663 8.77682 10.1646 5.76586 10.1689 2.75448C10.1693 2.59862 10.1202 2.56008 9.97023 2.5605C7.39806 2.56474 4.82632 2.56516 2.25415 2.55965C2.08897 2.55923 2.05212 2.6109 2.05255 2.76761C2.05763 4.27627 2.05509 5.7845 2.05551 7.29273Z"
                                                    fill="#505050" />
                                                <path
                                                    d="M6.12062 0.00104219C6.79786 0.00104219 7.47553 -0.00065195 8.15277 0.00188929C8.39334 0.00273637 8.58012 0.150552 8.64662 0.377146C8.71057 0.595693 8.62205 0.831182 8.42638 0.959514C8.33955 1.01627 8.24425 1.03956 8.14049 1.03956C6.78558 1.03872 5.43109 1.04041 4.07618 1.03787C3.77462 1.03745 3.55607 0.809157 3.55862 0.512679C3.56116 0.220013 3.77293 0.00358345 4.07237 0.00188929C4.75554 -0.00149903 5.43829 0.000618652 6.12062 0.00104219Z"
                                                    fill="#505050" />
                                                <path
                                                    d="M4.85102 7.88835C4.85102 8.73501 4.85229 9.58209 4.85018 10.4288C4.84975 10.674 4.70744 10.8629 4.48551 10.9298C4.27204 10.9946 4.04418 10.9167 3.90737 10.7337C3.84172 10.6456 3.8125 10.5465 3.8125 10.4372C3.81292 8.7384 3.81165 7.03957 3.81377 5.34075C3.81419 5.04215 4.03359 4.83081 4.32668 4.82911C4.62485 4.82742 4.84848 5.04385 4.84975 5.34837C4.85272 6.19503 4.85102 7.04169 4.85102 7.88835Z"
                                                    fill="#505050" />
                                                <path
                                                    d="M7.36968 7.88235C7.36968 7.03569 7.36799 6.1886 7.37095 5.34194C7.3718 5.08104 7.54545 4.87647 7.78772 4.83497C8.0537 4.78922 8.28707 4.92476 8.3811 5.17592C8.40609 5.24241 8.40778 5.31018 8.40778 5.37879C8.40778 7.05136 8.40863 8.7235 8.40736 10.3961C8.40736 10.7294 8.19643 10.9501 7.8864 10.9492C7.58145 10.9484 7.36968 10.7222 7.36926 10.3906C7.36884 9.55491 7.36968 8.71884 7.36968 7.88235Z"
                                                    fill="#505050" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="ps-4 pt-3">
                                    <div class="promo-code-section">
                                        <label class="promo-label">Mã giới thiệu</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control promo-input me-2 form-control-sm"
                                                name="promo_code" placeholder="Code">
                                            <button class="btn btn-apply btn-sm" type="button">Sử dụng</button>
                                        </div>
                                    </div>

                                    <div class="price-breakdown">
                                        <div class="price-row">
                                            <span class="price-label">Phí đăng ký</span>
                                            <span class="price-value" data-registration-fee>200.000đ</span>
                                        </div>
                                        <div class="price-row discount">
                                            <span class="price-label">Mã giảm giá</span>
                                            <span class="price-value" data-discount>-50.000đ</span>
                                        </div>
                                        <div class="price-row total">
                                            <span class="price-label">Tổng thanh toán</span>
                                            <span class="price-value" data-total-amount>150.000đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-none d-xxl-block col-xxl-2">
                            <!-- Spacer Column -->

                        </div>

                        <!-- Right: Registration Form -->
                        <div class="col-12 col-lg-6 col-xxl-5">
                            <div class="registration-form-card" id="step-register-form">
                                <h3 class="form-title text-center">Thông tin đăng ký</h3>

                                <form id="registrationForm" class="registration-form">
                                    <div class="row g-3 content-registration-form">
                                        <input type="hidden" name="package_id" id="package_id_input">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Họ<span
                                                    class="text-danger-form">*</span></label>
                                            <input type="text" class="form-control" name="first_name" 
                                                   value="{{ $firstName }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Tên<span
                                                    class="text-danger-form">*</span></label>
                                            <input type="text" class="form-control" name="last_name" 
                                                   value="{{ $lastName }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Email<span
                                                    class="text-danger-form">*</span></label>
                                            <input type="email" class="form-control" name="email" 
                                                   value="{{ $userEmail }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Số điện thoại<span
                                                    class="text-danger-form">*</span></label>
                                            <div class="phone-input-group">
                                                <select class="form-select country-code" name="country_code">
                                                    <option value="+84" selected>+ 84</option>
                                                    <option value="+1">+ 1</option>
                                                    <option value="+44">+ 44</option>
                                                </select>
                                                <input type="tel" class="form-control phone-number"
                                                    name="phone" value="{{ $userPhone }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check ps-4">
                                                <input class="form-check-input" type="checkbox"
                                                    name="export_vat_invoice" id="exportVAT">
                                                <label class="form-check-label" for="exportVAT">
                                                    Xuất hoá đơn VAT
                                                </label>
                                            </div>
                                            <div id="vat-fields" class="mt-3 d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Mã số thuế<span
                                                            class="text-danger-form">*</span></label>
                                                    <input type="text" class="form-control" name="vat_mst"
                                                        placeholder="Mã số thuế" autocomplete="off">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tên đơn vị<span
                                                            class="text-danger-form">*</span></label>
                                                    <input type="text" class="form-control" name="vat_company"
                                                        placeholder="Tên đơn vị" autocomplete="off">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Địa chỉ<span
                                                            class="text-danger-form">*</span></label>
                                                    <input type="text" class="form-control" name="vat_address"
                                                        placeholder="Địa chỉ" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions text-center mt-4">
                                        <button type="submit" class="btn btn-continue">Tiếp tục</button>
                                    </div>
                                </form>
                            </div>
                            <div class="registration-form-card d-none" id="step-confirm-info">
                                <h3 class="form-title text-center mb-4">Cảm ơn bạn đã lựa chọn sản phẩm của <span
                                        class="confirm-gotax-text">GoTax</span>!</h3>
                                <div class="mb-4">
                                    <div class="confirm-section-title">XÁC NHẬN THÔNG TIN</div>
                                    <div class="confirm-content-box">
                                        <div class="confirm-info-row">
                                            <div class="">Họ và Tên:</div>
                                            <div class="text-end">
                                                <span id="confirm_fullname"></span>
                                                <img class="ms-2" src="{{ asset('images/svg/edit.svg') }}"
                                                    alt="edit">
                                            </div>
                                        </div>
                                        <div class="confirm-info-row">
                                            <div class="">Email:</div>
                                            <div class=" text-end">
                                                <span id="confirm_email"></span>
                                                <img class="ms-2" src="{{ asset('images/svg/edit.svg') }}"
                                                    alt="edit">
                                            </div>
                                        </div>
                                        <div class="confirm-info-row">
                                            <div class="">Số điện thoại:</div>
                                            <div class=" text-end">
                                                <span id="confirm_phone"></span>
                                                <img class="ms-2" src="{{ asset('images/svg/edit.svg') }}"
                                                    alt="edit">
                                            </div>
                                        </div>
                                        <div id="row-confirm-vat" class="d-none">
                                            <div class="confirm-info-row">
                                                <div class="">Mã số thuế:</div>
                                                <div class=" text-end">
                                                    <span id="confirm_vat_mst"></span>
                                                    <img class="ms-2" src="{{ asset('images/svg/edit.svg') }}"
                                                        alt="edit">
                                                </div>
                                            </div>
                                            <div class="confirm-info-row">
                                                <div class="">Tên đơn vị:</div>
                                                <div class=" text-end">
                                                    <span id="confirm_vat_company"></span>
                                                    <img class="ms-2" src="{{ asset('images/svg/edit.svg') }}"
                                                        alt="edit">
                                                </div>
                                            </div>
                                            <div class="confirm-info-row">
                                                <div class="">Địa chỉ:</div>
                                                <div class="text-end">
                                                    <span id="confirm_vat_address"></span>
                                                    <img class="ms-2" src="{{ asset('images/svg/edit.svg') }}"
                                                        alt="edit">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="confirm-section-title">PHƯƠNG THỨC THANH TOÁN</div>
                                <div class="mb-4">
                                    <div class="confirm-content-box confirm-payment-method">
                                        <span class="me-2"><img src="{{ asset('images/d/qr.png') }}"
                                                class="confirm-icon" alt="QR Code"></span>
                                        <span class="flex-grow-1 text-sm color-primary-12">Quét QR & Thanh toán bằng
                                            ứng dụng ngân hàng</span>
                                        <span><svg xmlns="http://www.w3.org/2000/svg" width="50" height="13"
                                                viewBox="0 0 50 13" fill="none">
                                                <path
                                                    d="M8.80423 1.01919C8.43189 0.669219 7.98572 0.401747 7.49644 0.235224C7.00718 0.0687023 6.48647 0.00709859 5.97028 0.0546347H2.21671C2.08552 0.0571098 1.95936 0.103919 1.85999 0.18698C1.76061 0.270059 1.69426 0.384167 1.67244 0.509613L0.00209761 10.1187C-0.0032137 10.1648 0.0015912 10.2114 0.0161738 10.2554C0.0307565 10.2994 0.0547981 10.3398 0.0867223 10.3742C0.118628 10.4086 0.157702 10.4361 0.201338 10.4549C0.244973 10.4736 0.292211 10.4831 0.339919 10.4827H2.21671C2.32897 10.478 2.43725 10.4408 2.52754 10.3761C2.61785 10.3111 2.68607 10.2215 2.72344 10.1187L3.13633 7.49805C3.15028 7.37265 3.21172 7.25655 3.30881 7.17265C3.40589 7.08857 3.53168 7.04234 3.66183 7.04307H4.88174C5.42136 7.10349 5.96803 7.05581 6.48775 6.90257C7.00749 6.74933 7.48916 6.49418 7.90282 6.15277C8.31651 5.81135 8.65332 5.39113 8.89234 4.91814C9.13135 4.44514 9.26746 3.92956 9.29219 3.40324C9.39793 2.99558 9.409 2.57032 9.3246 2.15804C9.24022 1.74575 9.06249 1.35676 8.80423 1.01919ZM6.42071 3.54883C6.21426 4.89557 5.14449 4.89557 4.13103 4.89557H3.54922L3.94335 2.36593C3.95619 2.29186 3.99489 2.2243 4.05297 2.1746C4.11106 2.12491 4.185 2.09607 4.2624 2.09294H4.52515C5.21956 2.09294 5.89521 2.09294 6.21426 2.47512C6.33109 2.62685 6.41046 2.80256 6.44625 2.98866C6.48204 3.17484 6.47329 3.36648 6.42071 3.54883Z"
                                                    fill="#254B86" />
                                                <path
                                                    d="M17.5688 3.49328H15.692C15.6156 3.49728 15.5422 3.52385 15.482 3.56953C15.4215 3.61503 15.3769 3.67727 15.3542 3.74806V4.27584L15.2228 4.07565C14.9386 3.79848 14.5935 3.58718 14.2133 3.45797C13.8328 3.32876 13.4273 3.28472 13.0269 3.32949C12.0002 3.3526 11.0162 3.73296 10.2547 4.40123C9.49328 5.0695 9.00505 5.98092 8.8792 6.96931C8.78427 7.45468 8.7971 7.95407 8.91684 8.43416C9.03658 8.91425 9.26048 9.36414 9.57361 9.75378C9.86719 10.069 10.2313 10.3147 10.6378 10.4725C11.0445 10.6303 11.4829 10.6956 11.9196 10.6637C12.3954 10.6687 12.8674 10.5793 13.3064 10.4009C13.7452 10.2224 14.1419 9.95889 14.4721 9.62639V10.1542C14.4666 10.2382 14.495 10.321 14.5507 10.3853C14.6066 10.4497 14.686 10.4908 14.7724 10.4999H16.4427C16.5731 10.5058 16.7011 10.4643 16.8017 10.3835C16.9023 10.3028 16.9684 10.1886 16.987 10.0632L17.9817 3.85726C18.0027 3.80266 18.0064 3.74333 17.9924 3.68655C17.9783 3.62995 17.9469 3.57863 17.9025 3.5395C17.858 3.50037 17.8024 3.4749 17.7429 3.46671C17.6834 3.45852 17.6226 3.4678 17.5688 3.49328ZM12.8768 8.75283C12.6473 8.7652 12.4179 8.72917 12.2042 8.64764C11.9902 8.56592 11.7973 8.44035 11.6381 8.27965C11.5009 8.09493 11.4037 7.88528 11.3521 7.66325C11.3005 7.44122 11.2956 7.21136 11.3378 6.98751C11.3971 6.49031 11.6415 6.03115 12.0249 5.6961C12.4085 5.36105 12.9048 5.17324 13.4211 5.1676C13.6536 5.15741 13.8856 5.19781 14.1001 5.28607C14.3144 5.37416 14.5057 5.50792 14.6598 5.67717C14.8133 5.84988 14.9221 6.05535 14.9777 6.27683C15.0332 6.49832 15.0336 6.72945 14.9788 6.95111C14.9227 7.45141 14.6763 7.9133 14.2874 8.24653C13.8985 8.57975 13.3955 8.76029 12.8768 8.75283Z"
                                                    fill="#254B86" />
                                                <path
                                                    d="M27.4404 3.49405H25.5637C25.4741 3.49296 25.3856 3.51425 25.3067 3.55557C25.2279 3.59706 25.1613 3.6573 25.1132 3.73064L22.542 7.37047L21.4535 3.82164C21.4182 3.71262 21.3486 3.61708 21.2542 3.54865C21.16 3.48004 21.0459 3.44182 20.928 3.43946H19.0512C18.9996 3.43927 18.9486 3.4511 18.9026 3.47422C18.8566 3.49733 18.8172 3.531 18.7877 3.57213C18.7581 3.61344 18.7393 3.66094 18.7329 3.7108C18.7265 3.76049 18.7327 3.81108 18.7509 3.85804L20.7966 9.75456L18.9198 12.4116C18.8851 12.4582 18.8643 12.5132 18.8598 12.5705C18.8553 12.6277 18.8675 12.6852 18.8947 12.7361C18.9219 12.7871 18.9634 12.8298 19.0142 12.8593C19.0651 12.8888 19.1235 12.9039 19.1826 12.903H21.0594C21.1476 12.9079 21.2356 12.8903 21.3146 12.8521C21.3936 12.8137 21.461 12.756 21.5098 12.6846L27.7032 3.93083C27.7246 3.88588 27.7343 3.83638 27.7317 3.78688C27.7291 3.73737 27.7145 3.68933 27.6887 3.64638C27.6628 3.60361 27.627 3.5674 27.5838 3.54101C27.5405 3.51444 27.4915 3.49842 27.4404 3.49405Z"
                                                    fill="#254B86" />
                                                <path
                                                    d="M36.45 1.0185C36.0769 0.669527 35.6306 0.402709 35.1415 0.23626C34.6524 0.0697926 34.1322 0.00760669 33.6161 0.0539417H29.7311C29.6029 0.0535959 29.4794 0.10033 29.3854 0.184756C29.2914 0.269182 29.2338 0.385002 29.2244 0.50892L27.6291 10.118C27.6237 10.164 27.6286 10.2106 27.6432 10.2547C27.6576 10.2987 27.6819 10.3393 27.7138 10.3737C27.7457 10.4079 27.7847 10.4354 27.8282 10.4541C27.872 10.4729 27.9191 10.4823 27.9669 10.482H29.9563C30.0464 10.4782 30.132 10.4428 30.1975 10.3826C30.263 10.3226 30.3039 10.2416 30.3129 10.1544L30.7633 7.40632C30.781 7.28239 30.8437 7.16864 30.94 7.08529C31.036 7.00194 31.1597 6.95462 31.2888 6.95134H32.5275C33.0662 7.00649 33.6106 6.9548 34.1281 6.79956C34.6455 6.64432 35.125 6.38881 35.5373 6.04848C35.9498 5.70798 36.2867 5.28994 36.5275 4.8195C36.7681 4.34905 36.9078 3.83601 36.938 3.31152C37.0213 2.91732 37.0209 2.51061 36.937 2.11638C36.8531 1.72215 36.6874 1.34861 36.45 1.0185ZM34.0477 3.6027C33.86 4.94944 32.7903 4.94944 31.758 4.94944H31.1762L31.5891 2.41983C31.5914 2.38156 31.6017 2.34412 31.619 2.30967C31.6362 2.27524 31.6605 2.24444 31.6901 2.21907C31.7198 2.19371 31.7543 2.17427 31.7916 2.16187C31.8292 2.14948 31.8686 2.14437 31.9082 2.14684H32.1897C32.8841 2.14684 33.541 2.14684 33.8788 2.52902C33.9809 2.67709 34.0489 2.84489 34.0779 3.02069C34.1072 3.19668 34.0969 3.37649 34.0477 3.5481V3.6027Z"
                                                    fill="#179BD7" />
                                                <path
                                                    d="M45.1956 3.49414H43.3188C43.2407 3.49214 43.1645 3.51653 43.1029 3.56294C43.0414 3.60953 42.9982 3.67504 42.9809 3.74893V4.2767L42.8496 4.07651C42.5699 3.79843 42.2284 3.58641 41.8507 3.45702C41.4731 3.32762 41.07 3.28431 40.6725 3.33035C39.6457 3.35346 38.6617 3.73382 37.9003 4.40191C37.1389 5.07019 36.6505 5.98178 36.5248 6.97018C36.4195 7.45519 36.4272 7.95675 36.5475 8.43849C36.6676 8.92022 36.897 9.36974 37.2192 9.75465C37.508 10.0704 37.8686 10.317 38.2725 10.475C38.6762 10.6328 39.1123 10.6977 39.5464 10.6646C40.025 10.6688 40.4995 10.5791 40.9413 10.4007C41.3832 10.2225 41.7834 9.95939 42.1176 9.62725V10.155C42.1122 10.1985 42.1165 10.2427 42.1302 10.2846C42.1437 10.3265 42.1664 10.365 42.1966 10.3976C42.2269 10.4304 42.2638 10.4564 42.3051 10.4742C42.3466 10.4921 42.3914 10.501 42.4367 10.5008H44.107C44.2343 10.5021 44.3578 10.4584 44.4544 10.3781C44.5511 10.2977 44.6143 10.1861 44.6325 10.064L45.5521 3.85813C45.5615 3.8099 45.5589 3.76021 45.5446 3.71326C45.5304 3.66612 45.5047 3.62317 45.4699 3.5875C45.435 3.55202 45.392 3.52508 45.3444 3.5087C45.2965 3.4925 45.2457 3.48741 45.1956 3.49414ZM40.5224 8.75369C40.2898 8.76734 40.0573 8.73222 39.8401 8.65051C39.6232 8.56879 39.4269 8.44267 39.2649 8.28052C39.127 8.09634 39.0307 7.88614 38.9821 7.66357C38.9337 7.44081 38.934 7.21077 38.9834 6.98838C39.0344 6.49118 39.2737 6.02983 39.6549 5.69369C40.0363 5.35755 40.5325 5.17047 41.0479 5.16846C41.2868 5.14808 41.5272 5.18411 41.7485 5.27365C41.9699 5.36319 42.1655 5.50351 42.3186 5.68241C42.4718 5.8613 42.578 6.07369 42.6279 6.30118C42.6778 6.52848 42.6701 6.76435 42.6056 6.98838C42.5493 7.48212 42.304 7.93746 41.9183 8.26432C41.5326 8.59118 41.0343 8.76571 40.5224 8.75369Z"
                                                    fill="#179BD7" />
                                                <path
                                                    d="M49.4933 0.000715682H47.6165C47.5397 -0.00450747 47.4639 0.0190065 47.4046 0.0664153C47.3451 0.113824 47.3068 0.181507 47.2974 0.255504L45.796 10.1195C45.7877 10.1644 45.7898 10.2107 45.802 10.2547C45.8142 10.2987 45.8363 10.3397 45.8669 10.3744C45.8973 10.4092 45.9354 10.437 45.9784 10.456C46.0212 10.4747 46.0679 10.4842 46.115 10.4835H47.7103C47.8376 10.4847 47.961 10.4411 48.0577 10.3608C48.1544 10.2804 48.2176 10.1688 48.2358 10.0467L49.8123 0.3465C49.8176 0.302932 49.8132 0.258743 49.7997 0.216903C49.786 0.175063 49.7633 0.136499 49.7333 0.103795C49.7031 0.0710912 49.6661 0.0449946 49.6246 0.0272323C49.5833 0.0094517 49.5385 0.000424496 49.4933 0.000715682Z"
                                                    fill="#179BD7" />
                                            </svg></span>
                                    </div>
                                </div>
                                <div class="confirm-section-title">ĐƠN HÀNG</div>
                                <div class="mb-4">
                                    <div class="confirm-content-box">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-total">Tổng thanh toán:</span>
                                            <span id="confirm_total" class="confirm-total-amount">150.000đ</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="confirm-actions">
                                    <button type="button"
                                        class="btn btn-outline-success confirm-btn-half btn-confirm-back"
                                        id="btn-confirm-back">Quay lại</button>
                                    <button type="button" class="btn btn-success confirm-btn-half btn-confirm">Thanh
                                        toán</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Payment QR Code -->
                    <div class="row g-5 px-0 p-md-5 pt-md-0 d-none" id="step-2-content">
                        <!-- Left: Order Information -->
                        <div class="col-12 col-lg-6 d-flex">
                            <div class="payment-order-info flex-grow-1">
                                <h3 class="payment-order-title">Thông tin đơn hàng</h3>

                                <div class="payment-order-field-box">
                                    <div class="payment-order-label">Nhà cung cấp</div>
                                    <div class="payment-order-value" id="payment-supplier">LE THI LAN</div>
                                </div>

                                <div class="payment-order-field-box">
                                    <div class="payment-order-label">Số tài khoản</div>
                                    <div class="payment-order-value" id="payment-account">0989 466 992</div>
                                </div>

                                <div class="payment-order-field-box">
                                    <div class="payment-order-label">Ngân hàng</div>
                                    <div class="payment-order-value" id="payment-bank">MB Bank</div>
                                </div>

                                <div class="payment-order-field-box">
                                    <div class="payment-order-label">Nội dung</div>
                                    <div class="payment-order-value" id="payment-content">GO-QUICK84</div>
                                </div>

                                <div class="payment-order-amount-row">
                                    <div class="payment-order-label">Số tiền</div>
                                    <div class="payment-order-value" id="payment-amount">150.000đ</div>
                                </div>

                                <div class="payment-timer-box">
                                    <div class="payment-timer-label">Đơn hàng sẽ hết hạn sau:</div>
                                    <div class="payment-timer-controls">
                                        <div class="payment-timer-btn">
                                            <div class="payment-timer-number" id="timer-minutes">10</div>
                                            <div class="payment-timer-text">Phút</div>
                                        </div>
                                        <div class="payment-timer-btn">
                                            <div class="payment-timer-number" id="timer-seconds">00</div>
                                            <div class="payment-timer-text">Giây</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: QR Code Payment -->
                        <div class="col-12 col-lg-6 d-flex">
                            <div class="payment-qr-container flex-grow-1 d-flex flex-column">
                                <h3 class="payment-qr-title">Quét mã QR thanh toán trực tiếp</h3>
                                <div
                                    class="payment-qr-box flex-grow-1 d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('images/dev/qr.png') }}" alt="QR Code">
                                </div>
                                <div class="payment-qr-instruction">
                                    <img src="{{ asset('images/d/qr.png') }}" alt="QR Icon"
                                        class="payment-qr-icon">
                                    <span>Mở ứng dụng ngân hàng để Quét Mã QR</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Result overlays -->
                    <div class="payment-result-overlay d-none" id="paymentSuccess">
                        <div class="payment-result-card success">
                            <button type="button" class="payment-result-close" data-result-close>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z"
                                        fill="#1C1C1B" />
                                    <path
                                        d="M9.16937 15.5794C8.97937 15.5794 8.78938 15.5094 8.63938 15.3594C8.34938 15.0694 8.34938 14.5894 8.63938 14.2994L14.2994 8.63938C14.5894 8.34938 15.0694 8.34938 15.3594 8.63938C15.6494 8.92937 15.6494 9.40937 15.3594 9.69937L9.69937 15.3594C9.55937 15.5094 9.35937 15.5794 9.16937 15.5794Z"
                                        fill="#1C1C1B" />
                                    <path
                                        d="M14.8294 15.5794C14.6394 15.5794 14.4494 15.5094 14.2994 15.3594L8.63938 9.69937C8.34938 9.40937 8.34938 8.92937 8.63938 8.63938C8.92937 8.34938 9.40937 8.34938 9.69937 8.63938L15.3594 14.2994C15.6494 14.5894 15.6494 15.0694 15.3594 15.3594C15.2094 15.5094 15.0194 15.5794 14.8294 15.5794Z"
                                        fill="#1C1C1B" />
                                </svg>
                            </button>
                            <div class="result-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="36"
                                    viewBox="0 0 50 36" fill="none">
                                    <path
                                        d="M17.8875 35.8375C16.8875 35.8375 15.9375 35.4375 15.2375 34.7375L1.0875 20.5875C-0.3625 19.1375 -0.3625 16.7375 1.0875 15.2875C2.5375 13.8375 4.9375 13.8375 6.3875 15.2875L17.8875 26.7875L43.5875 1.0875C45.0375 -0.3625 47.4375 -0.3625 48.8875 1.0875C50.3375 2.5375 50.3375 4.9375 48.8875 6.3875L20.5375 34.7375C19.8375 35.4375 18.8875 35.8375 17.8875 35.8375Z"
                                        fill="#227447" />
                                </svg>
                            </div>
                            <h3 class="result-title color-primary">Thanh toán thành công!</h3>
                            <div class="result-info">
                                <div class="d-flex justify-content-between">
                                    <span>Họ và Tên</span>
                                    <span id="success_fullname">Nguyễn Trường</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Số điện thoại</span>
                                    <span id="success_phone">0834 085 578</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Email</span>
                                    <span id="success_email">Truongnguyen@gmail.com</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Đơn hàng</span>
                                    <span id="success_order">GO-SOFT191</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Số tiền</span>
                                    <span id="success_amount">150.000đ</span>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="button" class="btn color-primary btn-success-custom px-5"
                                    id="btn-payment-success-close">Hoàn
                                    thành</button>
                            </div>
                        </div>
                    </div>

                    <div class="payment-result-overlay d-none" id="paymentFailed">
                        <div class="payment-result-card failed">
                            <button type="button" class="payment-result-close" data-result-close>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z"
                                        fill="#505050" />
                                    <path
                                        d="M9.16937 15.5794C8.97937 15.5794 8.78938 15.5094 8.63938 15.3594C8.34938 15.0694 8.34938 14.5894 8.63938 14.2994L14.2994 8.63938C14.5894 8.34938 15.0694 8.34938 15.3594 8.63938C15.6494 8.92937 15.6494 9.40937 15.3594 9.69937L9.69937 15.3594C9.55937 15.5094 9.35937 15.5794 9.16937 15.5794Z"
                                        fill="#505050" />
                                    <path
                                        d="M14.8294 15.5794C14.6394 15.5794 14.4494 15.5094 14.2994 15.3594L8.63938 9.69937C8.34938 9.40937 8.34938 8.92937 8.63938 8.63938C8.92937 8.34938 9.40937 8.34938 9.69937 8.63938L15.3594 14.2994C15.6494 14.5894 15.6494 15.0694 15.3594 15.3594C15.2094 15.5094 15.0194 15.5794 14.8294 15.5794Z"
                                        fill="#505050" />
                                </svg>

                            </button>
                            <div class="result-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"
                                    viewBox="0 0 120 120" fill="none">
                                    <path
                                        d="M45.8508 77.9008C44.9008 77.9008 43.9508 77.5508 43.2008 76.8008C41.7508 75.3508 41.7508 72.9508 43.2008 71.5008L71.5008 43.2008C72.9508 41.7508 75.3508 41.7508 76.8008 43.2008C78.2508 44.6508 78.2508 47.0508 76.8008 48.5008L48.5008 76.8008C47.8008 77.5508 46.8008 77.9008 45.8508 77.9008Z"
                                        fill="#EB5757" />
                                    <path
                                        d="M74.1508 77.9008C73.2008 77.9008 72.2508 77.5508 71.5008 76.8008L43.2008 48.5008C41.7508 47.0508 41.7508 44.6508 43.2008 43.2008C44.6508 41.7508 47.0508 41.7508 48.5008 43.2008L76.8008 71.5008C78.2508 72.9508 78.2508 75.3508 76.8008 76.8008C76.0508 77.5508 75.1008 77.9008 74.1508 77.9008Z"
                                        fill="#EB5757" />
                                </svg>
                            </div>
                            <h3 class="result-title text-failed">Thanh toán thất bại!</h3>
                            <p class="result-message">Rất tiếc, chúng tôi gặp sự cố với khoản thanh toán của bạn, vui
                                lòng thử lại sau.</p>
                            <div class="text-center mt-4">
                                <button type="button" class="btn color-primary btn-success-custom px-5" id="btn-payment-failed-close">Thử
                                    lại</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @vite('resources/assets/frontend/css/components/payment/register-modal.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle modal show with package data
            const modal = document.getElementById('{{ $modalId }}');

            if (!modal) return;

            let currentPackageData = null;
            let currentToolType = 'go-invoice';
            let currentTransactionCode = null;
            let sseEventSource = null;

            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                if (button && button.dataset.package) {
                    try {
                        const packageData = JSON.parse(button.dataset.package);
                        currentPackageData = packageData;
                        updatePackageInfo(packageData);
                        
                        // Set package_id - check both package_id and id
                        const packageIdInput = document.getElementById('package_id_input');
                        if (packageIdInput) {
                            const packageId = packageData.package_id || packageData.id || null;
                            if (packageId) {
                                packageIdInput.value = packageId;
                            } else {
                                console.error('Package ID not found in package data:', packageData);
                            }
                        }
                        
                        // Detect tool type
                        const path = window.location.pathname;
                        if (path.includes('go-invoice')) currentToolType = 'go-invoice';
                        else if (path.includes('go-soft')) currentToolType = 'go-soft';
                        else if (path.includes('go-quick')) currentToolType = 'go-quick';
                        else if (path.includes('go-bot')) currentToolType = 'go-bot';
                    } catch (e) {
                        console.error('Error parsing package data:', e);
                    }
                }
            });

            // Update package information
            function updatePackageInfo(packageData) {
                const packageNameEl = modal.querySelector('[data-package-name]');
                const packageDescEl = modal.querySelector('[data-package-desc]');
                const packagePriceEl = modal.querySelector('[data-package-price]');
                const registrationFeeEl = modal.querySelector('[data-registration-fee]');

                // Get tool name from package data or detect from route
                let toolName = packageData.tool_name || '';
                if (!toolName) {
                    // Try to detect from current route/page
                    const path = window.location.pathname;
                    if (path.includes('go-invoice')) toolName = 'Go Invoice';
                    else if (path.includes('go-soft')) toolName = 'Go Soft';
                    else if (path.includes('go-quick')) toolName = 'Go Quick';
                    else if (path.includes('go-bot')) toolName = 'Go Bot';
                    else toolName = 'Go Invoice'; // default
                }

                // Package name and description
                const packageName = packageData.name || 'Basic';
                const packageDesc = packageData.description || '';
                const packageFullDesc = packageDesc ? `${packageName} - ${packageDesc}` : packageName;

                if (packageNameEl) packageNameEl.textContent = toolName;
                if (packageDescEl) packageDescEl.textContent = packageFullDesc;

                let price = packageData.price;
                if (typeof price === 'string') {
                    price = parseInt(price.replace(/[^\d]/g, '') || 0);
                }
                const formattedPrice = price.toLocaleString('vi-VN');

                if (packagePriceEl) packagePriceEl.textContent = formattedPrice + 'đ';
                if (registrationFeeEl) registrationFeeEl.textContent = formattedPrice + 'đ';

                // Update price badge (discount badge)
                // Priority: discount > badge
                const priceBadgeEl = modal.querySelector('.package-price [data-package-badge]');
                if (priceBadgeEl) {
                    const badgeText = packageData.discount || packageData.badge || null;
                    if (badgeText) {
                        priceBadgeEl.textContent = badgeText;
                        priceBadgeEl.style.display = 'inline-block';
                    } else {
                        priceBadgeEl.style.display = 'none';
                        priceBadgeEl.textContent = '';
                    }
                }

                // Reset discount in price breakdown
                const discountEl = modal.querySelector('[data-discount]');
                if (discountEl) {
                    discountEl.textContent = '0đ';
                }

                calculateTotal();
            }

            // Calculate total amount
            function calculateTotal() {
                const registrationFeeText = modal.querySelector('[data-registration-fee]')?.textContent || '0đ';
                const discountText = modal.querySelector('[data-discount]')?.textContent || '0đ';

                const registrationFee = parseFloat(registrationFeeText.replace(/[^\d]/g, '') || 0);
                const discount = parseFloat(discountText.replace(/[^\d-]/g, '') || 0);
                const total = Math.max(0, registrationFee + discount);

                const totalEl = modal.querySelector('[data-total-amount]');
                if (totalEl) {
                    totalEl.textContent = total.toLocaleString('vi-VN') + 'đ';
                }
            }

            // Handle promo code
            const applyButton = modal.querySelector('.btn-apply');
            if (applyButton) {
                applyButton.addEventListener('click', function() {
                    const promoInput = modal.querySelector('.promo-input');
                    const promoCode = promoInput?.value.trim();

                    if (promoCode) {
                        // TODO: Call API to validate promo code
                        console.log('Applying promo code:', promoCode);
                        // Update discount amount based on API response
                        // For now, just update UI
                        // calculateTotal();
                    }
                });
            }

            // Handle form submission - moved to below

            // Handle remove package
            const removeButton = modal.querySelector('[data-remove-package]');
            if (removeButton) {
                removeButton.addEventListener('click', function() {
                    // TODO: Handle package removal logic
                    console.log('Remove package clicked');
                    hideModal();
                });
            }

            var exportVatCheckbox = document.querySelector('[name="export_vat_invoice"]');
            var vatFields = document.getElementById('vat-fields');
            if (exportVatCheckbox && vatFields) {
                exportVatCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        vatFields.classList.remove('d-none');
                    } else {
                        vatFields.classList.add('d-none');
                    }
                });
            }

            var stepForm = document.getElementById('step-register-form');
            var stepConfirm = document.getElementById('step-confirm-info');
            var step1Content = document.getElementById('step-1-content');
            var step2Content = document.getElementById('step-2-content');

            let currentStep = 'info';
            let paymentTimer = null;
            const minutesEl = document.getElementById('timer-minutes');
            const secondsEl = document.getElementById('timer-seconds');

            function stopPaymentTimer() {
                if (paymentTimer) {
                    clearInterval(paymentTimer);
                    paymentTimer = null;
                }
                if (minutesEl) minutesEl.textContent = '10';
                if (secondsEl) secondsEl.textContent = '00';
            }

            function showInfoStep() {
                stopPaymentTimer();
                currentStep = 'info';
                stepItems.forEach(item => item.classList.remove('error'));
                if (step1Content) step1Content.classList.remove('d-none');
                if (step2Content) step2Content.classList.add('d-none');
                if (stepForm) stepForm.classList.remove('d-none');
                if (stepConfirm) stepConfirm.classList.add('d-none');
                setStepActive(0);
            }

            function showConfirmStep() {
                stopPaymentTimer();
                currentStep = 'confirm';
                stepItems[2]?.classList.remove('error');
                if (step1Content) step1Content.classList.remove('d-none');
                if (step2Content) step2Content.classList.add('d-none');
                if (stepForm) stepForm.classList.add('d-none');
                if (stepConfirm) stepConfirm.classList.remove('d-none');
                setStepActive(0);
            }

            function showPaymentStep() {
                currentStep = 'payment';
                if (step1Content) step1Content.classList.add('d-none');
                if (step2Content) step2Content.classList.remove('d-none');
                setStepActive(1);
                startPaymentTimer();
            }

            function hideModal() {
                stopPaymentTimer();
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    let bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (!bootstrapModal) {
                        bootstrapModal = new bootstrap.Modal(modal);
                    }
                    bootstrapModal.hide();
                } else {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                    modal.setAttribute('aria-modal', 'false');
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
            }

            // Step activation for progress bar
            var stepItems = document.querySelectorAll('.progress-steps .step-item');

            function setStepActive(stepIdx) {
                stepItems.forEach((item, idx) => {
                    if (idx <= stepIdx) item.classList.add('active');
                    else item.classList.remove('active');
                });
            }

            stepItems.forEach((item, idx) => {
                item.addEventListener('click', function() {
                    if (idx === 0) {
                        if (currentStep === 'success') return;

                        const stepHasError = stepItems[2].classList.contains('error');
                        if (stepHasError) {
                            showConfirmStep();
                            return;
                        }

                        if (currentStep === 'payment') {
                            showConfirmStep();
                        } else if (currentStep === 'confirm') {
                            showInfoStep();
                        }
                    }
                });
            });

            const headerBackButton = modal.querySelector('.btn-back');
            if (headerBackButton) {
                headerBackButton.addEventListener('click', function() {
                    if (currentStep === 'payment') {
                        showConfirmStep();
                    } else if (currentStep === 'confirm') {
                        showInfoStep();
                    } else {
                        hideModal();
                    }
                });
            }


            document.getElementById('registrationForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                // lấy thông tin
                const form = this;
                const fullName = form.first_name.value.trim() + ' ' + form.last_name.value.trim();
                const email = form.email.value.trim();
                const phone = form.country_code.value + ' ' + form.phone.value.trim();
                // Nạp vào block confirm
                document.getElementById('confirm_fullname').textContent = fullName;
                document.getElementById('confirm_email').textContent = email;
                document.getElementById('confirm_phone').textContent = phone;
                // VAT fields
                const checked = form.export_vat_invoice.checked;
                const vatRow = document.getElementById('row-confirm-vat');
                if (checked) {
                    vatRow?.classList.remove('d-none');
                    document.getElementById('confirm_vat_mst').textContent = form.vat_mst.value.trim();
                    document.getElementById('confirm_vat_company').textContent = form.vat_company.value
                        .trim();
                    document.getElementById('confirm_vat_address').textContent = form.vat_address.value
                        .trim();
                } else {
                    vatRow?.classList.add('d-none');
                }
                // Tổng thanh toán lấy lại từ modal (cho đúng số), có thể tuỳ chỉnh để lấy động hơn nếu cần
                var totalText = document.querySelector('[data-total-amount]')?.textContent;
                document.getElementById('confirm_total').textContent = totalText || '';
                // Hiện form xác nhận, ẩn form đăng ký - KHÔNG active step thanh toán
                showConfirmStep();
            });

            document.getElementById('btn-confirm-back')?.addEventListener('click', function() {
                // Quay lại form đăng ký
                showInfoStep();
            });

            // Khi nhấn nút "Thanh toán"
            document.querySelector('.btn-confirm')?.addEventListener('click', async function() {
                const btn = this;
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';

                try {
                    // Lấy dữ liệu từ form
                    const form = document.getElementById('registrationForm');
                    const formData = new FormData(form);
                    
                    // Thêm package_id - check both package_id and id
                    const packageIdInput = document.getElementById('package_id_input');
                    let packageId = null;
                    
                    if (packageIdInput && packageIdInput.value) {
                        packageId = packageIdInput.value;
                    } else if (currentPackageData) {
                        packageId = currentPackageData.package_id || currentPackageData.id;
                    }
                    
                    if (!packageId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không tìm thấy thông tin gói. Vui lòng thử lại.',
                            confirmButtonColor: '#3085d6'
                        });
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        return;
                    }
                    
                    formData.append('package_id', packageId);
                    
                    // Xử lý phone
                    const countryCode = formData.get('country_code');
                    const phone = formData.get('phone');
                    formData.set('phone', countryCode + phone);
                    
                    // Xử lý export_vat_invoice
                    if (formData.get('export_vat_invoice') === 'on') {
                        formData.set('export_vat_invoice', '1');
                    } else {
                        formData.set('export_vat_invoice', '0');
                    }

                    // Gọi API tạo purchase
                    const routeMap = {
                        'go-invoice': '{{ route("payment.go-invoice.store") }}',
                        'go-bot': '{{ route("payment.go-bot.store") }}',
                        'go-soft': '{{ route("payment.go-soft.store") }}',
                        'go-quick': '{{ route("payment.go-quick.store") }}',
                    };
                    
                    const response = await fetch(routeMap[currentToolType], {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        currentTransactionCode = result.transaction_code;
                        // Load payment info
                        await loadPaymentInfo(result.transaction_code);
                        // Ẩn step 1, hiện step 2
                        showPaymentStep();
                    } else {
                        // Handle validation errors
                        let errorMessage = result.message || 'Có lỗi xảy ra khi tạo đơn hàng.';
                        if (result.errors) {
                            const errorList = Object.values(result.errors).flat().join('<br>');
                            errorMessage = errorList || errorMessage;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            html: errorMessage,
                            confirmButtonColor: '#3085d6'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra khi tạo đơn hàng.',
                        confirmButtonColor: '#3085d6'
                    });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });

            // Load payment info
            async function loadPaymentInfo(transactionCode) {
                try {
                    const response = await fetch(`{{ route('payment.info') }}?transaction_code=${transactionCode}&tool_type=${currentToolType}`);
                    const result = await response.json();

                    if (result.success) {
                        // Update bank info
                        document.getElementById('payment-supplier').textContent = result.bank.account_name;
                        document.getElementById('payment-account').textContent = result.bank.account_number;
                        document.getElementById('payment-bank').textContent = result.bank.name;
                        document.getElementById('payment-content').textContent = result.content;
                        document.getElementById('payment-amount').textContent = result.amount;
                        
                        // Update QR code
                        const qrImg = document.querySelector('.payment-qr-box img');
                        if (qrImg && result.qr_code) {
                            qrImg.src = result.qr_code;
                        }
                        
                        // Start SSE
                        startSSE(transactionCode);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: result.message || 'Không thể tải thông tin thanh toán.',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                } catch (error) {
                    console.error('Error loading payment info:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra khi tải thông tin thanh toán.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            }

            // Start SSE to check transaction status
            function startSSE(transactionCode) {
                // Close existing SSE if any
                if (sseEventSource) {
                    sseEventSource.close();
                }

                const sseUrl = `{{ route('payment.sse') }}?transaction_code=${transactionCode}&tool_type=${currentToolType}`;
                sseEventSource = new EventSource(sseUrl);

                sseEventSource.onmessage = function(event) {
                    try {
                        const data = JSON.parse(event.data);
                        
                        if (data.type === 'close') {
                            sseEventSource.close();
                            return;
                        }

                        if (data.status === 'success') {
                            stopPaymentTimer();
                            markSuccessStep();
                            closeAllOverlays();
                            if (successOverlay) {
                                successOverlay.classList.remove('d-none');
                                // Update success info
                                document.getElementById('success_fullname').textContent = document.getElementById('confirm_fullname').textContent;
                                document.getElementById('success_phone').textContent = document.getElementById('confirm_phone').textContent;
                                document.getElementById('success_email').textContent = document.getElementById('confirm_email').textContent;
                                document.getElementById('success_order').textContent = transactionCode;
                                document.getElementById('success_amount').textContent = document.getElementById('payment-amount').textContent;
                            }
                            sseEventSource.close();
                        } else if (data.status === 'expired') {
                            stopPaymentTimer();
                            markErrorStep();
                            closeAllOverlays();
                            if (failedOverlay) {
                                failedOverlay.classList.remove('d-none');
                            }
                            sseEventSource.close();
                        }
                    } catch (e) {
                        console.error('Error parsing SSE data:', e);
                    }
                };

                sseEventSource.onerror = function(error) {
                    console.error('SSE error:', error);
                    // SSE will auto-reconnect
                };
            }

            // Timer function
            function startPaymentTimer() {
                stopPaymentTimer();
                let minutes = 10;
                let seconds = 0;

                if (minutesEl) minutesEl.textContent = minutes;
                if (secondsEl) secondsEl.textContent = '00';

                paymentTimer = setInterval(function() {
                    if (seconds === 0) {
                        if (minutes === 0) {
                            stopPaymentTimer();
                            // Timer expired - show failed
                            markErrorStep();
                            closeAllOverlays();
                            if (failedOverlay) {
                                failedOverlay.classList.remove('d-none');
                            }
                            if (sseEventSource) {
                                sseEventSource.close();
                            }
                            return;
                        }
                        minutes--;
                        seconds = 59;
                    } else {
                        seconds--;
                    }

                    if (minutesEl) minutesEl.textContent = minutes;
                    if (secondsEl) secondsEl.textContent = (seconds < 10 ? '0' : '') + seconds;
                }, 1000);
            }

            const successOverlay = document.getElementById('paymentSuccess');
            const failedOverlay = document.getElementById('paymentFailed');

            function closeAllOverlays() {
                if (successOverlay) successOverlay.classList.add('d-none');
                if (failedOverlay) failedOverlay.classList.add('d-none');
            }

            function setupOverlayClose(overlay) {
                overlay?.addEventListener('click', function(e) {
                    // Chỉ đóng khi click vào overlay background hoặc nút close X, không đóng khi click vào button
                    if (e.target === overlay || (e.target.closest('[data-result-close]') && !e.target.closest('button.btn'))) {
                        overlay.classList.add('d-none');
                    }
                });
            }

            setupOverlayClose(successOverlay);
            setupOverlayClose(failedOverlay);

            // Xử lý button "Hoàn thành" - reload trang
            const btnSuccessClose = document.getElementById('btn-payment-success-close');
            if (btnSuccessClose) {
                btnSuccessClose.addEventListener('click', function() {
                    window.location.reload();
                });
            }

            // Xử lý button "Thử lại" - tắt modal
            const btnFailedClose = document.getElementById('btn-payment-failed-close');
            if (btnFailedClose) {
                btnFailedClose.addEventListener('click', function() {
                    closeAllOverlays();
                    hideModal();
                });
            }

            function markSuccessStep() {
                // clear error
                stepItems.forEach(item => item.classList.remove('error'));
                // mark step 3 active
                setStepActive(2);
                currentStep = 'success';
            }

            function markErrorStep() {
                // remove active on step 3, add error (label giữ nguyên)
                stepItems.forEach((item, idx) => {
                    if (idx === 2) {
                        item.classList.remove('active');
                        item.classList.add('error');
                    }
                });
                currentStep = 'payment';
            }

            // Close SSE when modal closes
            modal.addEventListener('hidden.bs.modal', function() {
                if (sseEventSource) {
                    sseEventSource.close();
                    sseEventSource = null;
                }
                stopPaymentTimer();
                showInfoStep();
                const formEl = modal.querySelector('#registrationForm');
                if (formEl) formEl.reset();
                currentTransactionCode = null;
            });
        });
    </script>
@endpush
