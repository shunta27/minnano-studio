<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StudioCreateRequest extends ApiRequest
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
            'name' => 'required|string',
            'url' => 'present|nullable|url',
            'tel' => 'required|string',
            'zip' => 'required|string',
            'prefecture' => 'required|string',
            'city_1' => 'required|string',
            'city_2' => 'present|nullable|string',
            'studio_count' => 'present|nullable|integer',
            'open_dt' => 'present|nullable|string',
            'end_dt' => 'present|nullable|string',
            'cheapest_price' => 'present|nullable|numeric',
            'is_web_reservation' => 'present|nullable|boolean',
        ];
    }
}
