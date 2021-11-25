<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'client.phone' => ['sometimes', 'phone:RU', 'required'],
            'client.name' => ['required'],
            'client.last_name' => ['required'],
            'client.email' => ['email', 'required'],
            'client.birthday' => ['date_format:Y-m-d', 'required'],
            'client.service_id' => ['exists:services,id'],
            'client.assessment' => ['required']
        ];
    }
}
