<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\OrderSetting;
use App\Models\SMTPSetting;
use App\Models\GoogleSetting;
use App\Models\PaypalSetting;
use App\Providers\SMTPSettingsServiceProvider;

class SettingController extends Controller
{
    public function index()
    {
        $smtpSetting = SMTPSetting::first() ?? new SMTPSetting();

        return view('admin.pages.settings.index', compact(
            'smtpSetting',
        ));
    }

    public function updateSMTP(Request $request)
    {
        $request->validate([
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|string|in:tls,ssl',
            'from_address' => 'required|email',
            'from_name' => 'nullable|string',
            'admin_email' => 'required|email',
        ], [
            'port.required' => 'Port không được để trống.',
            'port.integer' => 'Port phải là số nguyên.',
            'port.min' => 'Port phải lớn hơn 0.',
            'port.max' => 'Port không được vượt quá 65535.',
            'encryption.in' => 'Encryption phải là TLS hoặc SSL.',
        ]);

        $smtpSetting = SMTPSetting::first();
        $isNew = !$smtpSetting;
        
        if ($isNew) {
            $smtpSetting = new SMTPSetting();
            $request->validate([
                'password' => 'required|string',
            ], [
                'password.required' => 'Password là bắt buộc khi tạo mới cài đặt SMTP.',
            ]);
        }

        $data = $request->all();
        
        if (empty($data['password']) && !$isNew) {
            unset($data['password']);
        }

        $smtpSetting->fill($data);
        $smtpSetting->save();

        SMTPSettingsServiceProvider::loadSMTPConfig();

        return redirect()->route('admin.setting.index', ['tab' => 'smtp'])
            ->with('success', 'Cài đặt SMTP đã được cập nhật thành công.');
    }


}
