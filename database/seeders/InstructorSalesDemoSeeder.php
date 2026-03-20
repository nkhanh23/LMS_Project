<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstructorSalesDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. Lấy instructor để test
            |--------------------------------------------------------------------------
            | Bạn có thể sửa email này thành đúng instructor đang đăng nhập
            */
            $instructor = User::where('email', 'instructor@example.com')->first();

            if (!$instructor) {
                $this->command->error('Không tìm thấy instructor với email instructor@example.com');
                $this->command->info('Hãy sửa email trong seeder cho đúng tài khoản instructor của bạn.');
                DB::rollBack();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Tạo vài student test
            |--------------------------------------------------------------------------
            */
            $students = [];

            for ($i = 1; $i <= 5; $i++) {
                $student = User::firstOrCreate(
                    ['email' => "student{$i}@example.com"],
                    [
                        'name' => "Student {$i}",
                        'password' => bcrypt('12345678'),
                        'role' => 'user',
                    ]
                );

                $students[] = $student;
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Lấy hoặc tạo course của instructor
            |--------------------------------------------------------------------------
            */
            $courses = Course::where('instructor_id', $instructor->id)->take(3)->get();

            if ($courses->count() < 3) {
                $needed = 3 - $courses->count();

                for ($i = 1; $i <= $needed; $i++) {
                    $course = Course::create([
                        'course_name' => 'Demo Course ' . ($courses->count() + $i),
                        'instructor_id' => $instructor->id,
                        'course_title' => 'Demo Course ' . ($courses->count() + $i),
                        'price' => rand(199000, 999000),
                        'discount_price' => null,
                        'image' => 'default.jpg',
                        'label' => 'Bestseller',
                        'duration' => '10h',
                        'resources' => 'Demo resources',
                        'certificate' => 'yes',
                        'selling_price' => rand(199000, 999000),
                        'description' => 'Demo description',
                        'video' => 'demo.mp4',
                        'category_id' => 1,
                        'subcategory_id' => 1,
                        'status' => 1,
                        'approval_status' => 'published',
                    ]);

                    $courses->push($course);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Xóa dữ liệu demo cũ của instructor này (tuỳ chọn)
            |--------------------------------------------------------------------------
            */
            Order::where('instructor_id', $instructor->id)->delete();

            /*
            |--------------------------------------------------------------------------
            | 5. Tạo payments + orders trong 60 ngày gần đây
            |--------------------------------------------------------------------------
            */
            for ($dayOffset = 0; $dayOffset < 60; $dayOffset++) {
                $date = Carbon::now()->subDays($dayOffset);

                // mỗi ngày có thể có 0 đến 3 giao dịch
                $transactionsPerDay = rand(0, 3);

                for ($t = 1; $t <= $transactionsPerDay; $t++) {
                    $student = $students[array_rand($students)];

                    // chọn ngẫu nhiên 1-2 course
                    $selectedCourses = $courses->shuffle()->take(rand(1, min(2, $courses->count())));

                    $totalAmount = 0;
                    $coursePrices = [];

                    foreach ($selectedCourses as $course) {
                        $price = $course->selling_price ?? $course->price ?? rand(199000, 999000);
                        $coursePrices[$course->id] = $price;
                        $totalAmount += $price;
                    }

                    $payment = Payment::create([
                        'transaction_id'   => 'pi_demo_' . Str::lower(Str::random(14)),
                        'name'         => $student->name,
                        'email'        => $student->email,
                        'invoice_no' => 'INV-' . strtoupper(Str::random(10)),
                        'payment_type' => collect(['stripe', 'card', 'paypal'])->random(),
                        'total_amount' => $totalAmount,
                        'status' => 'completed',
                        'order_date' => $date->format('d F Y'),
                        'order_month' => $date->format('F'),
                        'order_year' => $date->format('Y'),
                        'created_at' => $date->copy()->setTime(rand(8, 21), rand(0, 59)),
                        'updated_at' => $date->copy()->setTime(rand(8, 21), rand(0, 59)),
                    ]);

                    foreach ($selectedCourses as $course) {
                        Order::create([
                            'payment_id' => $payment->id,
                            'user_id' => $student->id,
                            'course_id' => $course->id,
                            'instructor_id' => $instructor->id,
                            'course_title' => $course->course_name ?? 'Demo Course',
                            'price' => $coursePrices[$course->id],
                            'status' => 'completed',
                            'paid_at' => $payment->created_at,
                            'created_at' => $payment->created_at,
                            'updated_at' => $payment->created_at,
                        ]);
                    }
                }
            }

            DB::commit();

            $this->command->info('Đã tạo dữ liệu demo sales cho instructor ID: ' . $instructor->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
