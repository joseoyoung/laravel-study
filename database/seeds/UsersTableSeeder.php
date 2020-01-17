<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $adminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $permissions = config('roles.models.permission')::all();

        /*
         * Add Users
         *
         */
        if (config('roles.models.defaultUser')::where('email', '=', 'admin@admin.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'     => 'Admin',
                'email'    => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => str_random(10),
            ]);

            $newUser->attachRole($adminRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }


        // if (config('roles.models.defaultUser')::where('email', '=', 'user@user.com')->first() === null) {
        //     $newUser = config('roles.models.defaultUser')::create([
        //         'name'     => 'User',
        //         'email'    => 'user@user.com',
        //         'email_verified_at' => now(),
        //         'password' => bcrypt('password'),
        //         'remember_token' => str_random(10),
        //     ]);

        //     $newUser;
        //     $newUser->attachRole($userRole);
        // }


        $users = factory(App\User::class, 9)->create();
        foreach($users as $user){
            $user->attachRole($userRole);
        }
        // $users = DB::table('users')->where('name', '<>', 'Admin')->get();

        // $users = DB::table('users')->where('name', '<>', 'Admin')->get();
        //dd($users);
        // echo "<pre>";
        // print_r($users);

        // echo "<br/>";
        // echo "<br/>";
        // echo "<br/>";
        // echo "dddddddddddddddddddddddddddddddddddddd<br/>";
        // echo "<br/>";
        // echo "<br/>";
        // foreach($users as $user){
            // print_r($user->email);
            
            // $newUser = config('roles.models.defaultUser')::create([
            //     'name'     => $user->name,
            //     'email'    => $user->email,
            //     'email_verified_at' => now(),
            //     'password' => bcrypt('password'),
            //     'remember_token' => str_random(10),
            // ]);
            // $newUser;
            // $newUser->attachRole($userRole);
        // }
    }
}
