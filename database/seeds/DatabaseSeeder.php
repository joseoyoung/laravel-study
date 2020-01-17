<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker::create();
        Model::unguard();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // factory(App\User::class)->create([
        //     'name' => 'John Doe',
        //     'email' => 'john@example.com',
        //     'password' => bcrypt('password')
        // ]);
        // factory(App\User::class, 10)->create();
        // $this->command->info('users table seeded');

        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ConnectRelationshipsSeeder::class);
        $this->call(UsersTableSeeder::class);

        $this->command->info('table seeded');


        /**
         * Seeding roles table
        */
        // Bican\Roles\Models\Role::truncate();
        // DB::table('role_user')->truncate();
        // $adminRole = Bican\Roles\Models\Role::create([
        //     'name' => 'Admin',
        //     'slug' => 'admin'
        // ]);
        // $memberRole = Bican\Roles\Models\Role::create([
        //     'name' => 'Member',
        //     'slug' => 'member'
        // ]);

        // App\User::where('email', '!=', 'john@example.com')->get()->map(function($user) use($memberRole) {
        //     $user->attachRole($memberRole);
        // });

        // App\User::whereEmail('john@example.com')->get()->map(function($user) use($adminRole){
        //     $user->attachRole($adminRole);
        // });
        // $this->command->info('roles table seeded');
        //UsersTableSeeder에서 처리함


           /*
         * Seeding articles table
         */
        // App\Article::truncate();
        $users = App\User::all();

        $users->each(function($user) use($faker) {
            $user->articles()->save(
                factory(App\Article::class)->make()
            );
        });
        $this->command->info('articles table seeded');
        


        $articles = App\Article::all();

        $articles->each(function($article) use($faker, $users) {
            // $article->comments()->save(
            //     factory(App\Comment::class)->make([
            //         'author_id' => $faker->randomElement($users->pluck('id')->toArray())
            //     ])
            // );
            
            $article->comments()->save(
                factory(App\Comment::class)->make()
            );

        });
            
        $this->command->info('comments table seeded');
         /**
         * Seeding comments table
         */
        // // App\Comment::truncate();
        // $articles = App\Article::all();

        // $articles->each(function($article) use($faker, $users) {
        //     $article->comments()->save(
        //         // factory(App\Comment::class)->make()
        //         factory(App\Comment::class)->make([
        //             'author_id' => $faker->randomElement($users->pluck('id')->toArray())
        //         ])
        //     );
        // });
        // $this->command->info('comments table seeded');


         /*
         * Seeding tags table
         */
        // App\Tag::truncate();
        DB::table('article_tag')->truncate();
        $articles->each(function($article) use($faker) {
            $article->tags()->save(
                factory(App\Tag::class)->make()
            );
        });
        $this->command->info('tags table seeded');



          /*
         * Seeding attachments table
         */
        // App\Attachment::truncate();
        if (! File::isDirectory(attachment_path())) {
            File::deleteDirectory(attachment_path(), true);
        }

        $articles->each(function($article) use($faker) {
            $article->attachments()->save(
                factory(App\Attachment::class)->make()
            );
        });

        $files = App\Attachment::pluck('name');

        if (! File::isDirectory(attachment_path())) {
            File::makeDirectory(attachment_path(), 777, true);
        }

        foreach($files as $file) {
            File::put(attachment_path($file), '');
        }

        $this->command->info('attachments table seeded');


        Model::reguard();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
