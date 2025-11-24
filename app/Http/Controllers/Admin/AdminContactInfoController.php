<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class AdminContactInfoController extends Controller
{
    /**
     * Display the contact info edit page.
     */
    public function index()
    {
        $contactInfo = ContactInfo::first();
        
        if (!$contactInfo) {
            $contactInfo = ContactInfo::create([
                'phone' => '0989 466 992',
                'email' => 'supportgotax@gmail.com',
                'address' => '2321 New Design Str, Lorem Ipsum10',
            ]);
        }

        return view('admin.pages.contact-info.index', compact('contactInfo'));
    }

    /**
     * Update the contact info.
     */
    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'map_url' => 'nullable|url|max:1000',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
        ], [
            'email.email' => 'Email không hợp lệ.',
            'map_url.url' => 'URL bản đồ không hợp lệ.',
        ]);

        $contactInfo = ContactInfo::first();
        
        if (!$contactInfo) {
            $contactInfo = new ContactInfo();
        }

        $contactInfo->update($request->only([
            'phone',
            'email',
            'address',
            'map_url',
            'latitude',
            'longitude',
        ]));

        return redirect()->route('admin.contact-info.index')
            ->with('success', 'Đã cập nhật thông tin liên hệ thành công!');
    }
}
