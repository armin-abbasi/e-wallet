<?php

namespace App\Http\Requests\Invoice;

use App\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'string',
            'type'        => 'required|string|in:' . implode(',', Invoice::TYPES),
            'amount'      => 'required|numeric',
        ];
    }
}
