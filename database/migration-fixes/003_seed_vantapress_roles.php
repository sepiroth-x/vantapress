<?php

use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

/**
 * Migration Fix 003: Seed VantaPress Roles
 * 
 * Ensures all default VantaPress roles exist in the database.
 * This runs automatically as part of the migration fix system.
 * 
 * Required Roles:
 * - super-admin (full system access)
 * - admin (administrative access)
 * - teacher (course management)
 * - student (learning access)
 * - registrar (enrollment management)
 * - department-head (department oversight)
 * 
 * @version 1.1.2-complete
 * @since 2025-12-08
 */

return new class
{
    /**
     * Default VantaPress roles
     */
    private array $roles = [
        'super-admin' => 'Full system access with all permissions',
        'admin' => 'Administrative access to manage content and users',
        'teacher' => 'Course management and student interaction',
        'student' => 'Learning access to courses and content',
        'registrar' => 'Enrollment and academic records management',
        'department-head' => 'Department oversight and reporting'
    ];

    /**
     * Determine if this fix should run
     */
    public function shouldRun(): bool
    {
        Log::warning('[Migration Fix 003] ========================================');
        Log::warning('[Migration Fix 003] Checking if roles need to be seeded');
        Log::warning('[Migration Fix 003] ========================================');

        // Check if any roles are missing
        $missingRoles = [];
        foreach (array_keys($this->roles) as $roleName) {
            $exists = Role::where('name', $roleName)->exists();
            Log::warning("[Migration Fix 003] Role check: '{$roleName}' exists=" . ($exists ? 'YES' : 'NO'));
            
            if (!$exists) {
                $missingRoles[] = $roleName;
            }
        }

        if (count($missingRoles) > 0) {
            Log::warning('[Migration Fix 003] ✓✓✓ DECISION: WILL RUN - Missing roles: ' . implode(', ', $missingRoles));
            return true;
        }

        Log::warning('[Migration Fix 003] ✓✓✓ DECISION: SKIP - All roles already exist');
        return false;
    }

    /**
     * Execute the fix
     */
    public function execute(): array
    {
        Log::warning('[Migration Fix 003] Starting execution - Seed VantaPress roles');
        
        $created = [];
        $skipped = [];

        try {
            foreach ($this->roles as $roleName => $description) {
                // Check if role already exists
                $role = Role::where('name', $roleName)->first();

                if (!$role) {
                    // Create new role
                    Role::create([
                        'name' => $roleName,
                        'guard_name' => 'web'
                    ]);
                    
                    $created[] = $roleName;
                    Log::warning("[Migration Fix 003] ✓ Created role: {$roleName}");
                } else {
                    $skipped[] = $roleName;
                    Log::warning("[Migration Fix 003] ⚠️ Skipped existing role: {$roleName}");
                }
            }

            $message = sprintf(
                'Roles seeded successfully. Created: %d, Skipped: %d',
                count($created),
                count($skipped)
            );

            Log::warning('[Migration Fix 003] ✓✓✓ SUCCESS: ' . $message);

            return [
                'executed' => true,
                'message' => $message,
                'created' => $created,
                'skipped' => $skipped
            ];

        } catch (\Exception $e) {
            $error = 'Failed to seed roles: ' . $e->getMessage();
            Log::error('[Migration Fix 003] ✗✗✗ ERROR: ' . $error);
            Log::error('[Migration Fix 003] Stack trace: ' . $e->getTraceAsString());

            return [
                'executed' => false,
                'message' => $error,
                'error' => $e->getMessage()
            ];
        }
    }
};
