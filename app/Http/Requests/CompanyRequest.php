<?php

namespace App\Http\Requests;

use App\System\Company;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch($this->method()) {
            case 'GET':
            case 'DELETE':
            case 'POST':
            case 'PUT':
            case 'PATCH':
                return $company = $this->id ? Company::find($this->id)->exists : true;
                // return $company = Company::find($this->route('id'))->exists();

                // return $company && $this->user()->can('update', $id);
            default:break;
        }
        return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method()) {
            case 'GET':
            case 'DELETE':
                return [];
            case 'POST':
            case 'PUT':
            case 'PATCH':
                return [
                    'company_name' => 'required|unique:companies,company_name,' . $this->id ?: NULL,
                    'logo' => 'required_without:id|image|mimes:jpeg,jpg,png,gif|max:1024',
                    'phone' => 'required',
                    'invoice_prefix' => 'required|unique:companies,invoice_prefix,' . $this->id ?: NULL,
                    'city' => 'required',
                    'zip_code' => 'required',
                    'country' => 'required',
                    'postal_address' => 'required|max:100',
                    'bank_details' => 'max:100',
                    'mail_from_email' => 'required|email',
                    'mail_from_name' => 'required',
                    'test_mail' => 'boolean',
                    'default_company' => 'boolean',
                    'test_mail_address' => 'bail|required_if:test_mail,1|nullable|email',
                ];
            default:break;
        }

    }
}
