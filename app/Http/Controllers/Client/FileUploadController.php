<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class FileUploadController extends Controller
{
    private $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private $allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
    private $dangerousExtensions = ['php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'sh', 'js', 'asp', 'aspx', 'jsp', 'py', 'rb', 'pl', 'cgi', 'htaccess'];

    public function uploadImage(Request $request)
    {
        $request->validate([
            'upload' => 'required|file|max:10240',
        ]);

        if (!$request->hasFile('upload')) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'Không tìm thấy file upload.'
                ]
            ], 422);
        }

        $file = $request->file('upload');
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        if (!$this->isFileSafe($file)) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'File không được phép upload. Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP) và tài liệu (PDF, DOC, DOCX, XLS, XLSX, TXT).'
                ]
            ], 422);
        }

        try {
            $now = Carbon::now();
            $yearMonth = $now->format('Y/m');
            $timestamp = $now->format('YmdHis');
            $randomString = substr(md5(uniqid()), 0, 8);
            
            Storage::disk('public')->makeDirectory("uploads/{$yearMonth}");

            if (in_array($extension, $this->allowedImageExtensions)) {
                $image = Image::make($file);
                
                $maxWidth = 1920;
                if ($image->width() > $maxWidth) {
                    $image->resize($maxWidth, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                
                $image->encode('webp', 85);
                $fileName = "{$timestamp}_{$randomString}.webp";
                $path = "uploads/{$yearMonth}/{$fileName}";
                
                Storage::disk('public')->put($path, $image->stream());
            } else {
                $fileName = "{$timestamp}_{$randomString}.{$extension}";
                $path = "uploads/{$yearMonth}/{$fileName}";
                $file->storeAs("uploads/{$yearMonth}", $fileName, 'public');
            }

            $url = Storage::disk('public')->url($path);

            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'Lỗi khi upload file: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    private function isFileSafe($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        if (in_array($extension, $this->dangerousExtensions)) {
            return false;
        }

        $allowedExtensions = array_merge($this->allowedImageExtensions, $this->allowedDocumentExtensions);
        if (!in_array($extension, $allowedExtensions)) {
            return false;
        }

        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain'
        ];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            return false;
        }

        return true;
    }
}
