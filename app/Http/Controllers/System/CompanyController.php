<?php

namespace App\Http\Controllers\System;

use Alert;
use App\System\Company;
use Illuminate\Support\Str;
use App\Mail\CompanyDetailsMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CompanyRequest;
use Illuminate\Support\Facades\Artisan;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();
        return view('pages.system.company-settings')->with(compact('company'));
    }

    public function update(CompanyRequest $request)
    {
        $updateCompanyDetails = Company::updateOrCreate(['id' => $request->id],[
                                    'company_name' => $request->company_name,
                                    'logo' => $request->hasFile('logo') ? $request->logo->getClientOriginalName() : $request->logo,
                                    'phone' => $request->phone,
                                    'invoice_prefix' => $request->invoice_prefix,
                                    'city' => $request->city,
                                    'zip_code' => $request->zip_code,
                                    'country' => $request->country,
                                    'postal_address' => $request->postal_address,
                                    'bank_details' => $request->bank_details,
                                    'mail_from_email' => $request->mail_from_email,
                                    'mail_from_name' => $request->mail_from_name,
                                ]);
        $keyValues = [];
        if($updateCompanyDetails)
        {
            if($request->has('logo'))
            {
                $updateMedia = $updateCompanyDetails->addMediaFromRequest('logo')->toMediaCollection('avatar');
                $keyValues = [
                    'APP_LOGO' => $updateCompanyDetails->logo,
                ];
            }

            $keyValues['APP_NAME'] = str_replace(' ', '-', $updateCompanyDetails->company_name);
            $keyValues['MAIL_FROM_ADDRESS'] = $updateCompanyDetails->mail_from_email;
            $keyValues['MAIL_FROM_NAME'] = str_replace(' ', '-', $updateCompanyDetails->mail_from_name);

            if($this->setEnvironmentValue($keyValues))
            {
                $this->refreshApp();
                if($request->test_mail)
                Mail::to($request->test_mail_address)
                    ->send(new CompanyDetailsMail($updateCompanyDetails));

                Alert::success('Success', 'Company details updated successfully.');
                return redirect()->back();
                // return response(['status' => 'success', 'message' => 'Company details added successfully.']);
            }
        }

        Alert::alert('Oops!', 'Company details not updated.');
        return redirect()->back();
    }

    public function setEnvironmentValue($keyValues)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $str .= "\n"; // In case the searched variable is in the last line without \n

        if (count($keyValues) > 0) {
            foreach ($keyValues as $envKey => $envValue) {
                // If key does not exist, add it
                if (!$oldValue = env($envKey)) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return true;
    }

    public function refreshApp()
    {
        $configClear = Artisan::call('config:clear');
        $cacheClear = Artisan::call('cache:clear');
        // $routeClear = Artisan::call('route:cache');
        // $viewClear = Artisan::call('view:cache');
        return true; //Return anything
    }


}
