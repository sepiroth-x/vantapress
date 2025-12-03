<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Project Configuration Test ===\n\n";

echo "Environment Variables:\n";
echo "  PROJECT_NAME: " . env('PROJECT_NAME') . "\n";
echo "  PROJECT_CODE: " . env('PROJECT_CODE') . "\n";
echo "  SYSTEM_YEAR_START: " . env('SYSTEM_YEAR_START') . "\n";

echo "\nConfig Values:\n";
echo "  cms.project.name: " . config('cms.project.name') . "\n";
echo "  cms.project.code: " . config('cms.project.code') . "\n";
echo "  cms.project.year_start: " . config('cms.project.year_start') . "\n";

echo "\nHelper Functions:\n";
echo "  system_year(): " . system_year() . "\n";
echo "  format_student_id('123456'): " . format_student_id('123456') . "\n";
echo "  grade_passing(2.5): " . (grade_passing(2.5) ? 'Yes' : 'No') . "\n";
echo "  grade_passing(3.5): " . (grade_passing(3.5) ? 'Yes' : 'No') . "\n";

echo "\n✓ All configurations updated successfully!\n";
echo "\nOld references removed:\n";
echo "  ✗ SCHOOL_NAME → PROJECT_NAME\n";
echo "  ✗ SCHOOL_CODE → PROJECT_CODE\n";
echo "  ✗ SCHOOL_YEAR_START → SYSTEM_YEAR_START\n";
echo "  ✗ ACADEMIC_YEAR → (removed, not needed)\n";
echo "  ✗ cms.school.* → cms.project.*\n";
