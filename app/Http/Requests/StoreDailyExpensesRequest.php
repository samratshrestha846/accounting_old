<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyExpensesRequest extends FormRequest
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
            'vendor_id' => ['required','exists:vendors,id'],
            'purchase_date' => ['required','date_format:Y-m-d'],
            'bill_number' => ['required','string','max:255'],
            'bill_amount' => ['required','numeric'],
            'paid_amount' => ['required','numeric'],
            'purpose' => ['required'],
            'bill_image' => ['nullable','image']
        ];
    }
}
