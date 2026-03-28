<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LectureRequest;
use App\Models\CourseLecture;
use App\Models\Order;
use App\Services\LectureService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    use FileUploadTrait;

    protected $lectureService;

    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LectureRequest $request)
    {
        $this->lectureService->createLecture($request->validated());
        return back()->with('success', 'Bài học đã được thêm thành công');
    }

    //
    public function generatePresignedUrl(Request $request)
    {
        $extension = $request->input('extension', 'mp4');

        // Gọi Service xử lý logic tạo URL
        $data = $this->lectureService->generatePresignedUrl($extension);

        return response()->json($data);
    }

    public function generateDocumentPresignedUrl(Request $request)
    {
        // Nhận extension và mime_type từ JavaScript gửi lên
        $request->validate([
            'extension' => 'required|string|max:10',
            'mime_type' => 'required|string|max:255',
        ]);

        $data = $this->lectureService->generateDocumentPresignedUrl(
            $request->input('extension'),
            $request->input('mime_type')
        );

        return response()->json($data); // Trả về link upload cho Frontend
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LectureRequest $request, string $id)
    {
        $this->lectureService->updateLecture($request->validated(), $id);
        return back()->with('success', 'Bài học đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->lectureService->deleteLecture($id);
        return redirect()->back()->with('success', 'Bài học đã được xóa thành công');
    }

    public function downloadDocument(CourseLecture $lecture)
    {
        // Kiểm tra bài học có phải là loại 'document' không
        abort_if($lecture->type !== 'document', 404);
        // Kiểm tra bài học có URL không
        abort_if(empty($lecture->url), 404);
        // Kiểm tra người dùng đã đăng nhập chưa
        abort_unless(Auth::check(), 403);

        // Kiểm tra người dùng đã mua khóa học chưa
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('course_id', $lecture->course_id)
            ->exists();

        abort_unless($hasPurchased, 403);

        // Kiểm tra xem file được lưu trên R2 hay không
        if ($lecture->storage_disk === 'r2') {
            // Tạo URL tải xuống tạm thời
            $downloadUrl = $this->lectureService->generateDocumentDownloadUrl(
                $lecture->url,
                $lecture->file_name
            );
            // Chuyển hướng người dùng đến URL tải xuống
            return redirect()->away($downloadUrl);
        }

        // Nếu không phải R2 thì trả về lỗi 404
        abort(404);
    }
}
