<?php

namespace App\Http\Controllers\System;

// use Alert;
use App\System\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use RealRashid\SweetAlert\Facades\Alert;

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
            $addMedia = $companyDetails->addMediaFromRequest('logo')->withResponsiveImages()->toMediaCollection('logo');
        }

        Alert::success('Success', 'Company details added successfully.');
        return redirect()->back();
        // return response(['status' => 'success', 'message' => 'Company details added successfully.']);
    }

    public function update(CompanyRequest $request)
    // public function update(Request $request)
    {
        $companyDetails = Company::find($request->id);
        $updateCompanyDetails = $companyDetails->update([
                                    'company_name' => $request->company_name,
                                    'logo' => $request->hasFile('logo') ? $request->logo->getClientOriginalName() : $companyDetails->logo,
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

        if($request->hasFile('logo') && $updateCompanyDetails)
        {
            $addMedia = $companyDetails->addMediaFromRequest('logo');
        }

        if($updateCompanyDetails)
        {
            Alert::success('Success', 'Company details updated successfully.');
            return redirect()->back();
            // return response(['status' => 'success', 'message' => 'Company details added successfully.']);
        }
    }

    public function setEnvironmentValue($values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $str .= "\n"; // In case the searched variable is in the last line without \n

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
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
