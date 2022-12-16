<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin'        
        ],[
            'name' => 'consultant'
        ]);
        DB::table('roles')->insert([
            'name' => 'consultant'
        ]);
        DB::table('users')->insert([
            'name' => 'User Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'User Consultant',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $admin_role_id = \App\Models\Role::where('name', 'admin')->first();
        $consultant_role_id = \App\Models\Role::where('name', 'consultant')->first();
        $admin_id = \App\Models\User::where('email', 'admin@example.com')->first();
        $consultant_id = \App\Models\User::where('email', 'user@example.com')->first();
        

        DB::table('user_roles')->insert([
            'user_id' => $admin_id->id,       
            'role_id' => $admin_role_id->id,       
        ],[
            'user_id' => $consultant_id->id,       
            'role_id' => $consultant_role_id->id, 
        ]);
        
    }
}
