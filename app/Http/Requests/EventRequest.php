<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'name' => 'required',
            'owner' => 'required|integer',
            'type' => 'required|alpha',
            'date' => 'required|date',
            'minimumUsers' => 'required|integer',
            'maximumUsers' => 'required|integer',
            'valueToBePaid' => 'required|numeric',
            'address' => 'required'
        ];
    }
}
