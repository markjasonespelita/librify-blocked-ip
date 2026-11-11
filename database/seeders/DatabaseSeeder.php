<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Mark Jason Espelita',
            'email' => 'markjasonespelita@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $permissions = [
            // roles
            'can add permission to role',
            'can create role',
            'can edit role',
            'can delete role',
            'can view role',

            // users

            'can view users',
            'can show users',
            'can add users',
            'can edit users',
            'can delete users',

            // another permissions
                                                
            // statusreports

            'can view statusreports',
            'can show statusreports',
            'can add statusreports',
            'can edit statusreports',
            'can delete statusreports',

            // another permissions
                                            
                                                
            // sites

            'can view sites',
            'can show sites',
            'can add sites',
            'can edit sites',
            'can delete sites',

            // another permissions
                                            
                                                
            // statuses

            'can view statuses',
            'can show statuses',
            'can add statuses',
            'can edit statuses',
            'can delete statuses',

            // another permissions
                                            
                                                
            // olts

            'can view olts',
            'can show olts',
            'can add olts',
            'can edit olts',
            'can delete olts',

            // another permissions
                                            
                                                
            // municipalities

            'can view municipalities',
            'can show municipalities',
            'can add municipalities',
            'can edit municipalities',
            'can delete municipalities',

            // another permissions
                                            

            // logs

            'can view logs',

            // any

            'can view database',
            'can view postman',
            'can view dashboard',
            'can view messenger notification',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // Assign roles to users
        User::find(1)->assignRole('admin');
        User::find(2)->assignRole('admin');
        User::find(3)->assignRole('user');

    }
}
