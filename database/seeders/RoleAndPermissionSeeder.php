<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $extraPermissions = [
            'Super Admin' => [
                'customer' => ['send-email', 'change-password'],
                'cache' => ['reset'],
            ],
        ];

        $roles_structure = [
            'Super Admin' => [
                'dashboard' => 'r',

                // Business
                'plan' => 'c,r,u,d',

                'customer' => 'c,r,u,d',
                'store' => 'c,r,u,d',
                'support-ticket' => 'r,u,d',

                'user' => 'c,r,u,d',

                // Business Setup
                'gateway' => 'c,r,u,d',
                'tax' => 'c,r,u,d',
                'coupon' => 'c,r,u,d',
                'currency' => 'c,r,u',

                // Frontend
                'blog' => 'c,r,u,d',
                'blog-category' => 'c,r,u,d',
                'page' => 'c,r,u,d',

                //Customization Setup
                'media-library' => 'c,r,u,d',

                // Settings
                'role' => 'c,r,u,d',
                'staff' => 'c,r,u,d',
                'language' => 'c,r,u,d',
                'email-setting' => 'r,u',
                'email-template' => 'r,u',
                'backup' => 'c,r,u,d',
                'oauth' => 'r,u',
                'cron-job' => 'r',
                'general-setting' => 'r,u',
            ],
        ];

        $mapPermission = collect([
            'l' => 'list',
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
        ]);

        foreach ($roles_structure as $key => $modules) {
            // Create a new role
            $role = Role::firstOrCreate([
                'name' => $key,
                'guard_name' => 'web',
                'is_system' => $key == 'Super Admin',
            ]);
            $permissions = [];

            $this->command->info('Creating Role '.strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                foreach (explode(',', $value) as $perm) {
                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = Permission::firstOrCreate([
                        'name' => $module.'-'.$permissionValue,
                        'module' => $module,
                        'guard_name' => 'web',
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '.$module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            $this->command->info("Creating '$key' user");

            $user = User::updateOrCreate([
                'email' => str($key)->remove(' ')->lower()->value().'@mail.com',
            ], [
                'username' => $this->makeUsername($key),
                'first_name' => $key,
                'last_name' => 'Demo',
                'email' => str($key)->remove(' ')->lower()->value().'@mail.com',
                'password' => bcrypt('password'),
                'group' => $key == 'User' ? 'customer' : 'admin',
                'email_verified_at' => now(),
            ]);
            $user->assignRole($role);
        }

        // Extra Permissions
        foreach ($extraPermissions as $role => $modules) {
            $role = Role::where('name', $role)->first();

            foreach ($modules as $module => $permissions) {
                foreach ($permissions as $permission) {
                    $permission = Permission::firstOrCreate([
                        'name' => $module.'-'.$permission,
                        'module' => $module,
                        'guard_name' => 'web',
                    ]);

                    $this->command->info('Creating Permission to '.$permission->name.' for '.$module);

                    $role?->givePermissionTo($permission);
                }
            }
        }

        // Create a demo User
        $this->command->info("Creating 'User' user");

        User::updateOrCreate([
            'email' => 'user@mail.com',
        ],
        [
            'first_name' => 'Demo',
            'last_name' => 'Customer',
            'username' => 'demo-customer',
            'group' => 'customer',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'phone' => '+8801700000000',
            'company' => 'Demo Company',
            'position' => 'Demo Position',
            'address' => 'Rangpur, Bangladesh',
            'state' => 'Rangpur',
            'city' => 'Rangpur',
            'country' => 'Bangladesh',
            'postal_code' => '5400',
        ]);
    }

    private function makeUsername($name): string
    {
        $username = str($name)->remove(' ')->lower()->value();
        $user = User::where('username', $username)->first();
        if ($user) {
            $username = $username.rand(1000, 9999);
        }

        return $username;
    }
}
