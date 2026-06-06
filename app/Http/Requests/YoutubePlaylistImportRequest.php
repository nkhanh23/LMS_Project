<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YoutubePlaylistImportRequest extends FormRequest
{
    protected $dontFlash = [
        'youtube_api_key',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'youtube_api_key' => ['required', 'string', 'max:255'],
            'playlist_url' => ['required', 'url', 'max:500'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['required', 'integer', 'exists:sub_categories,id'],
            'course_name' => ['nullable', 'string', 'max:255'],
            'course_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'section_title' => ['nullable', 'string', 'max:255'],
            'selling_price' => ['required', 'integer', 'min:0'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'lte:selling_price'],
            'label' => ['nullable', 'string', 'max:100'],
            'certificate' => ['nullable', 'in:yes,no'],
            'max_videos' => ['nullable', 'integer', 'min:1', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'youtube_api_key.required' => 'Vui lòng nhập YouTube API key.',
            'playlist_url.required' => 'Vui lòng nhập link danh sách phát YouTube.',
            'playlist_url.url' => 'Link danh sách phát YouTube không hợp lệ.',
            'category_id.required' => 'Vui lòng chọn danh mục chính.',
            'subcategory_id.required' => 'Vui lòng chọn danh mục con.',
            'selling_price.required' => 'Vui lòng nhập giá khóa học.',
            'discount_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá bán.',
            'max_videos.max' => 'Chỉ có thể import tối đa 200 video mỗi lần.',
        ];
    }
}
