<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GoSoftController extends Controller
{
    private function getApiUrl()
    {
        return config('services.go_soft.url', env('GO_SOFT_API_URL', 'http://127.0.0.1:5000/api/go-soft'));
    }

    private function getApiKey()
    {
        return config('services.go_soft.api_key', env('API_KEY', null));
    }

    private function makeApiRequest($method, $endpoint, $data = [], $timeout = 60)
    {
        $url = $this->getApiUrl() . $endpoint;
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($apiKey = $this->getApiKey()) {
            $headers['X-API-Key'] = $apiKey;
        }

        try {
            $response = Http::timeout($timeout)->withHeaders($headers);

            if ($method === 'GET') {
                $response = $response->get($url, $data);
            } else {
                $response = $response->$method($url, $data);
            }

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            // Kiểm tra nếu là lỗi timeout hoặc connection
            $errorMessage = $e->getMessage();
            if (str_contains(strtolower($errorMessage), 'timeout') || 
                str_contains(strtolower($errorMessage), 'timed out') ||
                str_contains(strtolower($errorMessage), 'connection')) {
                Log::error('Go Soft API Connection/Timeout Error: ' . $errorMessage);
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối API: Timeout hoặc không thể kết nối. Vui lòng thử lại sau.',
                    'status' => 500,
                ];
            }
            Log::error('Go Soft API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Tạo session mới
     */
    public function createSession(Request $request)
    {
        try {
            $result = $this->makeApiRequest('POST', '/session/create');

            if ($result['success'] && isset($result['data']['session_id'])) {
                return response()->json([
                    'status' => 'success',
                    'session_id' => $result['data']['session_id'],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result['data']['message'] ?? 'Không thể tạo session',
            ], $result['status'] ?? 500);
        } catch (\Exception $e) {
            Log::error('Create session error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo session: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Khởi tạo login và lấy captcha
     */
    public function initLogin(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $result = $this->makeApiRequest('POST', '/login/init', [
                'session_id' => $request->session_id,
            ]);

            if ($result['success'] && isset($result['data']['captcha_base64'])) {
                return response()->json([
                    'status' => 'success',
                    'captcha_base64' => $result['data']['captcha_base64'],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result['data']['message'] ?? 'Không thể lấy captcha',
            ], $result['status'] ?? 500);
        } catch (\Exception $e) {
            Log::error('Init login error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi khởi tạo login: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit login
     */
    public function submitLogin(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|string',
        ]);

        try {
            $result = $this->makeApiRequest('POST', '/login/submit', [
                'session_id' => $request->session_id,
                'username' => $request->username,
                'password' => $request->password,
                'captcha' => $request->captcha,
            ]);

            if ($result['success']) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đăng nhập thành công',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result['data']['message'] ?? 'Đăng nhập thất bại',
            ], $result['status'] ?? 401);
        } catch (\Exception $e) {
            Log::error('Submit login error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi đăng nhập: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lấy danh sách loại tờ khai
     */
    public function getTokhaiTypes(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $result = $this->makeApiRequest('GET', '/tokhai/types?session_id=' . urlencode($request->session_id));

            if ($result['success'] && isset($result['data']['tokhai_types'])) {
                return response()->json([
                    'status' => 'success',
                    'tokhai_types' => $result['data']['tokhai_types'],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result['data']['message'] ?? 'Không thể lấy danh sách tờ khai',
            ], $result['status'] ?? 500);
        } catch (\Exception $e) {
            Log::error('Get tokhai types error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách tờ khai: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crawl tờ khai (sync)
     */
    public function crawlTokhai(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'tokhai_type' => 'nullable|string',
        ]);

        try {
            $data = [
                'session_id' => $request->session_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            if ($request->tokhai_type) {
                $data['tokhai_type'] = (string) $request->tokhai_type;
            }

            // Tăng timeout cho API crawl lên 10 phút (600 giây) vì crawl có thể mất nhiều thời gian
            $result = $this->makeApiRequest('POST', '/crawl/tokhai/sync', $data, 600);

            if ($result['success']) {
                $zipBase64 = $result['data']['zip_base64'] ?? null;
                if ($zipBase64 !== null && !is_string($zipBase64)) {
                    if (is_array($zipBase64) || is_object($zipBase64)) {
                        $zipBase64 = json_encode($zipBase64);
                    } else {
                        $zipBase64 = (string) $zipBase64;
                    }
                }
                
                return response()->json([
                    'status' => 'success',
                    'total' => $result['data']['total'] ?? 0,
                    'files_count' => $result['data']['files_count'] ?? ($result['data']['total'] ?? 0),
                    'results' => $result['data']['results'] ?? [],
                    'zip_base64' => $zipBase64,
                    'zip_filename' => $result['data']['zip_filename'] ?? null,
                ]);
            }

            // Kiểm tra nếu là lỗi timeout
            $errorMessage = $result['data']['message'] ?? $result['message'] ?? 'Crawl thất bại';
            if (str_contains(strtolower($errorMessage), 'timeout') || str_contains(strtolower($errorMessage), 'timed out')) {
                $errorMessage = 'Quá trình crawl mất quá nhiều thời gian. Vui lòng thử lại với khoảng thời gian ngắn hơn hoặc chọn loại tờ khai cụ thể.';
            }

            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
            ], $result['status'] ?? 500);
        } catch (\Exception $e) {
            Log::error('Crawl tokhai error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi crawl tờ khai: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crawl thông báo (sync)
     */
    public function crawlThongbao(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
        ]);

        try {
            $data = [
                'session_id' => $request->session_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            // Tăng timeout cho API crawl lên 10 phút (600 giây)
            $result = $this->makeApiRequest('POST', '/crawl/thongbao/sync', $data, 600);

            if ($result['success']) {
                $zipBase64 = $result['data']['zip_base64'] ?? null;
                if ($zipBase64 !== null && !is_string($zipBase64)) {
                    if (is_array($zipBase64) || is_object($zipBase64)) {
                        $zipBase64 = json_encode($zipBase64);
                    } else {
                        $zipBase64 = (string) $zipBase64;
                    }
                }
                
                return response()->json([
                    'status' => 'success',
                    'total' => $result['data']['total'] ?? 0,
                    'files_count' => $result['data']['files_count'] ?? ($result['data']['total'] ?? 0),
                    'results' => $result['data']['results'] ?? [],
                    'zip_base64' => $zipBase64,
                    'zip_filename' => $result['data']['zip_filename'] ?? null,
                ]);
            }

            $errorMessage = $result['data']['message'] ?? $result['message'] ?? 'Crawl thất bại';
            if (str_contains(strtolower($errorMessage), 'timeout') || str_contains(strtolower($errorMessage), 'timed out')) {
                $errorMessage = 'Quá trình crawl mất quá nhiều thời gian. Vui lòng thử lại với khoảng thời gian ngắn hơn.';
            }

            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
            ], $result['status'] ?? 500);
        } catch (\Exception $e) {
            Log::error('Crawl thongbao error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi crawl thông báo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crawl giấy nộp tiền thuế (sync)
     */
    public function crawlGiayNopTien(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
        ]);

        try {
            $data = [
                'session_id' => $request->session_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            $result = $this->makeApiRequest('POST', '/crawl/giaynoptien/sync', $data, 600);

            if ($result['success']) {
                $zipBase64 = $result['data']['zip_base64'] ?? null;
                
                if ($zipBase64 !== null && !is_string($zipBase64)) {
                    if (is_array($zipBase64) || is_object($zipBase64)) {
                        $zipBase64 = json_encode($zipBase64);
                    } else {
                        $zipBase64 = (string) $zipBase64;
                    }
                }
                
                return response()->json([
                    'status' => 'success',
                    'total' => $result['data']['total'] ?? 0,
                    'files_count' => $result['data']['files_count'] ?? ($result['data']['total'] ?? 0),
                    'results' => $result['data']['results'] ?? [],
                    'zip_base64' => $zipBase64,
                    'zip_filename' => $result['data']['zip_filename'] ?? null,
                ]);
            }

            $errorMessage = $result['data']['message'] ?? $result['message'] ?? 'Crawl thất bại';
            if (str_contains(strtolower($errorMessage), 'timeout') || str_contains(strtolower($errorMessage), 'timed out')) {
                $errorMessage = 'Quá trình crawl mất quá nhiều thời gian. Vui lòng thử lại với khoảng thời gian ngắn hơn.';
            }

            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
            ], $result['status'] ?? 500);
        } catch (\Exception $e) {
            Log::error('Crawl giaynoptien error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi crawl giấy nộp tiền thuế: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Kiểm tra trạng thái session
     */
    public function checkSessionStatus(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $result = $this->makeApiRequest('GET', '/session/status', [
                'session_id' => $request->session_id
            ]);

            if ($result['success']) {
                return response()->json([
                    'status' => 'success',
                    'session_id' => $result['data']['session_id'] ?? $request->session_id,
                    'is_logged_in' => $result['data']['is_logged_in'] ?? false,
                    'username' => $result['data']['username'] ?? null,
                ]);
            }

            $responseData = $result['data'] ?? [];
            
            $errorCode = $responseData['error_code'] ?? null;
            $message = $responseData['message'] ?? 'Session không hợp lệ';
            
            if (!$errorCode && ($result['status'] ?? 0) == 404) {
                $errorCode = 'SESSION_NOT_FOUND';
                $message = $message === 'Session không hợp lệ' ? 'Session not found or expired' : $message;
            }

            return response()->json([
                'status' => 'error',
                'error_code' => $errorCode,
                'message' => $message,
            ], $result['status'] ?? 404);
        } catch (\Exception $e) {
            Log::error('Check session status error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error_code' => 'CHECK_SESSION_ERROR',
                'message' => 'Lỗi khi kiểm tra session: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Đóng session
     */
    public function closeSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $result = $this->makeApiRequest('POST', '/session/close', [
                'session_id' => $request->session_id,
            ]);

            return response()->json([
                'status' => $result['success'] ? 'success' : 'error',
                'message' => $result['data']['message'] ?? ($result['success'] ? 'Đóng session thành công' : 'Đóng session thất bại'),
            ], $result['status'] ?? 200);
        } catch (\Exception $e) {
            Log::error('Close session error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi đóng session: ' . $e->getMessage(),
            ], 500);
        }
    }
}

