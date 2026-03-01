<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lập trình & Công nghệ',
                'subcategories' => [
                    'Lập trình Web',
                    'Lập trình Mobile',
                    'Khoa học dữ liệu',
                    'Trí tuệ nhân tạo',
                    'Bảo mật mạng',
                    'Điện toán đám mây',
                    'DevOps',
                    'Lập trình Game',
                ],
            ],
            [
                'name' => 'Kinh doanh',
                'subcategories' => [
                    'Khởi nghiệp',
                    'Quản trị doanh nghiệp',
                    'Tài chính & Kế toán',
                    'Marketing',
                    'Bán hàng',
                    'Quản lý dự án',
                ],
            ],
            [
                'name' => 'Thiết kế',
                'subcategories' => [
                    'Thiết kế đồ họa',
                    'Thiết kế UI/UX',
                    'Thiết kế 3D',
                    'Chỉnh sửa ảnh',
                    'Thiết kế web',
                ],
            ],
            [
                'name' => 'Marketing',
                'subcategories' => [
                    'Digital Marketing',
                    'SEO',
                    'Mạng xã hội',
                    'Email Marketing',
                    'Quảng cáo Google Ads',
                ],
            ],
            [
                'name' => 'Phát triển bản thân',
                'subcategories' => [
                    'Kỹ năng lãnh đạo',
                    'Quản lý thời gian',
                    'Kỹ năng giao tiếp',
                    'Tư duy sáng tạo',
                    'Sức khỏe & Thiền định',
                ],
            ],
            [
                'name' => 'Ngoại ngữ',
                'subcategories' => [
                    'Tiếng Anh',
                    'Tiếng Nhật',
                    'Tiếng Hàn',
                    'Tiếng Trung',
                    'Tiếng Pháp',
                ],
            ],
            [
                'name' => 'Âm nhạc',
                'subcategories' => [
                    'Guitar',
                    'Piano',
                    'Sản xuất âm nhạc',
                    'Hát',
                    'DJ & Remix',
                ],
            ],
            [
                'name' => 'Nhiếp ảnh & Quay phim',
                'subcategories' => [
                    'Chụp ảnh cơ bản',
                    'Chụp ảnh chân dung',
                    'Quay phim',
                    'Chỉnh sửa video',
                    'Drone',
                ],
            ],
        ];

        foreach ($categories as $cat) {
            $categoryId = DB::table('categories')->insertGetId([
                'name'       => $cat['name'],
                'slug'       => Str::slug($cat['name']),
                'image'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cat['subcategories'] as $sub) {
                DB::table('sub_categories')->insert([
                    'category_id' => $categoryId,
                    'name'        => $sub,
                    'slug'        => Str::slug($sub),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
