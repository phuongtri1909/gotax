<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class QRCodeService
{
    /**
     * Generate QR code for bank using VietQR API
     */
    public function generateBankQRCode($bank, $transactionCode, $amount)
    {
        try {
            $accountNo = $bank->account_number;
            $accountName = $bank->account_name;
            $bankCode = $bank->code;
            $description = $transactionCode;
            
            $qrData = $this->callVietQRAPI($bankCode, $accountNo, $accountName, $amount, $description);
            
            if ($qrData) {
                return $qrData;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Call VietQR API to generate QR code
     */
    private function callVietQRAPI($bankCode, $accountNo, $accountName, $amount, $description)
    {
        try {
            $url = "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.jpg";
            
            $params = [
                'amount' => (int)$amount,
                'addInfo' => $description,
                'accountName' => $accountName
            ];
            
            $queryString = http_build_query($params);
            $fullUrl = $url . '?' . $queryString;
            
            $ch = curl_init($fullUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200 && !empty($imageData)) {
                $base64 = base64_encode($imageData);
                return 'data:image/jpeg;base64,' . $base64;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('VietQR API Exception: ' . $e->getMessage());
            return null;
        }
    }
}

