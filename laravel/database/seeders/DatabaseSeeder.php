<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        $directors = ['John Smith', 'Ava Knight', 'Mia Stone', 'Evan Lee', 'Noah Grant', 'Liam Carter', 'Sophie Hall'];
        foreach ($directors as $name) {
            DB::table('directors')->insert(['name' => $name]);
        }

        $studios = ['MovieMall Studios'];
        foreach ($studios as $name) {
            DB::table('studios')->insert(['name' => $name]);
        }

        $languages = ['English', 'Slovak', 'Czech', 'German', 'French'];
        foreach ($languages as $name) {
            DB::table('languages')->insert(['name' => $name]);
        }

        $genres = ['Action', 'Adventure', 'Comedy', 'Drama', 'Documentary', 'Thriller', 'Horror', 'Sci-Fi', 'Fantasy', 'Romance', 'Apocalypse', 'Sports', 'Western'];
        foreach ($genres as $name) {
            DB::table('genres')->insert(['name' => $name]);
        }

        DB::table('halls')->insert([
            ['name' => 'Hall A'],
            ['name' => 'Hall B'],
            ['name' => 'Hall C'],
        ]);

        DB::table('category')->insert([
            ['name' => 'Figurine'],
            ['name' => 'Plush Toy'],
            ['name' => 'Accessory'],
            ['name' => 'Prop Replica'],
            ['name' => 'Sticker Pack'],
            ['name' => 'Print'],
        ]);

        DB::table('souvenir_status')->insert([
            ['name' => 'available'],
            ['name' => 'out_of_stock'],
            ['name' => 'discontinued'],
        ]);

        $directorId = fn(string $name) => DB::table('directors')->where('name', $name)->value('id');
        $studioId = fn(string $name) => DB::table('studios')->where('name', $name)->value('id');
        $languageId = fn(string $name) => DB::table('languages')->where('name', $name)->value('id');
        $genreId = fn(string $name) => DB::table('genres')->where('name', $name)->value('id');
        $categoryId = fn(string $name) => DB::table('category')->where('name', $name)->value('id');
        $statusId = fn(string $name) => DB::table('souvenir_status')->where('name', $name)->value('id');

        $englishId = $languageId('English');
        $studioMmId = $studioId('MovieMall Studios');

        $movies = [
            [
                'title' => 'SuperGrandpa',
                'description' => 'An extraordinary superhero adventure spanning multiple dimensions. SuperGrandpa must save the world from the Mad Squirrels before time runs out.',
                'rating' => 7.5,
                'release_date' => '2026-02-28',
                'length_minutes' => 118,
                'director_id' => $directorId('John Smith'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Adventure', 'Comedy'],
                'image' => '/images/Supergrandpa.png',
            ],
            [
                'title' => 'Gollum: Steal The Ring',
                'description' => 'A desperate anti-hero embarks on a dangerous quest to reclaim a legendary ring and outsmart enemies from every realm.',
                'rating' => 9.5,
                'release_date' => '2028-10-10',
                'length_minutes' => 132,
                'director_id' => $directorId('Ava Knight'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Adventure', 'Action'],
                'image' => '/images/gollum.png',
            ],
            [
                'title' => 'Mission: Possible',
                'description' => 'When every plan fails, one team improvises the impossible to stop a global catastrophe.',
                'rating' => 6.5,
                'release_date' => '2024-06-01',
                'length_minutes' => 109,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Drama'],
                'image' => '/images/missionpossible.png',
            ],
            [
                'title' => 'Hiding Nemo',
                'description' => 'A heartfelt underwater journey about courage, friendship, and finding your way home.',
                'rating' => 4.5,
                'release_date' => '2018-03-15',
                'length_minutes' => 103,
                'director_id' => $directorId('Evan Lee'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Adventure'],
                'image' => '/images/hidingnemo.png',
            ],
            [
                'title' => 'The Ordinary Blue Bulk',
                'description' => 'An unlikely hero rises in a collapsing world, balancing brute force with unexpected compassion.',
                'rating' => 9.8,
                'release_date' => '2025-05-12',
                'length_minutes' => 124,
                'director_id' => $directorId('Noah Grant'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Apocalypse', 'Drama'],
                'image' => '/images/bluebulk.png',
            ],
            [
                'title' => "The Squirrel's Revenge",
                'description' => 'A mischievous squirrel mastermind turns a quiet city into chaos, forcing unlikely heroes to step up.',
                'rating' => 7.5,
                'release_date' => '2020-08-23',
                'length_minutes' => 101,
                'director_id' => $directorId('Liam Carter'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy'],
                'image' => '/images/Squirrel.png',
            ],
            [
                'title' => 'Dr. Normal',
                'description' => 'A brilliant scientist tries to live an ordinary life while confronting the consequences of past experiments.',
                'rating' => 8.2,
                'release_date' => '2026-01-17',
                'length_minutes' => 116,
                'director_id' => $directorId('Sophie Hall'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Documentary'],
                'image' => '/images/drnormal.png',
            ],
        ];

        foreach ($movies as $data) {
            $genres = $data['genres'];
            $image = $data['image'];
            $movieData = array_diff_key($data, array_flip(['genres', 'image']));
            $movieData = array_merge($movieData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $movieId = DB::table('movies')->insertGetId($movieData);

            foreach ($genres as $genreName) {
                DB::table('movie_genres')->insert([
                    'genre_id' => $genreId($genreName),
                    'movie_id' => $movieId,
                ]);
            }

            DB::table('movie_images')->insert([
                'movie_id' => $movieId,
                'url' => $image,
                'is_primary' => true,
                'created_at' => now(),
            ]);
        }

        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $seatsPerRow = 10;

        foreach (['Hall A', 'Hall B', 'Hall C'] as $hallName) {
            $hallId = DB::table('halls')->where('name', $hallName)->value('id');
            foreach ($rows as $row) {
                for ($seat = 1; $seat <= $seatsPerRow; $seat++) {
                    DB::table('seats')->insert([
                        'hall_id' => $hallId,
                        'row_label' => $row,
                        'seat_number' => $seat,
                    ]);
                }
            }
        }

        $hallAId = DB::table('halls')->where('name', 'Hall A')->value('id');
        $supergrandpaId = DB::table('movies')->where('title', 'SuperGrandpa')->value('id');
        $squirrelId = DB::table('movies')->where('title', "The Squirrel's Revenge")->value('id');

        $slots = [
            [
                'movie_id' => $supergrandpaId,
                'hall_id' => $hallAId,
                'starts_at' => '2026-03-06 14:00:00',
                'ends_at' => '2026-03-06 15:58:00',
            ],
            [
                'movie_id' => $supergrandpaId,
                'hall_id' => $hallAId,
                'starts_at' => '2026-03-06 16:30:00',
                'ends_at' => '2026-03-06 18:28:00',
            ],
            [
                'movie_id' => $supergrandpaId,
                'hall_id' => $hallAId,
                'starts_at' => '2026-03-06 19:30:00',
                'ends_at' => '2026-03-06 21:28:00',
            ],
            [
                'movie_id' => $supergrandpaId,
                'hall_id' => $hallAId,
                'starts_at' => '2026-03-07 18:30:00',
                'ends_at' => '2026-03-07 20:28:00',
            ],
            [
                'movie_id' => $squirrelId,
                'hall_id' => $hallAId,
                'starts_at' => '2026-03-08 16:30:00',
                'ends_at' => '2026-03-08 18:11:00',
            ],
        ];

        foreach ($slots as $slot) {
            DB::table('schedule_slots')->insert($slot);
        }

        $availableId = $statusId('available');
        $figurineId = $categoryId('Figurine');
        $plushId = $categoryId('Plush Toy');
        $accessoryId = $categoryId('Accessory');
        $propReplicaId = $categoryId('Prop Replica');
        $stickerPackId = $categoryId('Sticker Pack');
        $printId = $categoryId('Print');

        $getMovieId = fn(string $title) => DB::table('movies')->where('title', $title)->value('id');

        $souvenirs = [
            [
                'name' => 'SuperGrandpa Figurine',
                'price' => 9.99,
                'category_id' => $figurineId,
                'movie_id' => $getMovieId('SuperGrandpa'),
                'quantity' => 50,
                'status_id' => $availableId,
                'image' => '/images/SuperGrandpaSouvenir.png',
            ],
            [
                'name' => "Mad Squirrel Figurine",
                'price' => 9.99,
                'category_id' => $figurineId,
                'movie_id' => $getMovieId("The Squirrel's Revenge"),
                'quantity' => 30,
                'status_id' => $availableId,
                'image' => '/images/SuperGrandpaSouvenir.png',
            ],
            [
                'name' => 'Mad Squirrel Sticker Pack',
                'price' => 4.99,
                'category_id' => $stickerPackId,
                'movie_id' => $getMovieId("The Squirrel's Revenge"),
                'quantity' => 100,
                'status_id' => $availableId,
                'image' => '/images/SquirrelSouvenir.png',
            ],
            [
                'name' => "Hiding Nemo Plush Toy",
                'price' => 9.99,
                'category_id' => $plushId,
                'movie_id' => $getMovieId('Hiding Nemo'),
                'quantity' => 40,
                'status_id' => $availableId,
                'image' => '/images/plushtoynemo.png',
            ],
            [
                'name' => "Blue Bulk Prop Replica",
                'price' => 9.99,
                'category_id' => $propReplicaId,
                'movie_id' => $getMovieId('The Ordinary Blue Bulk'),
                'quantity' => 20,
                'status_id' => $availableId,
                'image' => '/images/bluebulkpropreplica.png',
            ],
            [
                'name' => "Gollum's Ring",
                'price' => 9.99,
                'category_id' => $accessoryId,
                'movie_id' => $getMovieId('Gollum: Steal The Ring'),
                'quantity' => 25,
                'status_id' => $availableId,
                'image' => '/images/gollumring.png',
            ],
            [
                'name' => "Mission: Possible Print",
                'price' => 9.99,
                'category_id' => $printId,
                'movie_id' => $getMovieId('Mission: Possible'),
                'quantity' => 60,
                'status_id' => $availableId,
                'image' => '/images/missionpossibleprint.png',
            ],
        ];

        foreach ($souvenirs as $data) {
            $image = $data['image'];
            $souvenirData = array_diff_key($data, array_flip(['image']));
            $souvenirData = array_merge($souvenirData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $souvenirId = DB::table('souvenirs')->insertGetId($souvenirData);

            DB::table('souvenir_images')->insert([
                'souvenir_id' => $souvenirId,
                'url' => $image,
                'is_primary' => true,
                'created_at' => now(),
            ]);
        }
    }
}
