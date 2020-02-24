<?php

namespace App\Http\Requests;

use App\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
                return $invoice = $this->id ? Invoice::find($this->id)->exists : true;
                // return $invoice = Invoice::find($this->route('id'))->exists();

                // return $invoice && $this->user()->can('update', $id);
            default:break;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'client_id' => 'required|required|string|nullable',
                'prefix' => 'string|nullable',
                'from_date' => 'required|date|before:tomorrow',
                'to_date' => 'required|date|after_or_equal:from_date',
                'company_id' => 'string|nullable',
                'generate_invoice' => 'boolean'
        ];
    }
}
