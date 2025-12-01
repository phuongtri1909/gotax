<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Policy;
use Illuminate\Support\Str;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            [
                'title' => 'Chính sách bảo mật',
                'content' => '<h1>Chính sách bảo mật</h1>
                    <h2>1. Giới thiệu</h2>
                    <p>Chúng tôi cam kết bảo vệ thông tin cá nhân và dữ liệu của bạn. Chính sách bảo mật này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ thông tin của bạn.</p>
                    
                    <h2>2. Thông tin chúng tôi thu thập</h2>
                    <h3>2.1. Thông tin cá nhân</h3>
                    <p>Chúng tôi có thể thu thập các thông tin sau:</p>
                    <ul>
                        <li>Họ và tên</li>
                        <li>Email</li>
                        <li>Số điện thoại</li>
                        <li>Địa chỉ</li>
                        <li>Thông tin thanh toán</li>
                    </ul>
                    
                    <h3>2.2. Thông tin sử dụng</h3>
                    <p>Chúng tôi cũng thu thập thông tin về cách bạn sử dụng dịch vụ của chúng tôi, bao gồm:</p>
                    <ul>
                        <li>Lịch sử sử dụng dịch vụ</li>
                        <li>Thông tin thiết bị</li>
                        <li>Địa chỉ IP</li>
                    </ul>
                    
                    <h2>3. Cách chúng tôi sử dụng thông tin</h2>
                    <p>Chúng tôi sử dụng thông tin của bạn để:</p>
                    <ol>
                        <li>Cung cấp và cải thiện dịch vụ</li>
                        <li>Xử lý thanh toán</li>
                        <li>Gửi thông báo và cập nhật</li>
                        <li>Hỗ trợ khách hàng</li>
                        <li>Tuân thủ pháp luật</li>
                    </ol>
                    
                    <h2>4. Bảo vệ thông tin</h2>
                    <p>Chúng tôi sử dụng các biện pháp bảo mật tiên tiến để bảo vệ thông tin của bạn:</p>
                    <ul>
                        <li>Mã hóa dữ liệu</li>
                        <li>Bảo mật kết nối SSL/TLS</li>
                        <li>Kiểm soát truy cập nghiêm ngặt</li>
                        <li>Backup dữ liệu định kỳ</li>
                    </ul>
                    
                    <h2>5. Quyền của bạn</h2>
                    <p>Bạn có quyền:</p>
                    <ul>
                        <li>Truy cập thông tin cá nhân của mình</li>
                        <li>Yêu cầu chỉnh sửa thông tin</li>
                        <li>Yêu cầu xóa thông tin</li>
                        <li>Từ chối nhận email marketing</li>
                    </ul>
                    
                    <h2>6. Liên hệ</h2>
                    <p>Nếu bạn có câu hỏi về chính sách bảo mật, vui lòng liên hệ với chúng tôi qua email: supportgotax@gmail.com</p>',
                'slug' => 'chinh-sach-bao-mat',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'title' => 'Điều khoản sử dụng',
                'content' => '<h1>Điều khoản sử dụng</h1>
                    <h2>1. Chấp nhận điều khoản</h2>
                    <p>Bằng việc sử dụng dịch vụ GoTax, bạn đồng ý với các điều khoản và điều kiện được nêu trong tài liệu này.</p>
                    
                    <h2>2. Đăng ký tài khoản</h2>
                    <h3>2.1. Yêu cầu</h3>
                    <p>Để sử dụng dịch vụ, bạn cần:</p>
                    <ul>
                        <li>Đăng ký tài khoản với thông tin chính xác</li>
                        <li>Bảo mật thông tin đăng nhập</li>
                        <li>Chịu trách nhiệm về mọi hoạt động trên tài khoản của bạn</li>
                    </ul>
                    
                    <h3>2.2. Quyền và nghĩa vụ</h3>
                    <p>Bạn có quyền:</p>
                    <ul>
                        <li>Sử dụng dịch vụ theo gói đã đăng ký</li>
                        <li>Nhận hỗ trợ từ đội ngũ kỹ thuật</li>
                        <li>Yêu cầu hoàn tiền trong trường hợp hợp lệ</li>
                    </ul>
                    
                    <p>Bạn có nghĩa vụ:</p>
                    <ul>
                        <li>Sử dụng dịch vụ đúng mục đích</li>
                        <li>Không chia sẻ tài khoản với người khác</li>
                        <li>Không sử dụng dịch vụ cho mục đích bất hợp pháp</li>
                    </ul>
                    
                    <h2>3. Thanh toán và hoàn tiền</h2>
                    <h3>3.1. Thanh toán</h3>
                    <p>Bạn có thể thanh toán bằng các phương thức:</p>
                    <ul>
                        <li>Chuyển khoản ngân hàng</li>
                        <li>Ví điện tử</li>
                        <li>Thẻ tín dụng/ghi nợ</li>
                    </ul>
                    
                    <h3>3.2. Hoàn tiền</h3>
                    <p>Chúng tôi sẽ hoàn tiền trong các trường hợp:</p>
                    <ul>
                        <li>Dịch vụ không hoạt động do lỗi hệ thống</li>
                        <li>Yêu cầu hoàn tiền trong vòng 7 ngày kể từ ngày đăng ký</li>
                        <li>Các trường hợp khác theo quy định</li>
                    </ul>
                    
                    <h2>4. Giới hạn trách nhiệm</h2>
                    <p>Chúng tôi không chịu trách nhiệm về:</p>
                    <ul>
                        <li>Thiệt hại gián tiếp phát sinh từ việc sử dụng dịch vụ</li>
                        <li>Mất mát dữ liệu do lỗi người dùng</li>
                        <li>Gián đoạn dịch vụ do nguyên nhân khách quan</li>
                    </ul>
                    
                    <h2>5. Chấm dứt dịch vụ</h2>
                    <p>Chúng tôi có quyền chấm dứt dịch vụ nếu:</p>
                    <ul>
                        <li>Bạn vi phạm điều khoản sử dụng</li>
                        <li>Bạn sử dụng dịch vụ cho mục đích bất hợp pháp</li>
                        <li>Tài khoản của bạn bị đình chỉ</li>
                    </ul>
                    
                    <h2>6. Thay đổi điều khoản</h2>
                    <p>Chúng tôi có quyền thay đổi điều khoản này bất cứ lúc nào. Thay đổi sẽ có hiệu lực sau 30 ngày kể từ ngày công bố.</p>',
                'slug' => 'dieu-khoan-su-dung',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Chính sách hoàn tiền',
                'content' => '<h1>Chính sách hoàn tiền</h1>
                    <h2>1. Điều kiện hoàn tiền</h2>
                    <p>Chúng tôi cam kết hoàn tiền trong các trường hợp sau:</p>
                    
                    <h3>1.1. Hoàn tiền trong 7 ngày đầu</h3>
                    <p>Bạn có thể yêu cầu hoàn tiền trong vòng 7 ngày kể từ ngày đăng ký gói dịch vụ nếu:</p>
                    <ul>
                        <li>Dịch vụ không đáp ứng được yêu cầu như đã cam kết</li>
                        <li>Gặp sự cố kỹ thuật nghiêm trọng không thể khắc phục</li>
                        <li>Không hài lòng với chất lượng dịch vụ</li>
                    </ul>
                    
                    <h3>1.2. Hoàn tiền do lỗi hệ thống</h3>
                    <p>Chúng tôi sẽ hoàn tiền nếu:</p>
                    <ul>
                        <li>Dịch vụ không hoạt động do lỗi từ phía chúng tôi</li>
                        <li>Mất dữ liệu do lỗi hệ thống</li>
                        <li>Không thể sử dụng dịch vụ trong thời gian dài</li>
                    </ul>
                    
                    <h2>2. Quy trình hoàn tiền</h2>
                    <h3>Bước 1: Gửi yêu cầu</h3>
                    <p>Gửi email yêu cầu hoàn tiền đến: supportgotax@gmail.com với các thông tin:</p>
                    <ul>
                        <li>Mã đơn hàng</li>
                        <li>Lý do hoàn tiền</li>
                        <li>Thông tin tài khoản nhận tiền</li>
                    </ul>
                    
                    <h3>Bước 2: Xử lý yêu cầu</h3>
                    <p>Chúng tôi sẽ xem xét yêu cầu trong vòng 3-5 ngày làm việc.</p>
                    
                    <h3>Bước 3: Hoàn tiền</h3>
                    <p>Sau khi được chấp nhận, tiền sẽ được hoàn về tài khoản của bạn trong vòng 7-14 ngày.</p>
                    
                    <h2>3. Các trường hợp không được hoàn tiền</h2>
                    <p>Chúng tôi không hoàn tiền trong các trường hợp:</p>
                    <ul>
                        <li>Đã sử dụng hết hạn mức của gói dịch vụ</li>
                        <li>Yêu cầu hoàn tiền sau 7 ngày kể từ ngày đăng ký</li>
                        <li>Vi phạm điều khoản sử dụng</li>
                        <li>Sử dụng dịch vụ cho mục đích bất hợp pháp</li>
                    </ul>
                    
                    <h2>4. Phương thức hoàn tiền</h2>
                    <p>Tiền sẽ được hoàn về:</p>
                    <ul>
                        <li>Tài khoản ngân hàng (nếu thanh toán bằng chuyển khoản)</li>
                        <li>Ví điện tử (nếu thanh toán bằng ví điện tử)</li>
                        <li>Thẻ tín dụng/ghi nợ (nếu thanh toán bằng thẻ)</li>
                    </ul>
                    
                    <h2>5. Liên hệ</h2>
                    <p>Nếu bạn có thắc mắc về chính sách hoàn tiền, vui lòng liên hệ: supportgotax@gmail.com</p>',
                'slug' => 'chinh-sach-hoan-tien',
                'order' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($policies as $policy) {
            Policy::create($policy);
        }
    }
}
