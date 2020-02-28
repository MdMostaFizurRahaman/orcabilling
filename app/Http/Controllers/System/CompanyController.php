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
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function index()
    {
        return view('pages.system.company.index');
    }

    public function dataTable()
    {
        $outputs = Company::select(['company_name', 'city', 'country', 'default_company', 'id']);

        return DataTables::of($outputs)
            ->addColumn('company_name', function ($query) {
                return $query->default_company ? '<h6>' . $query->company_name . '<span class="badge badge-success">Default</span></h6>' : '<h6>'.$query->company_name.'</h6>';
            })
            ->addColumn('view', function($query){
                return '<a href="'.route('company.settings.view', $query->id).'" class="btn btn-sm btn-success view"><i class="fas fa-binoculars"></i></a>';
            })
            ->addColumn('edit',function ($query) {
                return '<a href="'.route('company.settings.edit', $query->id).'" class="btn btn-sm btn-info edit"><i class="fa fa-edit"></i></a>';
            })
            ->addColumn('delete',function ($query) {
                return '<a href="'.route('company.settings.delete', $query->id).'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></a>';
            })
            ->addColumn('logo', function ($query) {
                return '<img width="100" height="40" src="'.asset($query->logo).'" alt="'.$query->company_name.' logo">';
            })
            ->rawColumns(['company_name', 'logo', 'view', 'edit', 'delete'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.system.company.company-settings');
    }

    public function edit(Company $company)
    {
        return view('pages.system.company.company-settings')->with(compact('company'));
    }

    public function update(CompanyRequest $request)
    {
        $updateCompanyDetails = Company::updateOrCreate(['id' => $request->id],[
                                    'company_name' => $request->company_name,
                                    'avatar' => $request->hasFile('logo') ? $request->logo->getClientOriginalName() : $request->avatar,
                                    'phone' => $request->phone,
                                    'invoice_prefix' => $request->invoice_prefix,
                                    'city' => $request->city,
                                    'zip_code' => $request->zip_code,
                                    'country' => $request->country,
                                    'postal_address' => $request->postal_address,
                                    'bank_details' => $request->bank_details,
                                    'default_company' => $request->default_company,
                                    'mail_from_email' => $request->mail_from_email,
                                    'mail_from_name' => $request->mail_from_name,
                                ]);

        if($updateCompanyDetails)
        {
            $keyValues = [];

            if($request->has('logo'))
            {
                $updateMedia = $updateCompanyDetails->addMediaFromRequest('logo')->toMediaCollection('avatar');
            }

            if($request->default_company)
            {
                $keyValues ['APP_LOGO'] = $updateCompanyDetails->logo;
                $keyValues['APP_NAME'] = str_replace(' ', '-', $updateCompanyDetails->company_name);
                $keyValues['MAIL_FROM_ADDRESS'] = $updateCompanyDetails->mail_from_email;
                $keyValues['MAIL_FROM_NAME'] = str_replace(' ', '-', $updateCompanyDetails->mail_from_name);

                if($old_default_company = Company::whereDefaultCompany(1)->where('id', '!=', $updateCompanyDetails->id)->first())
                {
                    $update_default_company = $old_default_company->update(['default_company' => 0]);
                }

                if($this->setEnvironmentValue($keyValues))
                {
                    $this->refreshApp();

                    if($request->test_mail) {
                        Mail::to($request->test_mail_address)
                            ->send(new CompanyDetailsMail($updateCompanyDetails));
                    }
                }
            }

            Alert::success('Success', 'Company details updated successfully.');
            return redirect()->back();
            // return response(['status' => 'success', 'message' => 'Company details added successfully.']);
        }

        Alert::alert('Oops!', 'Company details not updated.');
        return redirect()->back();
    }

    public function delete(Company $company)
    {
        if($company->default_company)
        {
            return response(['status' => 'warning', 'message' => 'Default company can\'t be deleted.']);
        } else {

            if($company->hasInvoices())
            {
                return response(['status' => 'warning', 'message' => 'Company has invoices in record.']);
            } else {
                if($company->delete())
                return response(['status' => 'success', 'message' => 'Company deleted successfully.']);
            }
        }
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
        // $cacheClear = Artisan::call('cache:clear');
        // $routeClear = Artisan::call('route:cache');
        // $viewClear = Artisan::call('view:cache');
        return true; //Return anything
    }

    public function companies()
    {
        return $companies = Company::all();
    }


}
