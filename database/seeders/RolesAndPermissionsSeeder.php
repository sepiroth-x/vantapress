<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Module Management
            'view modules',
            'install modules',
            'enable modules',
            'disable modules',
            'uninstall modules',
            
            // Theme Management
            'view themes',
            'install themes',
            'activate themes',
            'uninstall themes',
            'customize themes',
            
            // Menu Management
            'view menus',
            'create menus',
            'edit menus',
            'delete menus',
            
            // Settings Management
            'view settings',
            'edit settings',
            
            // Student Management
            'view students',
            'create students',
            'edit students',
            'delete students',
            'view student grades',
            
            // Teacher Management
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',
            'manage teacher rates',
            
            // Department Management
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            
            // Subject Management
            'view subjects',
            'create subjects',
            'edit subjects',
            'delete subjects',
            
            // Enrollment Management
            'view enrollments',
            'create enrollments',
            'edit enrollments',
            'delete enrollments',
            'approve enrollments',
            
            // Grade Management
            'view grades',
            'input grades',
            'edit grades',
            'delete grades',
            'view grade history',
            
            // Schedule Management
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            'detect conflicts',
            
            // Report Access
            'view reports',
            'generate reports',
            'export reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin Role
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin Role
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view modules',
            'view themes',
            'view menus',
            'create menus',
            'edit menus',
            'delete menus',
            'view settings',
            'edit settings',
            'view students',
            'create students',
            'edit students',
            'view teachers',
            'create teachers',
            'edit teachers',
            'view departments',
            'create departments',
            'edit departments',
            'view subjects',
            'create subjects',
            'edit subjects',
            'view enrollments',
            'create enrollments',
            'edit enrollments',
            'approve enrollments',
            'view grades',
            'view grade history',
            'view schedules',
            'create schedules',
            'edit schedules',
            'detect conflicts',
            'view reports',
            'generate reports',
            'export reports',
        ]);

        // Teacher Role
        $teacher = Role::create(['name' => 'teacher']);
        $teacher->givePermissionTo([
            'view students',
            'view subjects',
            'view enrollments',
            'view grades',
            'input grades',
            'edit grades',
            'view grade history',
            'view schedules',
            'view reports',
        ]);

        // Student Role
        $student = Role::create(['name' => 'student']);
        $student->givePermissionTo([
            'view student grades',
            'view enrollments',
            'view schedules',
        ]);

        // Registrar Role
        $registrar = Role::create(['name' => 'registrar']);
        $registrar->givePermissionTo([
            'view students',
            'create students',
            'edit students',
            'view enrollments',
            'create enrollments',
            'edit enrollments',
            'approve enrollments',
            'view grades',
            'view grade history',
            'view reports',
            'generate reports',
            'export reports',
        ]);

        // Department Head Role
        $departmentHead = Role::create(['name' => 'department-head']);
        $departmentHead->givePermissionTo([
            'view students',
            'view teachers',
            'view subjects',
            'view enrollments',
            'view grades',
            'view grade history',
            'view schedules',
            'view reports',
            'generate reports',
        ]);
    }
}
