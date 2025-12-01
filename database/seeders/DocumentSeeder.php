<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            [
                'title' => 'Hướng dẫn sử dụng Go Invoice',
                'content' => '<h1>Hướng dẫn sử dụng Go Invoice</h1>
                    <h2>Giới thiệu</h2>
                    <p>Go Invoice là công cụ giúp bạn tải hóa đơn điện tử hàng loạt một cách nhanh chóng và hiệu quả.</p>
                    
                    <h2>Các bước sử dụng</h2>
                    <h3>Bước 1: Đăng ký tài khoản</h3>
                    <p>Đầu tiên, bạn cần đăng ký tài khoản trên hệ thống GoTax.</p>
                    
                    <h3>Bước 2: Chọn gói dịch vụ</h3>
                    <p>Sau khi đăng ký, bạn cần chọn gói dịch vụ phù hợp với nhu cầu của mình.</p>
                    <ul>
                        <li>Gói Basic: Phù hợp cho cá nhân</li>
                        <li>Gói Professional: Phù hợp cho doanh nghiệp nhỏ</li>
                        <li>Gói Enterprise: Phù hợp cho doanh nghiệp lớn</li>
                    </ul>
                    
                    <h3>Bước 3: Tải hóa đơn</h3>
                    <p>Sau khi đăng ký gói, bạn có thể bắt đầu tải hóa đơn điện tử.</p>
                    <ol>
                        <li>Đăng nhập vào hệ thống</li>
                        <li>Chọn công cụ Go Invoice</li>
                        <li>Nhập thông tin cần thiết</li>
                        <li>Nhấn nút tải hóa đơn</li>
                    </ol>
                    
                    <h2>Lưu ý quan trọng</h2>
                    <p>Khi sử dụng Go Invoice, bạn cần lưu ý:</p>
                    <ul>
                        <li>Đảm bảo thông tin MST chính xác</li>
                        <li>Kiểm tra kết nối internet ổn định</li>
                        <li>Lưu trữ hóa đơn đã tải về an toàn</li>
                    </ul>',
                'slug' => 'huong-dan-su-dung-go-invoice',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'title' => 'Hướng dẫn sử dụng Go Bot',
                'content' => '<h1>Hướng dẫn sử dụng Go Bot</h1>
                    <h2>Giới thiệu</h2>
                    <p>Go Bot là công cụ tra cứu mã số thuế hàng loạt, giúp bạn kiểm tra rủi ro nhà cung cấp một cách nhanh chóng.</p>
                    
                    <h2>Tính năng chính</h2>
                    <ul>
                        <li>Tra hàng loạt MST cá nhân cũ → MST cá nhân mới</li>
                        <li>Tra hàng loạt CCCD → MST cá nhân và doanh nghiệp</li>
                        <li>Tra cứu rủi ro nhà cung cấp hàng loạt</li>
                        <li>Tra địa chỉ Doanh nghiệp sau sáp nhập</li>
                    </ul>
                    
                    <h2>Cách sử dụng</h2>
                    <h3>Tra cứu MST</h3>
                    <p>Bạn có thể tra cứu MST bằng cách:</p>
                    <ol>
                        <li>Nhập danh sách MST vào file Excel hoặc TXT</li>
                        <li>Upload file lên hệ thống</li>
                        <li>Chờ hệ thống xử lý</li>
                        <li>Tải kết quả về máy</li>
                    </ol>
                    
                    <h3>Tra cứu rủi ro</h3>
                    <p>Để tra cứu rủi ro nhà cung cấp:</p>
                    <ol>
                        <li>Chọn chức năng tra cứu rủi ro</li>
                        <li>Nhập thông tin nhà cung cấp</li>
                        <li>Xem kết quả phân tích</li>
                    </ol>',
                'slug' => 'huong-dan-su-dung-go-bot',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Hướng dẫn sử dụng Go Soft',
                'content' => '<h1>Hướng dẫn sử dụng Go Soft</h1>
                    <h2>Giới thiệu</h2>
                    <p>Go Soft là công cụ tra cứu và tải tờ khai doanh nghiệp hàng loạt.</p>
                    
                    <h2>Tính năng</h2>
                    <ul>
                        <li>Tải tờ khai, giấy nộp tiền, thông báo hàng loạt</li>
                        <li>Thời gian tải: Tháng - Quý - Năm</li>
                        <li>Chuyển file XML thành file Excel</li>
                        <li>Tải về dữ liệu dưới dạng XML</li>
                        <li>Không giới hạn số lượng tra cứu</li>
                        <li>Không giới hạn thiết bị truy cập</li>
                    </ul>
                    
                    <h2>Hướng dẫn sử dụng</h2>
                    <h3>Bước 1: Đăng nhập</h3>
                    <p>Đăng nhập vào tài khoản GoTax của bạn.</p>
                    
                    <h3>Bước 2: Chọn Go Soft</h3>
                    <p>Từ menu công cụ, chọn Go Soft.</p>
                    
                    <h3>Bước 3: Chọn loại tờ khai</h3>
                    <p>Chọn loại tờ khai bạn muốn tải:</p>
                    <ul>
                        <li>Tờ khai thuế GTGT</li>
                        <li>Tờ khai thuế TNDN</li>
                        <li>Giấy nộp tiền</li>
                        <li>Thông báo</li>
                    </ul>
                    
                    <h3>Bước 4: Chọn thời gian</h3>
                    <p>Chọn khoảng thời gian bạn muốn tải tờ khai.</p>
                    
                    <h3>Bước 5: Tải về</h3>
                    <p>Nhấn nút tải về và chờ hệ thống xử lý.</p>',
                'slug' => 'huong-dan-su-dung-go-soft',
                'order' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Hướng dẫn sử dụng Go Quick',
                'content' => '<h1>Hướng dẫn sử dụng Go Quick</h1>
                    <h2>Giới thiệu</h2>
                    <p>Go Quick là công cụ đọc và trích xuất thông tin từ CCCD hàng loạt.</p>
                    
                    <h2>Tính năng</h2>
                    <ul>
                        <li>Hỗ trợ tải ảnh CCCD lên để đọc dữ liệu tự động</li>
                        <li>Quét và trích xuất thông tin CCCD hàng loạt</li>
                        <li>Hỗ trợ đọc đa dạng file: PDF, Excel, Folder chứa ảnh</li>
                        <li>Xử lý linh hoạt phân loại mặt trước, mặt sau CCCD</li>
                        <li>Tự động nhận diện thông tin khi nhập link ảnh</li>
                        <li>Trích xuất dữ liệu hàng loạt ra file Excel</li>
                    </ul>
                    
                    <h2>Cách sử dụng</h2>
                    <h3>Đọc từ ảnh</h3>
                    <ol>
                        <li>Chọn chức năng đọc từ ảnh</li>
                        <li>Upload ảnh CCCD (mặt trước và mặt sau)</li>
                        <li>Hệ thống sẽ tự động nhận diện và trích xuất thông tin</li>
                        <li>Xem và chỉnh sửa thông tin nếu cần</li>
                        <li>Xuất file Excel</li>
                    </ol>
                    
                    <h3>Đọc từ file</h3>
                    <ol>
                        <li>Chọn chức năng đọc từ file</li>
                        <li>Upload file PDF hoặc Excel chứa ảnh CCCD</li>
                        <li>Hệ thống sẽ xử lý và trích xuất thông tin</li>
                        <li>Tải kết quả về máy</li>
                    </ol>
                    
                    <h2>Lưu ý</h2>
                    <p>Để đạt kết quả tốt nhất:</p>
                    <ul>
                        <li>Ảnh CCCD phải rõ nét, đủ ánh sáng</li>
                        <li>Đảm bảo ảnh không bị mờ, không bị che khuất</li>
                        <li>File upload không quá 10MB</li>
                    </ul>',
                'slug' => 'huong-dan-su-dung-go-quick',
                'order' => 4,
                'status' => 'active',
            ],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
