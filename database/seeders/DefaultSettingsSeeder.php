<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'Talisay City College',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'Excellence in Education',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'admin_email',
                'value' => 'admin@tcc.edu.ph',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'timezone',
                'value' => 'Asia/Manila',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'time_format',
                'value' => 'H:i:s',
                'type' => 'string',
            ],

            // Project Settings
            [
                'group' => 'project',
                'key' => 'project_name',
                'value' => env('PROJECT_NAME', 'VantaPress'),
                'type' => 'string',
            ],
            [
                'group' => 'project',
                'key' => 'project_code',
                'value' => env('PROJECT_CODE', 'VP'),
                'type' => 'string',
            ],
            [
                'group' => 'project',
                'key' => 'system_year_start',
                'value' => env('SYSTEM_YEAR_START', '2024'),
                'type' => 'string',
            ],
            [
                'group' => 'school',
                'key' => 'current_semester',
                'value' => 'First',
                'type' => 'string',
            ],
            [
                'group' => 'school',
                'key' => 'school_address',
                'value' => 'Talisay City, Cebu, Philippines',
                'type' => 'string',
            ],
            [
                'group' => 'school',
                'key' => 'school_phone',
                'value' => '+63 32 XXX XXXX',
                'type' => 'string',
            ],
            [
                'group' => 'school',
                'key' => 'school_email',
                'value' => 'info@tcc.edu.ph',
                'type' => 'string',
            ],

            // Grading Settings
            [
                'group' => 'grading',
                'key' => 'grading_scale_min',
                'value' => '1.0',
                'type' => 'float',
            ],
            [
                'group' => 'grading',
                'key' => 'grading_scale_max',
                'value' => '5.0',
                'type' => 'float',
            ],
            [
                'group' => 'grading',
                'key' => 'passing_grade',
                'value' => '3.0',
                'type' => 'float',
            ],
            [
                'group' => 'grading',
                'key' => 'grade_computation',
                'value' => json_encode([
                    'prelim' => 0.25,
                    'midterm' => 0.25,
                    'semifinal' => 0.00,
                    'finals' => 0.50,
                ]),
                'type' => 'json',
            ],
            [
                'group' => 'grading',
                'key' => 'enable_grade_history',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'grading',
                'key' => 'require_grade_remarks',
                'value' => '1',
                'type' => 'boolean',
            ],

            // Enrollment Settings
            [
                'group' => 'enrollment',
                'key' => 'enrollment_open',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'enrollment',
                'key' => 'enrollment_start_date',
                'value' => '2024-06-01',
                'type' => 'string',
            ],
            [
                'group' => 'enrollment',
                'key' => 'enrollment_end_date',
                'value' => '2024-07-31',
                'type' => 'string',
            ],
            [
                'group' => 'enrollment',
                'key' => 'require_approval',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'enrollment',
                'key' => 'max_units_per_semester',
                'value' => '24',
                'type' => 'integer',
            ],
            [
                'group' => 'enrollment',
                'key' => 'min_units_per_semester',
                'value' => '15',
                'type' => 'integer',
            ],

            // Appearance Settings
            [
                'group' => 'appearance',
                'key' => 'theme_primary_color',
                'value' => '#5D4037',
                'type' => 'string',
            ],
            [
                'group' => 'appearance',
                'key' => 'theme_secondary_color',
                'value' => '#8B4513',
                'type' => 'string',
            ],
            [
                'group' => 'appearance',
                'key' => 'theme_accent_color',
                'value' => '#D4A574',
                'type' => 'string',
            ],
            [
                'group' => 'appearance',
                'key' => 'show_breadcrumbs',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'appearance',
                'key' => 'items_per_page',
                'value' => '20',
                'type' => 'integer',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
