<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Dọn dẹp dữ liệu mồ côi để tránh lỗi Frontend
        Wishlist::truncate();
        Cart::truncate();

        // 2. Lấy instructor, category, subcategory
        $instructor = User::where('role', 'instructor')->first();
        if (!$instructor) {
            $instructor = User::where('role', 'admin')->first();
        }

        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->error('Vui lòng chạy CategorySeeder trước!');
            return;
        }

        // 3. Tạo 10 khóa học mẫu
        for ($i = 1; $i <= 10; $i++) {
            $category = $categories->random();
            $subcategory = SubCategory::where('category_id', $category->id)->first()
                ?? SubCategory::first();

            $name = "Khóa học lập trình mẫu số " . $i;

            Course::create([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory ? $subcategory->id : 1,
                'instructor_id' => $instructor->id,
                'course_title' => $name,
                'course_name' => $name,
                'course_name_slug' => Str::slug($name),
                'description' => 'Đây là mô tả mẫu cho khóa học số ' . $i . '. Khóa học này cung cấp các kiến thức nền tảng và nâng cao về lập trình.',
                'course_image' => 'https://picsum.photos/seed/course' . $i . '/800/600',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => collect(['Bestseller', 'New', 'Featured', 'Highest Rated'])->random(),
                'duration' => rand(5, 50) . ' giờ',
                'resources' => rand(1, 10) . ' tài liệu',
                'certificate' => 'Có',
                'selling_price' => rand(200000, 1000000),
                'discount_price' => rand(100000, 500000),
                'prerequisites' => 'Kiến thức cơ bản về máy tính',
                'bestseller' => rand(0, 1) ? 'yes' : 'no',
                'featured' => rand(0, 1) ? 'yes' : 'no',
                'highestrated' => rand(0, 1) ? 'yes' : 'no',
                'status' => 1,
                'approval_status' => 'published',
                'course_goals' => [
                    'Nắm vững kiến thức cơ bản',
                    'Thực hành dự án thực tế',
                    'Tự tin ứng tuyển công việc'
                ]
            ]);
        }
    }
}
