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

        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@moviemall.com',
            'password' => 'MM$ecure#2026!',
            'is_admin' => true,
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
            ['name' => 'Poster'],
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
                'price' => 12.99,
                'release_date' => '2026-02-28',
                'length_minutes' => 118,
                'director_id' => $directorId('John Smith'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Adventure', 'Comedy'],
                'image' => '/images/grandpa/Supergrandpa.jpg',
            ],
            [
                'title' => 'Gollum: Steal The Ring',
                'description' => 'A desperate anti-hero embarks on a dangerous quest to reclaim a legendary ring and outsmart enemies from every realm.',
                'rating' => 9.5,
                'price' => 15.99,
                'release_date' => '2028-10-10',
                'length_minutes' => 132,
                'director_id' => $directorId('Ava Knight'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Adventure', 'Action'],
                'image' => '/images/gollum/gollum.png',
            ],
            [
                'title' => 'Mission: Possible',
                'description' => 'When every plan fails, one team improvises the impossible to stop a global catastrophe.',
                'rating' => 6.5,
                'price' => 9.99,
                'release_date' => '2024-06-01',
                'length_minutes' => 109,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Drama'],
                'image' => '/images/mission/missionpossible.png',
            ],
            [
                'title' => 'Hiding Nemo',
                'description' => 'A heartfelt underwater journey about courage, friendship, and finding your way home.',
                'rating' => 4.5,
                'price' => 7.99,
                'release_date' => '2018-03-15',
                'length_minutes' => 103,
                'director_id' => $directorId('Evan Lee'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Adventure'],
                'image' => '/images/nemo/hidingnemo.png',
            ],
            [
                'title' => 'The Ordinary Blue Bulk',
                'description' => 'An unlikely hero rises in a collapsing world, balancing brute force with unexpected compassion.',
                'rating' => 9.8,
                'price' => 14.99,
                'release_date' => '2025-05-12',
                'length_minutes' => 124,
                'director_id' => $directorId('Noah Grant'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Apocalypse', 'Drama'],
                'image' => '/images/bulk/bluebulk.png',
            ],
            [
                'title' => "The Squirrel's Revenge",
                'description' => 'A mischievous squirrel mastermind turns a quiet city into chaos, forcing unlikely heroes to step up.',
                'rating' => 7.5,
                'price' => 8.99,
                'release_date' => '2020-08-23',
                'length_minutes' => 101,
                'director_id' => $directorId('Liam Carter'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy'],
                'image' => '/images/squirrel/Squirrel.jpg',
            ],
            [
                'title' => 'Dr. Normal',
                'description' => 'A brilliant scientist tries to live an ordinary life while confronting the consequences of past experiments.',
                'rating' => 8.2,
                'price' => 11.99,
                'release_date' => '2026-01-17',
                'length_minutes' => 116,
                'director_id' => $directorId('Sophie Hall'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Documentary'],
                'image' => '/images/dr_normal/drnormal.png',
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
        $hallBId = DB::table('halls')->where('name', 'Hall B')->value('id');

        $slotDefs = [
            // SuperGrandpa — Hall A
            ['title' => 'SuperGrandpa',            'hall' => $hallAId, 'starts_at' => '2026-05-10 14:00:00', 'length' => 118],
            ['title' => 'SuperGrandpa',            'hall' => $hallAId, 'starts_at' => '2026-05-10 17:30:00', 'length' => 118],
            ['title' => 'SuperGrandpa',            'hall' => $hallAId, 'starts_at' => '2026-05-11 19:00:00', 'length' => 118],
            ['title' => 'SuperGrandpa',            'hall' => $hallAId, 'starts_at' => '2026-05-13 15:00:00', 'length' => 118],
            // Gollum — Hall B
            ['title' => 'Gollum: Steal The Ring',  'hall' => $hallBId, 'starts_at' => '2026-05-10 13:00:00', 'length' => 132],
            ['title' => 'Gollum: Steal The Ring',  'hall' => $hallBId, 'starts_at' => '2026-05-11 16:00:00', 'length' => 132],
            ['title' => 'Gollum: Steal The Ring',  'hall' => $hallBId, 'starts_at' => '2026-05-12 20:00:00', 'length' => 132],
            ['title' => 'Gollum: Steal The Ring',  'hall' => $hallBId, 'starts_at' => '2026-05-14 14:30:00', 'length' => 132],
            // Mission: Possible — Hall A
            ['title' => 'Mission: Possible',       'hall' => $hallAId, 'starts_at' => '2026-05-09 11:00:00', 'length' => 109],
            ['title' => 'Mission: Possible',       'hall' => $hallAId, 'starts_at' => '2026-05-11 13:30:00', 'length' => 109],
            ['title' => 'Mission: Possible',       'hall' => $hallAId, 'starts_at' => '2026-05-13 18:00:00', 'length' => 109],
            // Hiding Nemo — Hall B
            ['title' => 'Hiding Nemo',             'hall' => $hallBId, 'starts_at' => '2026-05-09 15:00:00', 'length' => 103],
            ['title' => 'Hiding Nemo',             'hall' => $hallBId, 'starts_at' => '2026-05-10 19:00:00', 'length' => 103],
            ['title' => 'Hiding Nemo',             'hall' => $hallBId, 'starts_at' => '2026-05-12 12:00:00', 'length' => 103],
            // The Ordinary Blue Bulk — Hall A
            ['title' => 'The Ordinary Blue Bulk',  'hall' => $hallAId, 'starts_at' => '2026-05-09 17:00:00', 'length' => 124],
            ['title' => 'The Ordinary Blue Bulk',  'hall' => $hallAId, 'starts_at' => '2026-05-11 20:00:00', 'length' => 124],
            ['title' => 'The Ordinary Blue Bulk',  'hall' => $hallAId, 'starts_at' => '2026-05-14 16:00:00', 'length' => 124],
            // The Squirrel's Revenge — Hall B
            ["title" => "The Squirrel's Revenge",  'hall' => $hallBId, 'starts_at' => '2026-05-09 13:00:00', 'length' => 101],
            ["title" => "The Squirrel's Revenge",  'hall' => $hallBId, 'starts_at' => '2026-05-10 16:00:00', 'length' => 101],
            ["title" => "The Squirrel's Revenge",  'hall' => $hallBId, 'starts_at' => '2026-05-12 19:30:00', 'length' => 101],
            ["title" => "The Squirrel's Revenge",  'hall' => $hallBId, 'starts_at' => '2026-05-15 14:00:00', 'length' => 101],
            // Dr. Normal — Hall A
            ['title' => 'Dr. Normal',              'hall' => $hallAId, 'starts_at' => '2026-05-09 12:00:00', 'length' => 116],
            ['title' => 'Dr. Normal',              'hall' => $hallAId, 'starts_at' => '2026-05-11 15:00:00', 'length' => 116],
            ['title' => 'Dr. Normal',              'hall' => $hallAId, 'starts_at' => '2026-05-13 20:00:00', 'length' => 116],
            ['title' => 'Dr. Normal',              'hall' => $hallAId, 'starts_at' => '2026-05-15 17:30:00', 'length' => 116],
        ];

        foreach ($slotDefs as $def) {
            $movieId = DB::table('movies')->where('title', $def['title'])->value('id');
            $ends = \Carbon\Carbon::parse($def['starts_at'])->addMinutes($def['length'] + 15)->format('Y-m-d H:i:s');
            DB::table('schedule_slots')->insert([
                'movie_id'  => $movieId,
                'hall_id'   => $def['hall'],
                'starts_at' => $def['starts_at'],
                'ends_at'   => $ends,
            ]);
        }

        $availableId   = $statusId('available');
        $figurineId    = $categoryId('Figurine');
        $plushId       = $categoryId('Plush Toy');
        $accessoryId   = $categoryId('Accessory');
        $propReplicaId = $categoryId('Prop Replica');
        $stickerPackId = $categoryId('Sticker Pack');
        $printId       = $categoryId('Print');
        $posterId      = $categoryId('Poster');

        $getMovieId = fn(string $title) => DB::table('movies')->where('title', $title)->value('id');

        $souvenirs = [
            // Superrandpa
            ['name' => 'SuperGrandpa Cape Pin', 'price' => 7.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 80, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_accessory.jpg'],
            ['name' => 'SuperGrandpa Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 50, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_figurine.jpg'],
            ['name' => 'SuperGrandpa Deluxe Figurine', 'price' => 14.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_figurine_2.png'],
            ['name' => 'SuperGrandpa Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 35, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_plush.jpg'],
            ['name' => 'SuperGrandpa Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 100, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_poster.png'],
            ['name' => 'SuperGrandpa Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_print.jpg'],
            ['name' => 'SuperGrandpa Prop Replica', 'price' => 29.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_prop.jpg'],
            ['name' => 'SuperGrandpa Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 90, 'status_id' => $availableId, 'image' => '/images/grandpa/grandpa_stickers.jpg'],

            // gollum stela the rng
            ["name" => "Gollum's Ring", 'price' => 9.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/gollum/gollum_accessory.jpg'],
            ['name' => 'Gollum Figurine', 'price' => 11.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/gollum/gollum_figurine.jpg'],
            ['name' => 'Gollum Plush Toy', 'price' => 14.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/gollum/gollum_plush.jpg'],
            ['name' => 'Gollum Movie Poster', 'price' => 6.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 65, 'status_id' => $availableId, 'image' => '/images/gollum/gollum_poster.jpg'],
            ['name' => 'Gollum Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 55, 'status_id' => $availableId, 'image' => '/images/gollum/gollum_print.jpg'],
            ['name' => 'Gollum Collector Statue', 'price' => 49.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 10, 'status_id' => $availableId, 'image' => '/images/gollum/gollum_prop.jpg'],
            ['name' => 'Gollum Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 75, 'status_id' => $availableId, 'image' => '/images/gollum/gollum_stickers.jpg'],

            // mission possible
            ['name' => 'Mission: Possible Spy Gadget', 'price' => 12.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 40, 'status_id' => $availableId, 'image' => '/images/mission/mission_accessory.jpg'],
            ['name' => 'Mission: Possible Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 35, 'status_id' => $availableId, 'image' => '/images/mission/mission_figurine.jpg'],
            ['name' => 'Mission: Possible Plush Toy', 'price' => 11.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/mission/mission_plush.jpg'],
            ['name' => 'Mission: Possible Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 70, 'status_id' => $availableId, 'image' => '/images/mission/mission_poster.jpg'],
            ['name' => 'Mission: Possible Art Print', 'price' => 7.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/mission/mission_print.jpg'],
            ['name' => 'Mission: Possible Special Print', 'price' => 9.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/mission/mission_print_2.png'],
            ['name' => 'Mission: Possible Gadget Set', 'price' => 34.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 12, 'status_id' => $availableId, 'image' => '/images/mission/mission_prop.jpg'],
            ['name' => 'Mission: Possible Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 85, 'status_id' => $availableId, 'image' => '/images/mission/mission_stickers.jpg'],

            // hiding nemo
            ['name' => 'Hiding Nemo Seashell Necklace', 'price' => 7.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 45, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_accessory.jpg'],
            ['name' => 'Hiding Nemo Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_figurine.jpg'],
            ['name' => 'Hiding Nemo Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 40, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_plush.jpg'],
            ['name' => 'Hiding Nemo XL Plush Toy', 'price' => 19.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_plush_2.png'],
            ['name' => 'Hiding Nemo Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 75, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_poster.jpg'],
            ['name' => 'Hiding Nemo Art Print', 'price' => 7.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 50, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_print.jpg'],
            ['name' => 'Hiding Nemo Prop Replica', 'price' => 24.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 18, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_prop.jpg'],
            ['name' => 'Hiding Nemo Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 90, 'status_id' => $availableId, 'image' => '/images/nemo/nemo_stickers.jpg'],

            // blue bilk
            ['name' => 'Blue Bulk Power Band', 'price' => 9.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 40, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_accessory.jpg'],
            ['name' => 'Blue Bulk Figurine', 'price' => 11.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_figurine.jpg'],
            ['name' => 'Blue Bulk Plush Toy', 'price' => 14.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_plush.jpg'],
            ['name' => 'Blue Bulk Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_poster.jpg'],
            ['name' => 'Blue Bulk Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 45, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_print.jpg'],
            ['name' => 'Blue Bulk Prop Replica', 'price' => 29.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_prop.jpg'],
            ['name' => 'Blue Bulk Deluxe Prop Replica', 'price' => 44.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 8, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_prop_2.png'],
            ['name' => 'Blue Bulk Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 80, 'status_id' => $availableId, 'image' => '/images/bulk/bulk_stickers.jpg'],

            // squirrels revenge
            ['name' => 'Mad Squirrel Acorn Keychain', 'price' => 6.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 70, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_accessory.jpg'],
            ['name' => 'Mad Squirrel Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_figurine.jpg'],
            ['name' => 'Mad Squirrel Deluxe Figurine', 'price' => 14.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_figurine_2.png'],
            ['name' => 'Mad Squirrel Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_plush.jpg'],
            ["name" => "Squirrel's Revenge Movie Poster", 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 80, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_poster.png'],
            ["name" => "Squirrel's Revenge Art Print", 'price' => 7.99, 'category_id' => $printId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 55, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_print.jpg'],
            ['name' => 'Mad Squirrel Prop Replica', 'price' => 24.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 18, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_prop.jpg'],
            ["name" => "Squirrel's Revenge Sticker Pack", 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 100, 'status_id' => $availableId, 'image' => '/images/squirrel/squirrel_stickers.jpg'],

            // dr normal
            ['name' => 'Dr. Normal Lab Badge', 'price' => 7.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/dr_normal/dr_normal_accessory.jpg'],
            ['name' => 'Dr. Normal Figurine', 'price' => 11.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 35, 'status_id' => $availableId, 'image' => '/images/dr_normal/dr_normal_figurine.jpg'],
            ['name' => 'Dr. Normal Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/dr_normal/dr_normal_plush.jpg'],
            ['name' => 'Dr. Normal Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 70, 'status_id' => $availableId, 'image' => '/images/dr_normal/dr_normal_poster.jpg'],
            ['name' => 'Dr. Normal Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 45, 'status_id' => $availableId, 'image' => '/images/dr_normal/dr_normal_print.jpg'],
            ['name' => 'Dr. Normal Lab Equipment', 'price' => 39.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 10, 'status_id' => $availableId, 'image' => '/images/dr_normal/dr_normal_prop.jpg'],
            ['name' => 'Dr. Normal Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 75, 'status_id' => $availableId, 'image' => '/images/dr_normal/dr_normal_stickers.jpg'],
        ];

        foreach ($souvenirs as $data) {
            $image = $data['image'];
            $souvenirData = array_diff_key($data, array_flip(['image']));
            $souvenirData['created_at'] = now();
            $souvenirData['updated_at'] = now();

            $souvenirId = DB::table('souvenirs')->insertGetId($souvenirData);

            DB::table('souvenir_images')->insert([
                'souvenir_id' => $souvenirId,
                'url'         => $image,
                'is_primary'  => true,
                'created_at'  => now(),
            ]);

            $movieImage = DB::table('movie_images')
                ->where('movie_id', $souvenirData['movie_id'])
                ->where('is_primary', true)
                ->value('url');

            if ($movieImage) {
                DB::table('souvenir_images')->insert([
                    'souvenir_id' => $souvenirId,
                    'url'         => $movieImage,
                    'is_primary'  => false,
                    'created_at'  => now(),
                ]);
            }
        }
    }
}
