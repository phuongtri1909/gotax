<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
        ]);

        try {

            $oldSessionId = session()->getId();

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Tài khoản không tồn tại.',
                ]);
            }

            if ($user->active == false) {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Tài khoản đã bị khóa.',
                ]);
            }

            if (!password_verify($request->password, $user->password)) {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Mật khẩu không chính xác.',
                ]);
            }


            Auth::login($user);


            $user->ip_address = $request->ip();
            $user->save();

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại sau.');
        }
    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

}
