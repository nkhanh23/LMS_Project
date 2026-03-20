<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteInfo;

class SiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteInfo::updateOrCreate(
            ['id' => 1],
            [
                'meta_title' => 'StackLearn',
                'meta_description' => 'The ultimate 16-bit learning platform for modern developers. Build, learn, level up.',
                'copyright' => 'StackLearn. All rights reserved.',
                'address' => 'Ho Chi Minh City, Vietnam',
                'phone' => '+84 123 456 789',
                'mail' => 'contact@stacklearn.dev',
                'facebook' => 'https://facebook.com/stacklearn',
                'twitter' => 'https://twitter.com/stacklearn',
                'instagram' => 'https://instagram.com/stacklearn',
                'linkedin' => 'https://linkedin.com/company/stacklearn',
            ]
        );
    }
}
