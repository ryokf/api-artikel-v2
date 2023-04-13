<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\viewers;
use App\Models\Bookmark;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\UserInterest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Article::factory(20)->create();
        Bookmark::factory(20)->create();
        Comment::factory(50)->create();
        User::factory(10)->create();
        UserInterest::factory(20)->create();
        // viewers::factory(20)->create();

        User::create([
            'username' => 'ryokf',
            'first_name' => 'ryo',
            'last_name' => 'khrisna',
            'email' => 'ryokhrisnaf@gmail.com',
            'email_verified_at' => now(),
            'phone' => null,
            'address' => null,
            'photo' => 'https://source.unsplash.com/random/' . rand(5,10) * 100 . 'x' .  rand(5,10) * 100,
            'password' => Hash::make('rahasia'), // password
            'remember_token' => Str::random(10),
        ]);

        Category::create([
            'name' => 'politik',
            'photo' => 'https://source.unsplash.com/random/?politic'
        ]);
        Category::create([
            'name' => 'otomotif',
            'photo' => 'https://source.unsplash.com/random/?automotive'
        ]);
        Category::create([
            'name' => 'hiburan',
            'photo' => 'https://source.unsplash.com/random/?entertainment'
        ]);
        Category::create([
            'name' => 'keuangan',
            'photo' => 'https://source.unsplash.com/random/?money'
        ]);
        Category::create([
            'name' => 'teknologi',
            'photo' => 'https://source.unsplash.com/random/?tech'
        ]);
        Category::create([
            'name' => 'olahraga',
            'photo' => 'https://source.unsplash.com/random/?sport'
        ]);
        Category::create([
            'name' => 'kuliner',
            'photo' => 'https://source.unsplash.com/random/?food'
        ]);
        Category::create([
            'name' => 'kesehatan',
            'photo' => 'https://source.unsplash.com/random/?healthy'
        ]);
        Category::create([
            'name' => 'pendidikan',
            'photo' => 'https://source.unsplash.com/random/?education'
        ]);
        Category::create([
            'name' => 'wisata',
            'photo' => 'https://source.unsplash.com/random/?travel'
        ]);
    }
}
