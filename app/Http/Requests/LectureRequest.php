<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureRequest extends FormRequest
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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'course_id' => 'required|exists:courses,id',
            'section_id' => 'required|exists:course_sections,id',
            'lecture_title' => 'required|string|max:255',
            'type' => 'required|in:video,document,text,r2_video',
            'url' => 'nullable|url|max:255',
            'video_duration' => 'nullable',
            'content' => 'nullable|string',

            // document R2
            'r2_document_key' => $isUpdate
                ? 'nullable|string|max:255'
                : 'required_if:type,document|nullable|string|max:255',

            'file_name' => 'nullable|string|max:255',
            'mime_type' => 'nullable|string|max:255',
            'file_size' => 'nullable|integer|min:1',
            'storage_disk' => 'nullable|string|max:255',
            'r2_video_key' => $isUpdate ? 'nullable|string' : 'required_if:type,r2_video|nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Vui lòng chọn khóa học',
            'course_id.exists' => 'Khóa học không tồn tại',
            'section_id.required' => 'Vui lòng chọn chương học',
            'section_id.exists' => 'Chương học không tồn tại',
            'lecture_title.required' => 'Vui lòng nhập tiêu đề bài học',
            'lecture_title.string' => 'Tiêu đề bài học phải là chuỗi',
            'lecture_title.max' => 'Tiêu đề bài học không được vượt quá 255 ký tự',
            'type.required' => 'Vui lòng chọn loại bài học',
            'type.in' => 'Loại bài học không hợp lệ',
            // 'document_file.file' => 'Tài liệu phải là một file',
            'document_file.mimes' => 'Tài liệu phải có định dạng pdf, doc, docx, txt',
            'document_file.max' => 'Tài liệu không được vượt quá 10MB',
            'url.url' => 'URL không hợp lệ',
            'url.max' => 'URL không được vượt quá 255 ký tự',
            'content.string' => 'Nội dung bài học phải là chuỗi',
        ];
    }
}
