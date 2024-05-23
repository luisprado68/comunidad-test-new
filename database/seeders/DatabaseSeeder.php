<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $administrator = Role::create(['name' => 'administrator']);
        $streamer = Role::create(['name' => 'streamer']);
        $god = Role::create(['name' => 'god']);
        $admin_lider = Role::create(['name' => 'admin_lider']);
        $admin_general = Role::create(['name' => 'admin_general']);
        $admin_plataforma = Role::create(['name' => 'admin_plataforma']);

        $users_manage = Permission::create(['name' => 'users-manage']);
        $administrator->givePermissionTo($users_manage);
        $admin_lider->givePermissionTo($users_manage);
        $admin_general->givePermissionTo($users_manage);

        $teams_manage = Permission::create(['name' => 'teams-manage']);
        $administrator->givePermissionTo($teams_manage);
        $admin_lider->givePermissionTo($teams_manage);
        $admin_general->givePermissionTo($teams_manage);

        $user_show = Permission::create(['name' => 'users-show']);
        $administrator->givePermissionTo($user_show);
        $admin_lider->givePermissionTo($user_show);
        $admin_general->givePermissionTo($user_show);

        $calendar = Permission::create(['name' => 'calendar-show']);
        $administrator->givePermissionTo($calendar);
        $admin_lider->givePermissionTo($calendar);
        $admin_general->givePermissionTo($calendar);
        
        $users_delete = Permission::create(['name' => 'users-delete']);
        $administrator->givePermissionTo($users_delete);
        $admin_lider->givePermissionTo($users_delete);
        // $admin_general->givePermissionTo($users_delete);
        
        $users_edit = Permission::create(['name' => 'users-edit']);
        $administrator->givePermissionTo($users_edit);
        $admin_lider->givePermissionTo($users_edit);
        // $admin_general->givePermissionTo($users_edit);

        $teams_edit = Permission::create(['name' => 'teams-edit']);
        $administrator->givePermissionTo($teams_edit);
        $admin_lider->givePermissionTo($teams_edit);
        // $admin_general->givePermissionTo($teams_edit);

        $teams_delete = Permission::create(['name' => 'teams-delete']);
        $administrator->givePermissionTo($teams_delete);
        $admin_lider->givePermissionTo($teams_delete);
        // $admin_general->givePermissionTo($teams_delete);
        
        $role = Role::findByName('administrator');
        $user = User::where('email','luisprado0095@gmail.com')->first();
        Log::debug("role ----------- " . json_encode($role));
        Log::debug("user --------- " . json_encode($user));
        // $user->assignRole($role);
        $user->assignRole('administrator');



        
    }
}
