<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Qrcode;
use Illuminate\Http\Request;
use Store;
use Exception;

class AuthController extends Controller
{
    /**
     * Show the application dashboard to the user.
     * TODO: validate for read permission?
     * @param Request $request
     * @return Response
     */
    public function auth(Request $request)
    {
        // X-Original-Request
        $path = $request->server('HTTP_X_ORIGINAL_REQUEST', false);
        if (!$path) return response('', 404);

        // cut url and get path in format as in files table (db)
        $storageUrl = url(config('filesystems.disks.public.url'));
        $path = urldecode($path);
        $pathWithoutStorageUrl = str_replace($storageUrl, '', $path);
        $firstDirectory = substr($pathWithoutStorageUrl, 1, strpos($pathWithoutStorageUrl, '/', 1)-1);

        $company = Store::get('company');
        try {
            if ($firstDirectory === 'qrcodes') {
                $file = Qrcode::select(MANUFACTURER_COMPANY_ID)
                    ->where('path', substr($pathWithoutStorageUrl, 1))
                    ->firstOrFail();

                $fileCompany = $file->manufacturer;
            } else {
                $file = File::select(COMPANY_ID)
                    ->where('path', $pathWithoutStorageUrl)
                    ->latest()
                    ->firstOrFail();

                $fileCompany = $file->company;
            }

            if ($company->domain === $fileCompany->domain) {
                return response('', 200);
            }
        } catch (Exception $e) {
            return response('', 403);
        }

        return response('', 403);
    }
}
