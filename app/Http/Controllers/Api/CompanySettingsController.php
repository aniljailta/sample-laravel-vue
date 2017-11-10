<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Requests\CompanySettings\UploadLogoRequest;
use App\Http\Requests\CompanySettings\UploadInventoryFormFooterGraphicRequest;
use App\Http\Requests\CompanySettings\UpdateCompanySettingRequest;
use App\Http\Requests\CompanySettings\TestEmailRequest;
use App\Http\Controllers\Controller;
use App\Mail\Company\TestEmail;
use App\Models\File;
use App\Models\ManufacturerCompany;
use App\Models\RtoCompany;
use App\Repositories\Settings\CompanySettingRepository;
use App\Services\Files\FileService;
use Store;
use DB;
use Event;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySettingsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $companySettings = Store::get('company_settings');
        $settings = $companySettings->getVisible();
        if ($request->id) {
            $settings = is_array($request->id) ?: [$request->id];
        }

        // Hide mail settings if not admin
        if (!Auth::user() || !Auth::user()->hasRole('administrator')) {
            $adminScopedSettings = [
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
            ];

            foreach ($adminScopedSettings as $adminScoped) {
                $key = array_search($adminScoped, $settings);
                if ($key !== false) {
                    unset($settings[$key]);
                }
            }
        }

        $companySettings->setVisible($settings);
        return response()->json($companySettings);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanySettingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanySettingRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // Tenant company
                $company = Store::get('company');

                // company_logo should be uploaded by another method
                if ($company->role_id === 'manufacturer') {
                    $settingValues = array_only($request->all(), (new ManufacturerCompany)->getFillable());
                }

                if ($company->role_id === 'rto') {
                    $settingValues = array_only($request->all(), (new RtoCompany)->getFillable());
                }

                if ($company->role_id === 'super_admin') {
                    $settingValues = array_only($request->all(), (new SuperAdminCompany)->getFillable());
                }

                $settingValues = array_except($settingValues, ['company_logo']);
                $settingRepository = new CompanySettingRepository();
                $settingRepository->update($company, $settingValues);
            });

            return response()->json(['msg' => 'Settings successfully updated.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Store a newly created logo file in storage.
     *
     * @param UploadLogoRequest $request
     * @param FileService $fileService
     * @return \Illuminate\Http\Response
     */
    public function uploadLogo(UploadLogoRequest $request, FileService $fileService)
    {
        try {
            // Tenant company
            $company = Store::get('company');

            // Add files
            $file = $request->file('upload_files');
            $fileService->store($file, [
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'key' => $company->id,
                'type' => 'company',
                'id' => $company->id,
                'category_id' => 'company_logo',
            ]);

            $message = $fileService->success() ? $fileService->messages : ['Logo successfully uploaded.'];
            $payload = $fileService->files;

            return response()->json(['message' => $message, 'payload' => $payload], 200);
        } catch (Exception $e) {
            Log::error($e);

            $message = $fileService->error() ? $fileService->errors : ['Logo has not been uploaded.'];
            return response()->json($message, 422);
        }

        return response()->json(['Logo has not been uploaded.'], 422);
    }

    /**
     * Store a newly created inventory form footer file in storage.
     *
     * @param UploadInventoryFormFooterGraphicRequest $request
     * @param FileService $fileService
     * @return \Illuminate\Http\Response
     */
    public function uploadInventoryFormFooter(UploadInventoryFormFooterGraphicRequest $request, FileService $fileService)
    {
        try {
            // Tenant company
            $company = Store::get('company');

            $fileCategory = File::$categories[$request->category_id];
            // Add files
            $file = $request->file('upload_files');
            $fileService->store($file, [
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'key' => $company->id,
                'type' => 'company',
                'id' => $company->id,
                'category_id' => $fileCategory['id'],
            ]);

            $message = $fileService->success() ? $fileService->messages : ["{$fileCategory['title']} successfully uploaded."];
            $payload = $fileService->files;

            return response()->json(['message' => $message, 'payload' => $payload], 200);
        } catch (Exception $e) {
            Log::error($e);

            $message = $fileService->error() ? $fileService->errors : ["{$fileCategory['title']} has not been uploaded."];
            return response()->json($message, 422);
        }

        return response()->json(["{$fileCategory['title']} has not been uploaded."], 422);
    }

    /**
     * Test email
     *
     * @param TestEmailRequest $request
     * @return \Illuminate\Http\Response
     */
    public function testEmail(TestEmailRequest $request)
    {
        $email = new TestEmail($request->email);

        // preview
        if ($request->exists('preview')) {
            $email->build();
            return response()->json([
                'to' => $email->to,
                'subject' => $email->subject,
                'body' => view($email->view, $email->buildViewData())->render()
            ]);
        }

        Mail::send($email);
        if (Mail::failures()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email is not sent.'
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Email successfully submitted.'
        ]);
    }
}
