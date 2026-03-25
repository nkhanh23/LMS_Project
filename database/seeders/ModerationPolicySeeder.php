<?php

namespace Database\Seeders;

use App\Models\ModerationPolicy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModerationPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = [
            ['code' => 'spam', 'name' => 'Spam content', 'target_type' => 'course'],
            ['code' => 'abuse', 'name' => 'Abusive content', 'target_type' => 'course'],
            ['code' => 'plagiarism', 'name' => 'Plagiarism / copied content', 'target_type' => 'course'],
            ['code' => 'misleading', 'name' => 'Misleading course information', 'target_type' => 'course'],
            ['code' => 'other_violation', 'name' => 'Other policy violation', 'target_type' => 'course'],
        ];

        foreach ($policies as $policy) {
            ModerationPolicy::updateOrCreate(
                ['code' => $policy['code']],
                $policy
            );
        }
    }
}
