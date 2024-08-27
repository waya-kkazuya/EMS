<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3' ,'max:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_of_use_id' => ['required', 'exists:locations,id'],
            'requestor' => ['required', 'min:3' ,'max:20'],
            'remarks_from_requestor' => ['required', 'max:500'],
            'request_status' => ['nullable'], // admin・staffが変更する、enum型か、テーブルを追加するか
            'manufacturer' => ['nullable', 'max:20'],
            'reference' => ['nullable', 'max:20'],
            'price' => ['nullable', 'integer', 'max:1000000'],,
        ];
    }
}
