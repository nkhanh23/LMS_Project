<?php

namespace Database\Seeders;

use App\Models\ModerationActionTemplate;
use Illuminate\Database\Seeder;

class ModerationActionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'code' => 'dismiss',
                'name' => 'Dismiss Report',
                'target_type' => 'all',
                'default_note' => 'Report has been dismissed as no violation was found.',
                'requires_reason' => false,
            ],
            [
                'code' => 'hide_content',
                'name' => 'Hide Content',
                'target_type' => 'content',
                'default_note' => 'Content has been hidden due to policy violation.',
                'requires_reason' => true,
            ],
            [
                'code' => 'delete_content',
                'name' => 'Delete Content',
                'target_type' => 'content',
                'default_note' => 'Content has been deleted due to severe policy violation.',
                'requires_reason' => true,
            ],
            [
                'code' => 'lock_course',
                'name' => 'Lock Course',
                'target_type' => 'course',
                'default_note' => 'Course has been locked for review.',
                'requires_reason' => true,
            ],
            [
                'code' => 'lock_instructor',
                'name' => 'Suspend Instructor',
                'target_type' => 'instructor',
                'default_note' => 'Instructor account has been suspended due to repeated violations.',
                'requires_reason' => true,
            ],
        ];

        foreach ($templates as $template) {
            ModerationActionTemplate::updateOrCreate(
                ['code' => $template['code']],
                $template
            );
        }
    }
}
