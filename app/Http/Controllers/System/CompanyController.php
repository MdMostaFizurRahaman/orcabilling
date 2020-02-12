<?php

namespace App\Http\Controllers\System;

use Alert;
use App\System\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();
        return view('pages.system.company-settings')->with(compact('company'));
    }

    public function testMail(Request $request, $mail_form_email, $mail_from_name, $test_mail_address)
    {
        $keyValues = [
                        'MAIL_FROM_ADDRESS' => $mail_form_email,
                        'MAIL_FROM_NAME' => $mail_from_name,
                    ];

        if($this->setEnvironmentValue($keyValues)) return 'true';

        return 'false';
    }


    public function store(CompanyRequest $request)
    {
        $companyDetails = Company::firstOrCreate(['company_name' => $request->company_name],
                                    [
                                        'logo' => $request->logo->getClientOriginalName(),
                                        'phone' => $request->phone,
                                        'invoice_prefix' => $request->invoice_prefix,
                                        'city' => $request->city,
                                        'zip_code' => $request->zip_code,
                                        'country' => $request->country,
                                        'postal_address' => $request->postal_address,
                                        'bank_details' => $request->bank_details,
                                        'mail_from_email' => $request->mail_from_email,
                                        'mail_from_name' => $request->mail_from_email,
                                    ]);
        if($request->has('logo') && $companyDetails)
        {
            return $addMedia = $companyDetails->addMediaFromRequest('logo')->withResponsiveImages()->toMediaCollection('logo');
        }

        $keyValues = [
            'MAIL_NAME' => $request->mail_form_email,
            'MAIL_LOGO' => $request->mail_form_email,
            'MAIL_FROM_ADDRESS' => $request->mail_form_email,
            'MAIL_FROM_NAME' => $request->mail_from_name,
        ];

        if($this->setEnvironmentValue($keyValues)) return 'true';
        Alert::success('Success', 'Company details added successfully.');
        return redirect()->back();
        // return response(['status' => 'success', 'message' => 'Company details added successfully.']);
    }

    public function update(CompanyRequest $request)
    {
        // return dump($companyDetails = Company::find($request->id));
        // return $request->company_name;
        $CompanyDetails = Company::updateOrCreate(['id' => $request->id],[
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
        if($request->has('logo') && $CompanyDetails)
        {
            $updateMedia = $CompanyDetails->addMediaFromRequest('logo')->withResponsiveImages()->toMediaCollection('logo');
            $app_logo = asset('public').$CompanyDetails->getFirstMediaUrl('logo');
            $keyValues = [
                'APP_LOGO' => $app_logo,
            ];
        }

        $keyValues['APP_NAME'] = str_replace(' ', '-', $CompanyDetails->company_name);
        $keyValues['MAIL_FROM_ADDRESS'] = $CompanyDetails->mail_from_email;
        $keyValues['MAIL_FROM_NAME'] = str_replace(' ', '-', $CompanyDetails->mail_from_name);

        if($this->setEnvironmentValue($keyValues))
        {
            return 'true';
            // Alert::success('Success', 'Company details updated successfully.');
            // return redirect()->back();
            // return response(['status' => 'success', 'message' => 'Company details added successfully.']);
        }

        Alert::alert('Success', 'Company details not updated.');
        return redirect()->back();;
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


}
