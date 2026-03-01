<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
        $sliderId = $this->route('slider');
        return [
            'title' => 'required|string|max:1000',
            'short_description' => 'required|string|max:3000',
            'video_url' => 'required|string',
            'image' => $sliderId ? 'nullable' : 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'description.required' => 'Mô tả không được để trống',
            'image.required' => 'Ảnh không được để trống',
            'video_url.required' => 'Video URL không được để trống',
        ];
    }
}
