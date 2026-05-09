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

        $directors = ['John Smith', 'Ava Knight', 'Mia Stone', 'Evan Lee', 'Noah Grant', 'Liam Carter', 'Sophie Hall', 'Carlos Reyes', 'Priya Sharma', 'Elena Volkov'];
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
                'genres' => ['Action', 'Adventure', 'Comedy', 'Sports'],
                'image' => '/images/grandpa/movies/Supergrandpa.jpg',
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
                'genres' => ['Adventure', 'Action', 'Fantasy'],
                'image' => '/images/gollum/movies/gollum.png',
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
                'image' => '/images/mission/movies/missionpossible.png',
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
                'image' => '/images/nemo/movies/hidingnemo.png',
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
                'image' => '/images/bulk/movies/bluebulk.png',
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
                'genres' => ['Comedy', 'Western'],
                'image' => '/images/squirrel/movies/Squirrel.jpg',
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
                'image' => '/images/dr_normal/movies/drnormal.png',
            ],

            // Dr. Normal sequels
            [
                'title' => 'Dr. Normal 2: Just Another Day',
                'description' => 'The average man returns for another unremarkable morning routine. Critics call it "aggressively uneventful." Audiences cannot stop watching.',
                'rating' => 7.8,
                'price' => 10.99,
                'release_date' => '2027-03-05',
                'length_minutes' => 98,
                'director_id' => $directorId('Sophie Hall'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Documentary'],
                'image' => '/images/dr_normal/movies/dr-normal-2.jpg',
            ],
            [
                'title' => 'Dr. Normal 3: The Routine Checkup',
                'description' => 'A waiting room. A three-year-old magazine. Forty-five minutes of beige. This is the film that redefined patience as an art form.',
                'rating' => 6.9,
                'price' => 9.99,
                'release_date' => '2028-06-14',
                'length_minutes' => 104,
                'director_id' => $directorId('Sophie Hall'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Documentary'],
                'image' => '/images/dr_normal/movies/dr-normal-3.jpg',
            ],
            [
                'title' => 'Dr. Normal 4: Mildly Inconvenienced',
                'description' => 'Toast lands butter-side up. This changes nothing. A meditation on the crushing neutrality of fate.',
                'rating' => 7.2,
                'price' => 10.99,
                'release_date' => '2029-09-22',
                'length_minutes' => 96,
                'director_id' => $directorId('Sophie Hall'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Comedy'],
                'image' => '/images/dr_normal/movies/dr-normal-4.jpg',
            ],
            [
                'title' => 'Dr. Normal 5: The Multiverse of Average',
                'description' => 'Infinite realities, infinite versions of the same man doing slightly different mundane tasks. Every universe ends with laundry.',
                'rating' => 8.0,
                'price' => 12.99,
                'release_date' => '2030-11-08',
                'length_minutes' => 112,
                'director_id' => $directorId('Sophie Hall'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Sci-Fi'],
                'image' => '/images/dr_normal/movies/dr-normal-5.jpg',
            ],
            [
                'title' => 'Dr. Normal: Origins (He Was Always Like This)',
                'description' => 'A sepia-toned prequel revealing that Dr. Normal has been exactly like this since birth. The baby looked unimpressed. Nothing has changed.',
                'rating' => 6.5,
                'price' => 8.99,
                'release_date' => '2017-04-01',
                'length_minutes' => 88,
                'director_id' => $directorId('Sophie Hall'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Documentary'],
                'image' => '/images/dr_normal/movies/dr-normal-origins.jpg',
            ],

            // Gollum sequels
            [
                'title' => 'Gollum 2: I Lost It Again',
                'description' => 'The precious slips between sofa cushions. What follows is thirty minutes of increasingly desperate digging and a handful of old coins.',
                'rating' => 8.1,
                'price' => 12.99,
                'release_date' => '2030-03-21',
                'length_minutes' => 118,
                'director_id' => $directorId('Ava Knight'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Adventure', 'Comedy', 'Fantasy'],
                'image' => '/images/gollum/movies/gollum-2.jpg',
            ],
            [
                'title' => 'Gollum 3: The Pawn Shop Returns',
                'description' => 'Standing in the rain outside a barred pawn shop window, our hero must decide: haggle, or despair. He does both.',
                'rating' => 7.6,
                'price' => 11.99,
                'release_date' => '2031-07-11',
                'length_minutes' => 110,
                'director_id' => $directorId('Ava Knight'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Adventure', 'Comedy', 'Fantasy'],
                'image' => '/images/gollum/movies/gollum-3.jpg',
            ],
            [
                'title' => 'Gollum 4: The Fellowship of the Bling',
                'description' => 'Plastic crown. Fake gold chains. A cave rap album. The most critically divisive entry in the franchise is also its most beloved.',
                'rating' => 8.4,
                'price' => 13.99,
                'release_date' => '2032-10-31',
                'length_minutes' => 124,
                'director_id' => $directorId('Ava Knight'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Adventure', 'Comedy', 'Action'],
                'image' => '/images/gollum/movies/gollum-4.jpg',
            ],
            [
                'title' => 'Gollum 5: The Two Pigeons',
                'description' => 'One stale french fry. Two indifferent pigeons. An epic territorial standoff that lasts the entire runtime and ends inconclusively.',
                'rating' => 7.2,
                'price' => 10.99,
                'release_date' => '2033-04-07',
                'length_minutes' => 98,
                'director_id' => $directorId('Ava Knight'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy'],
                'image' => '/images/gollum/movies/gollum-5.jpg',
            ],
            [
                'title' => "Gollum 6: Return of the King's Cousin",
                'description' => 'A muddy cardboard sign. A manicured doorbell. A family that really wishes he had called ahead. The most emotional chapter yet.',
                'rating' => 8.8,
                'price' => 14.99,
                'release_date' => '2034-12-19',
                'length_minutes' => 130,
                'director_id' => $directorId('Ava Knight'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Adventure', 'Comedy'],
                'image' => '/images/gollum/movies/gollum-6.jpg',
            ],

            // Hiding Nemo sequels
            [
                'title' => 'Hiding Nemo 2: He\'s Still There',
                'description' => 'The clownfish returns to the same thin strand of seaweed. No one is fooled. He does not notice.',
                'rating' => 5.2,
                'price' => 8.99,
                'release_date' => '2021-07-04',
                'length_minutes' => 95,
                'director_id' => $directorId('Evan Lee'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Adventure', 'Comedy'],
                'image' => '/images/nemo/movies/hiding-nemo-2.jpg',
            ],
            [
                'title' => 'Hiding Dory (She Forgot Where She Hid)',
                'description' => 'Armed with a soggy crayon map and absolutely no memory, Dory embarks on a search for a hiding spot she invented and immediately forgot.',
                'rating' => 6.1,
                'price' => 9.99,
                'release_date' => '2023-05-19',
                'length_minutes' => 100,
                'director_id' => $directorId('Evan Lee'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Adventure', 'Romance'],
                'image' => '/images/nemo/movies/hiding-dory.jpg',
            ],
            [
                'title' => 'Hiding Nemo 4: The Witness Protection Program',
                'description' => 'A tiny fake moustache. Oversized dark sunglasses. An entire school of gray tuna that are absolutely not convinced.',
                'rating' => 6.8,
                'price' => 10.99,
                'release_date' => '2025-08-30',
                'length_minutes' => 102,
                'director_id' => $directorId('Evan Lee'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama'],
                'image' => '/images/nemo/movies/hiding-nemo-4.jpg',
            ],
            [
                'title' => 'Hiding Nemo 5: Found Him (Just Kidding)',
                'description' => 'A small sign in the sand reads "Be Back In 5 Minutes." He has been gone for three years. Nobody is surprised.',
                'rating' => 5.5,
                'price' => 8.99,
                'release_date' => '2027-02-14',
                'length_minutes' => 88,
                'director_id' => $directorId('Evan Lee'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy'],
                'image' => '/images/nemo/movies/hiding-nemo-5.jpg',
            ],

            // Mission: Possible sequels
            [
                'title' => 'Mission: Possible 2: Actually Pretty Easy',
                'description' => 'The team is deployed to neutralize a party popper. They are perhaps overqualified. The slow-motion walk away is nine minutes long.',
                'rating' => 5.8,
                'price' => 9.99,
                'release_date' => '2026-06-12',
                'length_minutes' => 102,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy'],
                'image' => '/images/mission/movies/mission-possible-2.jpg',
            ],
            [
                'title' => 'Mission: Possible 3: A Minor Setback',
                'description' => 'He pulled on a door marked PUSH for eleven minutes. The mission was not completed. He does not speak of it.',
                'rating' => 6.2,
                'price' => 9.99,
                'release_date' => '2027-09-03',
                'length_minutes' => 106,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy'],
                'image' => '/images/mission/movies/mission-possible-3.jpg',
            ],
            [
                'title' => 'Mission: Possible 4: Ghost Protocol (He Just Ignored My Texts)',
                'description' => 'The message was delivered. It was read at 9:00 AM. There was no reply. The agent investigates this personally and with great intensity.',
                'rating' => 7.0,
                'price' => 11.99,
                'release_date' => '2028-11-22',
                'length_minutes' => 113,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Drama', 'Romance'],
                'image' => '/images/mission/movies/mission-possible-4.jpg',
            ],
            [
                'title' => 'Mission: Possible 5: Rogue Nation (They Forgot To Pay Taxes)',
                'description' => 'A W-2 form held up to the light. Sweat. The IRS. The most tense financial thriller of the decade.',
                'rating' => 6.5,
                'price' => 10.99,
                'release_date' => '2029-04-15',
                'length_minutes' => 108,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy', 'Thriller', 'Western'],
                'image' => '/images/mission/movies/mission-possible-5.jpg',
            ],
            [
                'title' => 'Mission: Possible 6: Fallout (We Dropped A Plate)',
                'description' => 'It happened in slow motion. The agent lunged. The plate did not survive. The kitchen will never be the same.',
                'rating' => 7.5,
                'price' => 12.99,
                'release_date' => '2030-08-08',
                'length_minutes' => 120,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Drama', 'Sports'],
                'image' => '/images/mission/movies/mission-possible-6.jpg',
            ],
            [
                'title' => 'Mission: Possible 7: Dead Reckoning (Using A Calculator)',
                'description' => 'A tactical glove hovers over the equals button. The fate of the quarterly budget hangs in the balance. Press it.',
                'rating' => 7.8,
                'price' => 12.99,
                'release_date' => '2031-12-03',
                'length_minutes' => 116,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Thriller'],
                'image' => '/images/mission/movies/mission-possible-7.jpg',
            ],
            [
                'title' => 'Mission: Possible 8: The Final Checkmark',
                'description' => 'One whiteboard. One dry-erase marker. One last box to tick. The franchise comes to its inevitable, satisfying, administrative conclusion.',
                'rating' => 8.3,
                'price' => 13.99,
                'release_date' => '2032-07-04',
                'length_minutes' => 125,
                'director_id' => $directorId('Mia Stone'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Drama'],
                'image' => '/images/mission/movies/mission-possible-8.jpg',
            ],

            // SuperGrandpa sequels
            [
                'title' => 'SuperGrandpa 2: The Search for the Dentures',
                'description' => 'From the depths of a glass of water rises an artifact of legendary power. Only the chosen grandfather may wield it.',
                'rating' => 7.0,
                'price' => 11.99,
                'release_date' => '2028-05-17',
                'length_minutes' => 112,
                'director_id' => $directorId('John Smith'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Adventure', 'Comedy', 'Sports'],
                'image' => '/images/grandpa/movies/supergrandpa-2.jpg',
            ],
            [
                'title' => 'SuperGrandpa 3: Asleep at 4 PM',
                'description' => 'Golden-hour sunlight. A floral recliner. An open mouth. The most visually stunning entry in the SuperGrandpa saga.',
                'rating' => 6.8,
                'price' => 9.99,
                'release_date' => '2030-02-28',
                'length_minutes' => 96,
                'director_id' => $directorId('John Smith'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Fantasy', 'Sports'],
                'image' => '/images/grandpa/movies/supergrandpa-3.jpg',
            ],
            [
                'title' => 'SuperGrandpa 4: Back in My Day',
                'description' => 'The hero encounters a smartphone and is not pleased. A sepia-toned war breaks out between the past and the present.',
                'rating' => 7.5,
                'price' => 12.99,
                'release_date' => '2032-10-10',
                'length_minutes' => 108,
                'director_id' => $directorId('John Smith'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy', 'Drama', 'Fantasy'],
                'image' => '/images/grandpa/movies/supergrandpa-4.jpg',
            ],
            [
                'title' => 'SuperGrandpa vs. The Remote Control',
                'description' => 'Across the living room, man and machine lock eyes. The remote glows red. The hero will not blink. Neither will the remote.',
                'rating' => 8.0,
                'price' => 13.99,
                'release_date' => '2034-06-21',
                'length_minutes' => 118,
                'director_id' => $directorId('John Smith'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy', 'Sports'],
                'image' => '/images/grandpa/movies/supergrandpa-remote.jpg',
            ],

            // Blue Bulk sequels
            [
                'title' => 'The Ordinary Blue Bulk 2: Slightly Annoyed',
                'description' => 'The DMV line stretches beyond the horizon. The blue giant sighs. The walls crack slightly. He checks his watch again.',
                'rating' => 8.5,
                'price' => 13.99,
                'release_date' => '2027-05-01',
                'length_minutes' => 118,
                'director_id' => $directorId('Noah Grant'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy', 'Apocalypse'],
                'image' => '/images/bulk/movies/blue-bulk-2.jpg',
            ],
            [
                'title' => 'The Ordinary Blue Bulk 3: He Needs a Nap',
                'description' => 'A twin bed. A teddy bear. A blue giant who simply cannot fit. The most poignant entry in the franchise asks: can a hero rest?',
                'rating' => 8.0,
                'price' => 12.99,
                'release_date' => '2028-09-14',
                'length_minutes' => 122,
                'director_id' => $directorId('Noah Grant'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy', 'Drama'],
                'image' => '/images/bulk/movies/blue-bulk-3.jpg',
            ],
            [
                'title' => 'The Ordinary Blue Bulk 4: The Grocery Store Meltdown',
                'description' => 'He came for eggs. He saw the price. A shopping cart ceased to exist. Witnesses are still giving statements.',
                'rating' => 7.8,
                'price' => 12.99,
                'release_date' => '2029-11-28',
                'length_minutes' => 115,
                'director_id' => $directorId('Noah Grant'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy', 'Sports'],
                'image' => '/images/bulk/movies/blue-bulk-4.jpg',
            ],
            [
                'title' => 'The Ordinary Blue Bulk 5: Planet Bulk (It\'s Just a Small Island)',
                'description' => 'Marooned on a cartoonishly tiny island with one palm tree and no wifi, the blue giant confronts the most terrifying enemy yet: boredom.',
                'rating' => 8.2,
                'price' => 13.99,
                'release_date' => '2030-07-04',
                'length_minutes' => 128,
                'director_id' => $directorId('Noah Grant'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Adventure', 'Apocalypse'],
                'image' => '/images/bulk/movies/blue-bulk-5.jpg',
            ],
            [
                'title' => 'The Ordinary Blue Bulk 6: The Mildly Frustrating Traffic Jam',
                'description' => 'His head touches the roof of the sedan. The bumper is three inches from the next car. He honks. The city trembles slightly.',
                'rating' => 8.6,
                'price' => 14.99,
                'release_date' => '2031-04-22',
                'length_minutes' => 134,
                'director_id' => $directorId('Noah Grant'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Action', 'Comedy', 'Drama', 'Apocalypse'],
                'image' => '/images/bulk/movies/blue-bulk-6.jpg',
            ],

            // Squirrel's Revenge sequels
            [
                'title' => "The Squirrel's Revenge 2: For the Acorns",
                'description' => 'War paint applied. Mountain of acorns secured. The general surveys her kingdom and finds it satisfactory. The city sleeps, unaware.',
                'rating' => 7.2,
                'price' => 9.99,
                'release_date' => '2022-10-01',
                'length_minutes' => 98,
                'director_id' => $directorId('Liam Carter'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Action', 'Western'],
                'image' => '/images/squirrel/movies/squirrel-2.jpg',
            ],
            [
                'title' => "The Squirrel's Revenge 3: The Bird Feeder Heist",
                'description' => 'Equipped with a tiny harness and zero remorse, the squirrel rappels toward the plastic bird feeder. The birds have been warned. They do nothing.',
                'rating' => 7.8,
                'price' => 10.99,
                'release_date' => '2024-04-13',
                'length_minutes' => 104,
                'director_id' => $directorId('Liam Carter'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Adventure', 'Western', 'Sports'],
                'image' => '/images/squirrel/movies/squirrel-3.jpg',
            ],
            [
                'title' => "The Squirrel's Revenge 4: Barking Up the Wrong Tree",
                'description' => 'She is in the correct tree. The golden retriever is at an entirely different tree. Neither party adjusts their position. A standoff for the ages.',
                'rating' => 6.9,
                'price' => 9.99,
                'release_date' => '2026-08-20',
                'length_minutes' => 96,
                'director_id' => $directorId('Liam Carter'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Western'],
                'image' => '/images/squirrel/movies/squirrel-4.jpg',
            ],
            [
                'title' => "The Squirrel's Revenge 5: Winter is Coming (Time to Hibernate)",
                'description' => 'A knitted blanket. A hollowed oak. A miniature cup of tea. The warlord of the park system finally takes a very well-earned rest.',
                'rating' => 7.5,
                'price' => 11.99,
                'release_date' => '2028-12-01',
                'length_minutes' => 102,
                'director_id' => $directorId('Liam Carter'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama'],
                'image' => '/images/squirrel/movies/squirrel-5.jpg',
            ],

            // The Godmother (new franchise)
            [
                'title' => 'The Godmother',
                'description' => 'She holds the lasagna like a holy relic. She asks for nothing, except everything. A matriarch makes her family an offer they cannot possibly refuse.',
                'rating' => 8.7,
                'price' => 13.99,
                'release_date' => '2019-11-01',
                'length_minutes' => 142,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller', 'Horror'],
                'image' => '/images/godmother/movies/godmother-1.jpg',
            ],
            [
                'title' => 'The Godmother Part II: The Guilt Trip',
                'description' => 'A split-screen masterpiece. Young and old. A metal lunchbox packed with intent. A landline receiver wielded like a weapon. Guilt at its most operatic.',
                'rating' => 8.9,
                'price' => 14.99,
                'release_date' => '2021-03-19',
                'length_minutes' => 148,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller', 'Horror'],
                'image' => '/images/godmother/movies/godmother-2.jpg',
            ],
            [
                'title' => 'The Godmother Part III: The Leftovers',
                'description' => 'The kitchen is dark. The Tupperware towers. Each container is labeled. Each container is a promise. Each promise is non-negotiable.',
                'rating' => 7.8,
                'price' => 12.99,
                'release_date' => '2023-09-29',
                'length_minutes' => 132,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller', 'Comedy', 'Horror'],
                'image' => '/images/godmother/movies/godmother-3.jpg',
            ],
            [
                'title' => 'The Godmother 4: Family Meeting',
                'description' => 'Her chair is empty. A store-bought pie sits at the center of the table. The family stares at it in absolute silence. They know what this means.',
                'rating' => 8.2,
                'price' => 13.99,
                'release_date' => '2024-06-15',
                'length_minutes' => 138,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller'],
                'image' => '/images/godmother/movies/godmother-4.jpg',
            ],
            [
                'title' => 'The Godmother 5: The Favor',
                'description' => 'Her hand rests on his shoulder at the wedding. Her other hand holds a gold tablet with an unconfigured app. He will configure the app.',
                'rating' => 8.5,
                'price' => 13.99,
                'release_date' => '2025-02-14',
                'length_minutes' => 135,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller'],
                'image' => '/images/godmother/movies/godmother-5.jpg',
            ],
            [
                'title' => "The Godmother 6: Omertà (Code of Silence)",
                'description' => 'Her hand is on the thermostat. The family huddles in winter coats in July. Nobody speaks. This is how it has always been.',
                'rating' => 8.3,
                'price' => 13.99,
                'release_date' => '2026-10-03',
                'length_minutes' => 130,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller'],
                'image' => '/images/godmother/movies/godmother-6.jpg',
            ],
            [
                'title' => 'The Godmother 7: The Mattresses',
                'description' => 'She is tucked in. The room is pitch black. She looks peaceful. She looks stern. She is watching. She is always watching.',
                'rating' => 8.1,
                'price' => 13.99,
                'release_date' => '2027-05-12',
                'length_minutes' => 126,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller'],
                'image' => '/images/godmother/movies/godmother-7.jpg',
            ],
            [
                'title' => 'The Godmother 8: The Final Blessing',
                'description' => 'A casserole dish glows on a rock in a meadow. She walks into the sunset. The dish is empty. It has never been empty before. Something is ending.',
                'rating' => 9.2,
                'price' => 15.99,
                'release_date' => '2028-11-25',
                'length_minutes' => 152,
                'director_id' => $directorId('Elena Volkov'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Drama', 'Thriller'],
                'image' => '/images/godmother/movies/godmother-8.jpg',
            ],

            // The Procrastinator (new franchise)
            [
                'title' => 'The Procrastinator',
                'description' => 'A chrome skeleton in an ergonomic chair. A gaming controller. A loading icon that has been spinning for three years. It will get to it eventually.',
                'rating' => 8.4,
                'price' => 13.99,
                'release_date' => '2020-08-29',
                'length_minutes' => 118,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Action', 'Horror'],
                'image' => '/images/procrastinator/movies/procrastinator-1.jpg',
            ],
            [
                'title' => 'The Procrastinator 2: Judgment Day (Eventually)',
                'description' => 'Six monitors. Six buffering symbols. One cat video that somehow plays fine. The machines will rise. Just not today.',
                'rating' => 7.9,
                'price' => 12.99,
                'release_date' => '2022-04-22',
                'length_minutes' => 112,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Comedy', 'Horror'],
                'image' => '/images/procrastinator/movies/procrastinator-2.jpg',
            ],
            [
                'title' => 'The Procrastinator 3: Rise of the Machines (Tomorrow)',
                'description' => 'A cardboard sign around a metallic neck. A broken vending machine. The apocalypse has been rescheduled indefinitely pending coffee.',
                'rating' => 7.5,
                'price' => 11.99,
                'release_date' => '2023-09-08',
                'length_minutes' => 108,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Comedy'],
                'image' => '/images/procrastinator/movies/procrastinator-3.jpg',
            ],
            [
                'title' => 'The Procrastinator 4: Salvation (Pending)',
                'description' => 'A hammock. Two burning lampposts. A digital calendar full of Decline buttons. He has declined every meeting since 2019.',
                'rating' => 8.1,
                'price' => 12.99,
                'release_date' => '2024-07-27',
                'length_minutes' => 120,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Action', 'Apocalypse'],
                'image' => '/images/procrastinator/movies/procrastinator-4.jpg',
            ],
            [
                'title' => 'The Procrastinator 5: Genisys (Loading...)',
                'description' => 'ERROR: FAILED TO SAVE. The robot stares at the prompt. The robot does not click OK. The robot is not ready to deal with this right now.',
                'rating' => 7.8,
                'price' => 12.99,
                'release_date' => '2025-03-15',
                'length_minutes' => 116,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Thriller'],
                'image' => '/images/procrastinator/movies/procrastinator-5.jpg',
            ],
            [
                'title' => 'The Procrastinator 6: Dark Fate (In Drafts)',
                'description' => 'RE: APOCALYPSE. The email has been in drafts since 2021. The outbox icon spins. The world waits. He scrolls back to the top and rephrases the subject line.',
                'rating' => 8.3,
                'price' => 13.99,
                'release_date' => '2026-01-30',
                'length_minutes' => 124,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Action', 'Thriller'],
                'image' => '/images/procrastinator/movies/procrastinator-6.jpg',
            ],
            [
                'title' => "The Procrastinator 7: I'll Be Back (In a Minute)",
                'description' => 'Outside a Parisian café. A tiny espresso cup in a metal claw. The croissants are right there. He is thinking about it. He will order soon.',
                'rating' => 7.6,
                'price' => 11.99,
                'release_date' => '2027-08-18',
                'length_minutes' => 110,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Comedy'],
                'image' => '/images/procrastinator/movies/procrastinator-7.jpg',
            ],
            [
                'title' => 'The Procrastinator 8: The Final Deadline',
                'description' => 'The clock reads 11:59. He runs. Papers scatter. The fluorescent hallway stretches forever. For the first time in eight films, he might actually make it.',
                'rating' => 9.0,
                'price' => 15.99,
                'release_date' => '2028-10-26',
                'length_minutes' => 135,
                'director_id' => $directorId('Carlos Reyes'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Sci-Fi', 'Action'],
                'image' => '/images/procrastinator/movies/procrastinator-8.jpg',
            ],

            // The Devil Wears Sweatpants (new franchise)
            [
                'title' => 'The Devil Wears Sweatpants',
                'description' => 'Silver hair. Silk sweatpants. Designer sunglasses in a messy home office. She has not worn hard pants in four years. Fashion will never recover.',
                'rating' => 8.0,
                'price' => 12.99,
                'release_date' => '2018-09-07',
                'length_minutes' => 110,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama', 'Romance'],
                'image' => '/images/devil/movies/devil-sweatpants-1.jpg',
            ],
            [
                'title' => 'The Devil Wears Sweatpants 2: The Muted Mic',
                'description' => 'She is on the video call. She is speaking. The MUTED icon glows red directly over her mouth. The assistant does not tell her. The assistant values their life.',
                'rating' => 7.8,
                'price' => 11.99,
                'release_date' => '2020-10-16',
                'length_minutes' => 104,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama', 'Romance'],
                'image' => '/images/devil/movies/devil-sweatpants-2.jpg',
            ],
            [
                'title' => "The Devil Wears Sweatpants 3: The 'Hard Pants' Rebellion",
                'description' => 'Someone brought jeans into the building. She holds them with two fingers. The assistant trembles in the background. This will not be forgotten.',
                'rating' => 7.5,
                'price' => 10.99,
                'release_date' => '2021-06-25',
                'length_minutes' => 98,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama'],
                'image' => '/images/devil/movies/devil-sweatpants-3.jpg',
            ],
            [
                'title' => 'The Devil Wears Sweatpants 4: Camera Off',
                'description' => 'A black square. A white camera-off icon. Faux fur in every direction. She is there. She is watching. The camera is simply off.',
                'rating' => 7.2,
                'price' => 10.99,
                'release_date' => '2022-03-04',
                'length_minutes' => 95,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy'],
                'image' => '/images/devil/movies/devil-sweatpants-4.jpg',
            ],
            [
                'title' => 'The Devil Wears Sweatpants 5: The Ugg Boot Ultimatum',
                'description' => 'Her plush boots rest on a merger contract worth forty-seven million dollars. Sign or do not sign. Either way, the boots stay on the contract.',
                'rating' => 7.9,
                'price' => 11.99,
                'release_date' => '2023-11-10',
                'length_minutes' => 102,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama', 'Thriller'],
                'image' => '/images/devil/movies/devil-sweatpants-5.jpg',
            ],
            [
                'title' => 'The Devil Wears Sweatpants 6: Reply All',
                'description' => 'Someone pressed Reply All. The emails are physical now. They are burying her. This is a horror film dressed as a comedy, and she deserves every inch of it.',
                'rating' => 8.1,
                'price' => 12.99,
                'release_date' => '2024-08-23',
                'length_minutes' => 108,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama'],
                'image' => '/images/devil/movies/devil-sweatpants-6.jpg',
            ],
            [
                'title' => 'The Devil Wears Sweatpants 7: The Standing Desk',
                'description' => 'She purchased the standing desk. She is attempting to use the standing desk. Her legs are shaking. Fashion demands sacrifice.',
                'rating' => 7.6,
                'price' => 11.99,
                'release_date' => '2025-05-30',
                'length_minutes' => 100,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama'],
                'image' => '/images/devil/movies/devil-sweatpants-7.jpg',
            ],
            [
                'title' => 'The Devil Wears Sweatpants 8: Return to Office (Denied)',
                'description' => 'A gleaming corporate tower. A gold card taped to the front door. It reads: NO. She has not elaborated. She will not elaborate. The card is embossed.',
                'rating' => 8.4,
                'price' => 13.99,
                'release_date' => '2026-03-08',
                'length_minutes' => 112,
                'director_id' => $directorId('Priya Sharma'),
                'studio_id' => $studioMmId,
                'language_id' => $englishId,
                'genres' => ['Comedy', 'Drama', 'Romance'],
                'image' => '/images/devil/movies/devil-sweatpants-8.jpg',
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
        $hallCId = DB::table('halls')->where('name', 'Hall C')->value('id');

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

        // Dr. Normal sequels
        $slotDefs[] = ['title' => 'Dr. Normal 2: Just Another Day',                          'hall' => $hallAId, 'starts_at' => '2026-05-16 14:00:00', 'length' => 98];
        $slotDefs[] = ['title' => 'Dr. Normal 2: Just Another Day',                          'hall' => $hallCId, 'starts_at' => '2026-05-19 16:30:00', 'length' => 98];
        $slotDefs[] = ['title' => 'Dr. Normal 3: The Routine Checkup',                       'hall' => $hallCId, 'starts_at' => '2026-05-17 11:00:00', 'length' => 104];
        $slotDefs[] = ['title' => 'Dr. Normal 3: The Routine Checkup',                       'hall' => $hallAId, 'starts_at' => '2026-05-20 18:00:00', 'length' => 104];
        $slotDefs[] = ['title' => 'Dr. Normal 4: Mildly Inconvenienced',                     'hall' => $hallAId, 'starts_at' => '2026-05-18 15:30:00', 'length' => 96];
        $slotDefs[] = ['title' => 'Dr. Normal 4: Mildly Inconvenienced',                     'hall' => $hallCId, 'starts_at' => '2026-05-22 13:00:00', 'length' => 96];
        $slotDefs[] = ['title' => 'Dr. Normal 5: The Multiverse of Average',                 'hall' => $hallCId, 'starts_at' => '2026-05-19 20:00:00', 'length' => 112];
        $slotDefs[] = ['title' => 'Dr. Normal 5: The Multiverse of Average',                 'hall' => $hallAId, 'starts_at' => '2026-05-23 11:30:00', 'length' => 112];
        $slotDefs[] = ['title' => 'Dr. Normal: Origins (He Was Always Like This)',            'hall' => $hallAId, 'starts_at' => '2026-05-21 13:00:00', 'length' => 88];
        $slotDefs[] = ['title' => 'Dr. Normal: Origins (He Was Always Like This)',            'hall' => $hallCId, 'starts_at' => '2026-05-25 17:00:00', 'length' => 88];
        // Gollum sequels
        $slotDefs[] = ['title' => 'Gollum 2: I Lost It Again',                               'hall' => $hallBId, 'starts_at' => '2026-05-16 13:00:00', 'length' => 118];
        $slotDefs[] = ['title' => 'Gollum 2: I Lost It Again',                               'hall' => $hallCId, 'starts_at' => '2026-05-20 15:00:00', 'length' => 118];
        $slotDefs[] = ['title' => 'Gollum 3: The Pawn Shop Returns',                         'hall' => $hallCId, 'starts_at' => '2026-05-17 16:00:00', 'length' => 110];
        $slotDefs[] = ['title' => 'Gollum 3: The Pawn Shop Returns',                         'hall' => $hallBId, 'starts_at' => '2026-05-21 19:00:00', 'length' => 110];
        $slotDefs[] = ['title' => 'Gollum 4: The Fellowship of the Bling',                   'hall' => $hallBId, 'starts_at' => '2026-05-18 11:00:00', 'length' => 124];
        $slotDefs[] = ['title' => 'Gollum 4: The Fellowship of the Bling',                   'hall' => $hallCId, 'starts_at' => '2026-05-23 14:00:00', 'length' => 124];
        $slotDefs[] = ['title' => 'Gollum 5: The Two Pigeons',                               'hall' => $hallCId, 'starts_at' => '2026-05-20 18:30:00', 'length' => 98];
        $slotDefs[] = ['title' => 'Gollum 5: The Two Pigeons',                               'hall' => $hallBId, 'starts_at' => '2026-05-24 12:00:00', 'length' => 98];
        $slotDefs[] = ["title" => "Gollum 6: Return of the King's Cousin",                   'hall' => $hallBId, 'starts_at' => '2026-05-22 20:00:00', 'length' => 130];
        $slotDefs[] = ["title" => "Gollum 6: Return of the King's Cousin",                   'hall' => $hallCId, 'starts_at' => '2026-05-26 16:30:00', 'length' => 130];
        // Hiding Nemo sequels
        $slotDefs[] = ["title" => "Hiding Nemo 2: He's Still There",                         'hall' => $hallAId, 'starts_at' => '2026-05-17 12:00:00', 'length' => 95];
        $slotDefs[] = ["title" => "Hiding Nemo 2: He's Still There",                         'hall' => $hallBId, 'starts_at' => '2026-05-22 14:30:00', 'length' => 95];
        $slotDefs[] = ['title' => 'Hiding Dory (She Forgot Where She Hid)',                  'hall' => $hallBId, 'starts_at' => '2026-05-18 17:00:00', 'length' => 100];
        $slotDefs[] = ['title' => 'Hiding Dory (She Forgot Where She Hid)',                  'hall' => $hallAId, 'starts_at' => '2026-05-24 11:00:00', 'length' => 100];
        $slotDefs[] = ['title' => 'Hiding Nemo 4: The Witness Protection Program',           'hall' => $hallAId, 'starts_at' => '2026-05-19 19:30:00', 'length' => 102];
        $slotDefs[] = ['title' => 'Hiding Nemo 4: The Witness Protection Program',           'hall' => $hallBId, 'starts_at' => '2026-05-26 15:00:00', 'length' => 102];
        $slotDefs[] = ['title' => 'Hiding Nemo 5: Found Him (Just Kidding)',                 'hall' => $hallBId, 'starts_at' => '2026-05-21 10:30:00', 'length' => 88];
        $slotDefs[] = ['title' => 'Hiding Nemo 5: Found Him (Just Kidding)',                 'hall' => $hallAId, 'starts_at' => '2026-05-27 13:30:00', 'length' => 88];
        // Mission: Possible sequels
        $slotDefs[] = ['title' => 'Mission: Possible 2: Actually Pretty Easy',               'hall' => $hallCId, 'starts_at' => '2026-05-17 14:30:00', 'length' => 102];
        $slotDefs[] = ['title' => 'Mission: Possible 2: Actually Pretty Easy',               'hall' => $hallBId, 'starts_at' => '2026-05-23 11:00:00', 'length' => 102];
        $slotDefs[] = ['title' => 'Mission: Possible 3: A Minor Setback',                    'hall' => $hallBId, 'starts_at' => '2026-05-18 20:00:00', 'length' => 106];
        $slotDefs[] = ['title' => 'Mission: Possible 3: A Minor Setback',                    'hall' => $hallCId, 'starts_at' => '2026-05-25 15:30:00', 'length' => 106];
        $slotDefs[] = ['title' => 'Mission: Possible 4: Ghost Protocol (He Just Ignored My Texts)', 'hall' => $hallCId, 'starts_at' => '2026-05-19 12:30:00', 'length' => 113];
        $slotDefs[] = ['title' => 'Mission: Possible 4: Ghost Protocol (He Just Ignored My Texts)', 'hall' => $hallBId, 'starts_at' => '2026-05-27 18:00:00', 'length' => 113];
        $slotDefs[] = ['title' => 'Mission: Possible 5: Rogue Nation (They Forgot To Pay Taxes)',   'hall' => $hallBId, 'starts_at' => '2026-05-20 17:00:00', 'length' => 108];
        $slotDefs[] = ['title' => 'Mission: Possible 5: Rogue Nation (They Forgot To Pay Taxes)',   'hall' => $hallCId, 'starts_at' => '2026-05-29 12:00:00', 'length' => 108];
        $slotDefs[] = ['title' => 'Mission: Possible 6: Fallout (We Dropped A Plate)',       'hall' => $hallCId, 'starts_at' => '2026-05-21 19:30:00', 'length' => 120];
        $slotDefs[] = ['title' => 'Mission: Possible 6: Fallout (We Dropped A Plate)',       'hall' => $hallBId, 'starts_at' => '2026-05-30 14:00:00', 'length' => 120];
        $slotDefs[] = ['title' => 'Mission: Possible 7: Dead Reckoning (Using A Calculator)', 'hall' => $hallBId, 'starts_at' => '2026-05-23 15:00:00', 'length' => 116];
        $slotDefs[] = ['title' => 'Mission: Possible 7: Dead Reckoning (Using A Calculator)', 'hall' => $hallCId, 'starts_at' => '2026-06-01 11:30:00', 'length' => 116];
        $slotDefs[] = ['title' => 'Mission: Possible 8: The Final Checkmark',                'hall' => $hallCId, 'starts_at' => '2026-05-25 20:00:00', 'length' => 125];
        $slotDefs[] = ['title' => 'Mission: Possible 8: The Final Checkmark',                'hall' => $hallBId, 'starts_at' => '2026-06-03 17:00:00', 'length' => 125];
        // SuperGrandpa sequels
        $slotDefs[] = ['title' => 'SuperGrandpa 2: The Search for the Dentures',             'hall' => $hallAId, 'starts_at' => '2026-05-18 13:30:00', 'length' => 112];
        $slotDefs[] = ['title' => 'SuperGrandpa 2: The Search for the Dentures',             'hall' => $hallCId, 'starts_at' => '2026-05-25 19:00:00', 'length' => 112];
        $slotDefs[] = ['title' => 'SuperGrandpa 3: Asleep at 4 PM',                          'hall' => $hallCId, 'starts_at' => '2026-05-20 11:00:00', 'length' => 96];
        $slotDefs[] = ['title' => 'SuperGrandpa 3: Asleep at 4 PM',                          'hall' => $hallAId, 'starts_at' => '2026-05-27 16:00:00', 'length' => 96];
        $slotDefs[] = ['title' => 'SuperGrandpa 4: Back in My Day',                          'hall' => $hallAId, 'starts_at' => '2026-05-22 17:30:00', 'length' => 108];
        $slotDefs[] = ['title' => 'SuperGrandpa 4: Back in My Day',                          'hall' => $hallCId, 'starts_at' => '2026-05-29 13:30:00', 'length' => 108];
        $slotDefs[] = ['title' => 'SuperGrandpa vs. The Remote Control',                     'hall' => $hallCId, 'starts_at' => '2026-05-24 14:00:00', 'length' => 118];
        $slotDefs[] = ['title' => 'SuperGrandpa vs. The Remote Control',                     'hall' => $hallAId, 'starts_at' => '2026-05-31 11:00:00', 'length' => 118];
        // Blue Bulk sequels
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 2: Slightly Annoyed',              'hall' => $hallBId, 'starts_at' => '2026-05-18 15:00:00', 'length' => 118];
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 2: Slightly Annoyed',              'hall' => $hallCId, 'starts_at' => '2026-05-26 12:30:00', 'length' => 118];
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 3: He Needs a Nap',                'hall' => $hallCId, 'starts_at' => '2026-05-20 20:00:00', 'length' => 122];
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 3: He Needs a Nap',                'hall' => $hallBId, 'starts_at' => '2026-05-28 17:00:00', 'length' => 122];
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 4: The Grocery Store Meltdown',    'hall' => $hallBId, 'starts_at' => '2026-05-22 12:00:00', 'length' => 115];
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 4: The Grocery Store Meltdown',    'hall' => $hallCId, 'starts_at' => '2026-05-30 15:30:00', 'length' => 115];
        $slotDefs[] = ["title" => "The Ordinary Blue Bulk 5: Planet Bulk (It's Just a Small Island)", 'hall' => $hallCId, 'starts_at' => '2026-05-24 18:00:00', 'length' => 128];
        $slotDefs[] = ["title" => "The Ordinary Blue Bulk 5: Planet Bulk (It's Just a Small Island)", 'hall' => $hallBId, 'starts_at' => '2026-06-01 13:00:00', 'length' => 128];
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 6: The Mildly Frustrating Traffic Jam', 'hall' => $hallBId, 'starts_at' => '2026-05-26 19:30:00', 'length' => 134];
        $slotDefs[] = ['title' => 'The Ordinary Blue Bulk 6: The Mildly Frustrating Traffic Jam', 'hall' => $hallCId, 'starts_at' => '2026-06-04 11:00:00', 'length' => 134];
        // Squirrel's Revenge sequels
        $slotDefs[] = ["title" => "The Squirrel's Revenge 2: For the Acorns",                'hall' => $hallAId, 'starts_at' => '2026-05-19 11:30:00', 'length' => 98];
        $slotDefs[] = ["title" => "The Squirrel's Revenge 2: For the Acorns",                'hall' => $hallBId, 'starts_at' => '2026-05-27 16:30:00', 'length' => 98];
        $slotDefs[] = ["title" => "The Squirrel's Revenge 3: The Bird Feeder Heist",         'hall' => $hallBId, 'starts_at' => '2026-05-21 14:00:00', 'length' => 104];
        $slotDefs[] = ["title" => "The Squirrel's Revenge 3: The Bird Feeder Heist",         'hall' => $hallAId, 'starts_at' => '2026-05-29 18:30:00', 'length' => 104];
        $slotDefs[] = ["title" => "The Squirrel's Revenge 4: Barking Up the Wrong Tree",     'hall' => $hallAId, 'starts_at' => '2026-05-23 19:00:00', 'length' => 96];
        $slotDefs[] = ["title" => "The Squirrel's Revenge 4: Barking Up the Wrong Tree",     'hall' => $hallBId, 'starts_at' => '2026-06-02 12:00:00', 'length' => 96];
        $slotDefs[] = ["title" => "The Squirrel's Revenge 5: Winter is Coming (Time to Hibernate)", 'hall' => $hallBId, 'starts_at' => '2026-05-25 11:00:00', 'length' => 102];
        $slotDefs[] = ["title" => "The Squirrel's Revenge 5: Winter is Coming (Time to Hibernate)", 'hall' => $hallAId, 'starts_at' => '2026-06-04 15:00:00', 'length' => 102];
        // The Godmother
        $slotDefs[] = ['title' => 'The Godmother',                                           'hall' => $hallAId, 'starts_at' => '2026-05-21 16:00:00', 'length' => 142];
        $slotDefs[] = ['title' => 'The Godmother',                                           'hall' => $hallBId, 'starts_at' => '2026-05-31 14:30:00', 'length' => 142];
        $slotDefs[] = ['title' => 'The Godmother Part II: The Guilt Trip',                   'hall' => $hallBId, 'starts_at' => '2026-05-22 19:30:00', 'length' => 148];
        $slotDefs[] = ['title' => 'The Godmother Part II: The Guilt Trip',                   'hall' => $hallAId, 'starts_at' => '2026-06-03 16:00:00', 'length' => 148];
        $slotDefs[] = ['title' => 'The Godmother Part III: The Leftovers',                   'hall' => $hallAId, 'starts_at' => '2026-05-23 13:30:00', 'length' => 132];
        $slotDefs[] = ['title' => 'The Godmother Part III: The Leftovers',                   'hall' => $hallBId, 'starts_at' => '2026-06-06 11:00:00', 'length' => 132];
        $slotDefs[] = ['title' => 'The Godmother 4: Family Meeting',                         'hall' => $hallBId, 'starts_at' => '2026-05-25 16:00:00', 'length' => 138];
        $slotDefs[] = ['title' => 'The Godmother 4: Family Meeting',                         'hall' => $hallAId, 'starts_at' => '2026-06-08 19:00:00', 'length' => 138];
        $slotDefs[] = ['title' => 'The Godmother 5: The Favor',                              'hall' => $hallAId, 'starts_at' => '2026-05-27 20:00:00', 'length' => 135];
        $slotDefs[] = ['title' => 'The Godmother 5: The Favor',                              'hall' => $hallBId, 'starts_at' => '2026-06-10 14:30:00', 'length' => 135];
        $slotDefs[] = ["title" => "The Godmother 6: Omertà (Code of Silence)",               'hall' => $hallBId, 'starts_at' => '2026-05-29 12:30:00', 'length' => 130];
        $slotDefs[] = ["title" => "The Godmother 6: Omertà (Code of Silence)",               'hall' => $hallAId, 'starts_at' => '2026-06-12 17:00:00', 'length' => 130];
        $slotDefs[] = ['title' => 'The Godmother 7: The Mattresses',                         'hall' => $hallAId, 'starts_at' => '2026-05-31 15:00:00', 'length' => 126];
        $slotDefs[] = ['title' => 'The Godmother 7: The Mattresses',                         'hall' => $hallBId, 'starts_at' => '2026-06-14 13:00:00', 'length' => 126];
        $slotDefs[] = ['title' => 'The Godmother 8: The Final Blessing',                     'hall' => $hallBId, 'starts_at' => '2026-06-02 18:00:00', 'length' => 152];
        $slotDefs[] = ['title' => 'The Godmother 8: The Final Blessing',                     'hall' => $hallAId, 'starts_at' => '2026-06-16 20:00:00', 'length' => 152];
        // The Procrastinator
        $slotDefs[] = ['title' => 'The Procrastinator',                                      'hall' => $hallCId, 'starts_at' => '2026-05-22 11:00:00', 'length' => 118];
        $slotDefs[] = ['title' => 'The Procrastinator',                                      'hall' => $hallBId, 'starts_at' => '2026-06-02 15:30:00', 'length' => 118];
        $slotDefs[] = ['title' => 'The Procrastinator 2: Judgment Day (Eventually)',         'hall' => $hallBId, 'starts_at' => '2026-05-24 16:00:00', 'length' => 112];
        $slotDefs[] = ['title' => 'The Procrastinator 2: Judgment Day (Eventually)',         'hall' => $hallCId, 'starts_at' => '2026-06-05 12:00:00', 'length' => 112];
        $slotDefs[] = ['title' => 'The Procrastinator 3: Rise of the Machines (Tomorrow)',   'hall' => $hallCId, 'starts_at' => '2026-05-25 19:30:00', 'length' => 108];
        $slotDefs[] = ['title' => 'The Procrastinator 3: Rise of the Machines (Tomorrow)',   'hall' => $hallBId, 'starts_at' => '2026-06-07 14:00:00', 'length' => 108];
        $slotDefs[] = ['title' => 'The Procrastinator 4: Salvation (Pending)',               'hall' => $hallBId, 'starts_at' => '2026-05-27 13:00:00', 'length' => 120];
        $slotDefs[] = ['title' => 'The Procrastinator 4: Salvation (Pending)',               'hall' => $hallCId, 'starts_at' => '2026-06-09 17:30:00', 'length' => 120];
        $slotDefs[] = ['title' => 'The Procrastinator 5: Genisys (Loading...)',              'hall' => $hallCId, 'starts_at' => '2026-05-29 15:00:00', 'length' => 116];
        $slotDefs[] = ['title' => 'The Procrastinator 5: Genisys (Loading...)',              'hall' => $hallBId, 'starts_at' => '2026-06-11 11:30:00', 'length' => 116];
        $slotDefs[] = ['title' => 'The Procrastinator 6: Dark Fate (In Drafts)',             'hall' => $hallBId, 'starts_at' => '2026-05-31 18:30:00', 'length' => 124];
        $slotDefs[] = ['title' => 'The Procrastinator 6: Dark Fate (In Drafts)',             'hall' => $hallCId, 'starts_at' => '2026-06-13 16:00:00', 'length' => 124];
        $slotDefs[] = ["title" => "The Procrastinator 7: I'll Be Back (In a Minute)",        'hall' => $hallCId, 'starts_at' => '2026-06-01 12:00:00', 'length' => 110];
        $slotDefs[] = ["title" => "The Procrastinator 7: I'll Be Back (In a Minute)",        'hall' => $hallBId, 'starts_at' => '2026-06-15 19:00:00', 'length' => 110];
        $slotDefs[] = ['title' => 'The Procrastinator 8: The Final Deadline',                'hall' => $hallBId, 'starts_at' => '2026-06-03 20:00:00', 'length' => 135];
        $slotDefs[] = ['title' => 'The Procrastinator 8: The Final Deadline',                'hall' => $hallCId, 'starts_at' => '2026-06-17 14:30:00', 'length' => 135];
        // The Devil Wears Sweatpants
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants',                              'hall' => $hallAId, 'starts_at' => '2026-05-23 12:30:00', 'length' => 110];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants',                              'hall' => $hallCId, 'starts_at' => '2026-06-04 17:00:00', 'length' => 110];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 2: The Muted Mic',             'hall' => $hallCId, 'starts_at' => '2026-05-25 14:00:00', 'length' => 104];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 2: The Muted Mic',             'hall' => $hallAId, 'starts_at' => '2026-06-07 11:30:00', 'length' => 104];
        $slotDefs[] = ["title" => "The Devil Wears Sweatpants 3: The 'Hard Pants' Rebellion", 'hall' => $hallAId, 'starts_at' => '2026-05-26 18:30:00', 'length' => 98];
        $slotDefs[] = ["title" => "The Devil Wears Sweatpants 3: The 'Hard Pants' Rebellion", 'hall' => $hallCId, 'starts_at' => '2026-06-09 15:00:00', 'length' => 98];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 4: Camera Off',                'hall' => $hallCId, 'starts_at' => '2026-05-28 11:30:00', 'length' => 95];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 4: Camera Off',                'hall' => $hallAId, 'starts_at' => '2026-06-11 19:30:00', 'length' => 95];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 5: The Ugg Boot Ultimatum',    'hall' => $hallAId, 'starts_at' => '2026-05-30 16:00:00', 'length' => 102];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 5: The Ugg Boot Ultimatum',    'hall' => $hallCId, 'starts_at' => '2026-06-13 13:00:00', 'length' => 102];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 6: Reply All',                 'hall' => $hallCId, 'starts_at' => '2026-06-01 19:00:00', 'length' => 108];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 6: Reply All',                 'hall' => $hallAId, 'starts_at' => '2026-06-15 14:30:00', 'length' => 108];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 7: The Standing Desk',         'hall' => $hallAId, 'starts_at' => '2026-06-03 13:30:00', 'length' => 100];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 7: The Standing Desk',         'hall' => $hallCId, 'starts_at' => '2026-06-17 11:00:00', 'length' => 100];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 8: Return to Office (Denied)', 'hall' => $hallCId, 'starts_at' => '2026-06-05 17:30:00', 'length' => 112];
        $slotDefs[] = ['title' => 'The Devil Wears Sweatpants 8: Return to Office (Denied)', 'hall' => $hallAId, 'starts_at' => '2026-06-19 18:00:00', 'length' => 112];

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
            ['name' => 'SuperGrandpa Cape Pin', 'price' => 7.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 80, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_accessory.jpg'],
            ['name' => 'SuperGrandpa Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 50, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_figurine.jpg'],
            ['name' => 'SuperGrandpa Deluxe Figurine', 'price' => 14.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_figurine_2.png'],
            ['name' => 'SuperGrandpa Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 35, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_plush.jpg'],
            ['name' => 'SuperGrandpa Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 100, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_poster.png'],
            ['name' => 'SuperGrandpa Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_print.jpg'],
            ['name' => 'SuperGrandpa Prop Replica', 'price' => 29.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_prop.jpg'],
            ['name' => 'SuperGrandpa Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('SuperGrandpa'), 'quantity' => 90, 'status_id' => $availableId, 'image' => '/images/grandpa/souvenirs/grandpa_stickers.jpg'],

            // gollum stela the rng
            ["name" => "Gollum's Ring", 'price' => 9.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/gollum/souvenirs/gollum_accessory.jpg'],
            ['name' => 'Gollum Figurine', 'price' => 11.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/gollum/souvenirs/gollum_figurine.jpg'],
            ['name' => 'Gollum Plush Toy', 'price' => 14.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/gollum/souvenirs/gollum_plush.jpg'],
            ['name' => 'Gollum Movie Poster', 'price' => 6.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 65, 'status_id' => $availableId, 'image' => '/images/gollum/souvenirs/gollum_poster.jpg'],
            ['name' => 'Gollum Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 55, 'status_id' => $availableId, 'image' => '/images/gollum/souvenirs/gollum_print.jpg'],
            ['name' => 'Gollum Collector Statue', 'price' => 49.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 10, 'status_id' => $availableId, 'image' => '/images/gollum/souvenirs/gollum_prop.jpg'],
            ['name' => 'Gollum Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Gollum: Steal The Ring'), 'quantity' => 75, 'status_id' => $availableId, 'image' => '/images/gollum/souvenirs/gollum_stickers.jpg'],

            // mission possible
            ['name' => 'Mission: Possible Spy Gadget', 'price' => 12.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 40, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_accessory.jpg'],
            ['name' => 'Mission: Possible Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 35, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_figurine.jpg'],
            ['name' => 'Mission: Possible Plush Toy', 'price' => 11.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_plush.jpg'],
            ['name' => 'Mission: Possible Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 70, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_poster.jpg'],
            ['name' => 'Mission: Possible Art Print', 'price' => 7.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_print.jpg'],
            ['name' => 'Mission: Possible Special Print', 'price' => 9.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_print_2.png'],
            ['name' => 'Mission: Possible Gadget Set', 'price' => 34.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 12, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_prop.jpg'],
            ['name' => 'Mission: Possible Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Mission: Possible'), 'quantity' => 85, 'status_id' => $availableId, 'image' => '/images/mission/souvenirs/mission_stickers.jpg'],

            // hiding nemo
            ['name' => 'Hiding Nemo Seashell Necklace', 'price' => 7.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 45, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_accessory.jpg'],
            ['name' => 'Hiding Nemo Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_figurine.jpg'],
            ['name' => 'Hiding Nemo Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 40, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_plush.jpg'],
            ['name' => 'Hiding Nemo XL Plush Toy', 'price' => 19.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_plush_2.png'],
            ['name' => 'Hiding Nemo Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 75, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_poster.jpg'],
            ['name' => 'Hiding Nemo Art Print', 'price' => 7.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 50, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_print.jpg'],
            ['name' => 'Hiding Nemo Prop Replica', 'price' => 24.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 18, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_prop.jpg'],
            ['name' => 'Hiding Nemo Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Hiding Nemo'), 'quantity' => 90, 'status_id' => $availableId, 'image' => '/images/nemo/souvenirs/nemo_stickers.jpg'],

            // blue bilk
            ['name' => 'Blue Bulk Power Band', 'price' => 9.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 40, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_accessory.jpg'],
            ['name' => 'Blue Bulk Figurine', 'price' => 11.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_figurine.jpg'],
            ['name' => 'Blue Bulk Plush Toy', 'price' => 14.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_plush.jpg'],
            ['name' => 'Blue Bulk Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_poster.jpg'],
            ['name' => 'Blue Bulk Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 45, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_print.jpg'],
            ['name' => 'Blue Bulk Prop Replica', 'price' => 29.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_prop.jpg'],
            ['name' => 'Blue Bulk Deluxe Prop Replica', 'price' => 44.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 8, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_prop_2.png'],
            ['name' => 'Blue Bulk Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('The Ordinary Blue Bulk'), 'quantity' => 80, 'status_id' => $availableId, 'image' => '/images/bulk/souvenirs/bulk_stickers.jpg'],

            // squirrels revenge
            ['name' => 'Mad Squirrel Acorn Keychain', 'price' => 6.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 70, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_accessory.jpg'],
            ['name' => 'Mad Squirrel Figurine', 'price' => 9.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 30, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_figurine.jpg'],
            ['name' => 'Mad Squirrel Deluxe Figurine', 'price' => 14.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 15, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_figurine_2.png'],
            ['name' => 'Mad Squirrel Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 25, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_plush.jpg'],
            ["name" => "Squirrel's Revenge Movie Poster", 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 80, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_poster.png'],
            ["name" => "Squirrel's Revenge Art Print", 'price' => 7.99, 'category_id' => $printId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 55, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_print.jpg'],
            ['name' => 'Mad Squirrel Prop Replica', 'price' => 24.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 18, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_prop.jpg'],
            ["name" => "Squirrel's Revenge Sticker Pack", 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId("The Squirrel's Revenge"), 'quantity' => 100, 'status_id' => $availableId, 'image' => '/images/squirrel/souvenirs/squirrel_stickers.jpg'],

            // dr normal
            ['name' => 'Dr. Normal Lab Badge', 'price' => 7.99, 'category_id' => $accessoryId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 60, 'status_id' => $availableId, 'image' => '/images/dr_normal/souvenirs/dr_normal_accessory.jpg'],
            ['name' => 'Dr. Normal Figurine', 'price' => 11.99, 'category_id' => $figurineId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 35, 'status_id' => $availableId, 'image' => '/images/dr_normal/souvenirs/dr_normal_figurine.jpg'],
            ['name' => 'Dr. Normal Plush Toy', 'price' => 12.99, 'category_id' => $plushId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 20, 'status_id' => $availableId, 'image' => '/images/dr_normal/souvenirs/dr_normal_plush.jpg'],
            ['name' => 'Dr. Normal Movie Poster', 'price' => 5.99, 'category_id' => $posterId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 70, 'status_id' => $availableId, 'image' => '/images/dr_normal/souvenirs/dr_normal_poster.jpg'],
            ['name' => 'Dr. Normal Art Print', 'price' => 8.99, 'category_id' => $printId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 45, 'status_id' => $availableId, 'image' => '/images/dr_normal/souvenirs/dr_normal_print.jpg'],
            ['name' => 'Dr. Normal Lab Equipment', 'price' => 39.99, 'category_id' => $propReplicaId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 10, 'status_id' => $availableId, 'image' => '/images/dr_normal/souvenirs/dr_normal_prop.jpg'],
            ['name' => 'Dr. Normal Sticker Pack', 'price' => 3.99, 'category_id' => $stickerPackId, 'movie_id' => $getMovieId('Dr. Normal'), 'quantity' => 75, 'status_id' => $availableId, 'image' => '/images/dr_normal/souvenirs/dr_normal_stickers.jpg'],
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

        $secondaryImages = [
            // Dr. Normal
            ['title' => 'Dr. Normal',                                              'url' => '/images/dr_normal/movies/secondary/dr-normal-1.jpg'],
            ['title' => 'Dr. Normal 2: Just Another Day',                         'url' => '/images/dr_normal/movies/secondary/dr-normal-2.jpg'],
            ['title' => 'Dr. Normal 3: The Routine Checkup',                      'url' => '/images/dr_normal/movies/secondary/dr-normal-3.jpg'],
            ['title' => 'Dr. Normal 4: Mildly Inconvenienced',                    'url' => '/images/dr_normal/movies/secondary/dr-normal-4.jpg'],
            ['title' => 'Dr. Normal 5: The Multiverse of Average',                'url' => '/images/dr_normal/movies/secondary/dr-normal-5.jpg'],
            ['title' => 'Dr. Normal: Origins (He Was Always Like This)',           'url' => '/images/dr_normal/movies/secondary/dr-normal-origins.jpg'],
            // Gollum
            ['title' => 'Gollum: Steal The Ring',                                 'url' => '/images/gollum/movies/secondary/gollum-1.jpg'],
            ['title' => 'Gollum 2: I Lost It Again',                              'url' => '/images/gollum/movies/secondary/gollum-2.jpg'],
            ['title' => 'Gollum 3: The Pawn Shop Returns',                        'url' => '/images/gollum/movies/secondary/gollum-3.jpg'],
            ['title' => 'Gollum 4: The Fellowship of the Bling',                  'url' => '/images/gollum/movies/secondary/gollum-4.jpg'],
            ['title' => 'Gollum 5: The Two Pigeons',                              'url' => '/images/gollum/movies/secondary/gollum-5.jpg'],
            ["title" => "Gollum 6: Return of the King's Cousin",                  'url' => '/images/gollum/movies/secondary/gollum-6.jpg'],
            // Hiding Nemo
            ['title' => 'Hiding Nemo',                                            'url' => '/images/nemo/movies/secondary/hiding-nemo-1.jpg'],
            ["title" => "Hiding Nemo 2: He's Still There",                        'url' => '/images/nemo/movies/secondary/hiding-nemo-2.jpg'],
            ['title' => 'Hiding Dory (She Forgot Where She Hid)',                 'url' => '/images/nemo/movies/secondary/hiding-dory.jpg'],
            ['title' => 'Hiding Nemo 4: The Witness Protection Program',          'url' => '/images/nemo/movies/secondary/hiding-nemo-4.jpg'],
            ['title' => 'Hiding Nemo 5: Found Him (Just Kidding)',                'url' => '/images/nemo/movies/secondary/hiding-nemo-5.jpg'],
            // Mission: Possible
            ['title' => 'Mission: Possible',                                      'url' => '/images/mission/movies/secondary/mission-possible-1.jpg'],
            ['title' => 'Mission: Possible 2: Actually Pretty Easy',              'url' => '/images/mission/movies/secondary/mission-possible-2.jpg'],
            ['title' => 'Mission: Possible 3: A Minor Setback',                   'url' => '/images/mission/movies/secondary/mission-possible-3.jpg'],
            ['title' => 'Mission: Possible 4: Ghost Protocol (He Just Ignored My Texts)', 'url' => '/images/mission/movies/secondary/mission-possible-4.jpg'],
            ['title' => 'Mission: Possible 5: Rogue Nation (They Forgot To Pay Taxes)',   'url' => '/images/mission/movies/secondary/mission-possible-5.jpg'],
            ['title' => 'Mission: Possible 6: Fallout (We Dropped A Plate)',      'url' => '/images/mission/movies/secondary/mission-possible-6.jpg'],
            ['title' => 'Mission: Possible 7: Dead Reckoning (Using A Calculator)', 'url' => '/images/mission/movies/secondary/mission-possible-7.jpg'],
            ['title' => 'Mission: Possible 8: The Final Checkmark',               'url' => '/images/mission/movies/secondary/mission-possible-8.jpg'],
            // SuperGrandpa
            ['title' => 'SuperGrandpa',                                           'url' => '/images/grandpa/movies/secondary/supergrandpa-1.jpg'],
            ['title' => 'SuperGrandpa 2: The Search for the Dentures',            'url' => '/images/grandpa/movies/secondary/supergrandpa-2.jpg'],
            ['title' => 'SuperGrandpa 3: Asleep at 4 PM',                         'url' => '/images/grandpa/movies/secondary/supergrandpa-3.jpg'],
            ['title' => 'SuperGrandpa 4: Back in My Day',                         'url' => '/images/grandpa/movies/secondary/supergrandpa-4.jpg'],
            ['title' => 'SuperGrandpa vs. The Remote Control',                    'url' => '/images/grandpa/movies/secondary/supergrandpa-remote.jpg'],
            // Blue Bulk
            ['title' => 'The Ordinary Blue Bulk',                                 'url' => '/images/bulk/movies/secondary/blue-bulk-1.jpg'],
            ['title' => 'The Ordinary Blue Bulk 2: Slightly Annoyed',             'url' => '/images/bulk/movies/secondary/blue-bulk-2.jpg'],
            ['title' => 'The Ordinary Blue Bulk 3: He Needs a Nap',               'url' => '/images/bulk/movies/secondary/blue-bulk-3.jpg'],
            ['title' => "The Ordinary Blue Bulk 4: The Grocery Store Meltdown",   'url' => '/images/bulk/movies/secondary/blue-bulk-4.jpg'],
            ["title" => "The Ordinary Blue Bulk 5: Planet Bulk (It's Just a Small Island)", 'url' => '/images/bulk/movies/secondary/blue-bulk-5.jpg'],
            ['title' => 'The Ordinary Blue Bulk 6: The Mildly Frustrating Traffic Jam', 'url' => '/images/bulk/movies/secondary/blue-bulk-6.jpg'],
            // Squirrel's Revenge
            ["title" => "The Squirrel's Revenge",                                 'url' => '/images/squirrel/movies/secondary/squirrel-1.jpg'],
            ["title" => "The Squirrel's Revenge 2: For the Acorns",               'url' => '/images/squirrel/movies/secondary/squirrel-2.jpg'],
            ["title" => "The Squirrel's Revenge 3: The Bird Feeder Heist",        'url' => '/images/squirrel/movies/secondary/squirrel-3.jpg'],
            ["title" => "The Squirrel's Revenge 4: Barking Up the Wrong Tree",    'url' => '/images/squirrel/movies/secondary/squirrel-4.jpg'],
            ["title" => "The Squirrel's Revenge 5: Winter is Coming (Time to Hibernate)", 'url' => '/images/squirrel/movies/secondary/squirrel-5.jpg'],
            // The Godmother
            ['title' => 'The Godmother',                                          'url' => '/images/godmother/movies/secondary/godmother-1.jpg'],
            ['title' => 'The Godmother Part II: The Guilt Trip',                  'url' => '/images/godmother/movies/secondary/godmother-2.jpg'],
            ['title' => 'The Godmother Part III: The Leftovers',                  'url' => '/images/godmother/movies/secondary/godmother-3.jpg'],
            ['title' => 'The Godmother 4: Family Meeting',                        'url' => '/images/godmother/movies/secondary/godmother-4.jpg'],
            ['title' => 'The Godmother 5: The Favor',                             'url' => '/images/godmother/movies/secondary/godmother-5.jpg'],
            ["title" => "The Godmother 6: Omertà (Code of Silence)",              'url' => '/images/godmother/movies/secondary/godmother-6.jpg'],
            ['title' => 'The Godmother 7: The Mattresses',                        'url' => '/images/godmother/movies/secondary/godmother-7.jpg'],
            ['title' => 'The Godmother 8: The Final Blessing',                    'url' => '/images/godmother/movies/secondary/godmother-8.jpg'],
            // The Procrastinator
            ['title' => 'The Procrastinator',                                     'url' => '/images/procrastinator/movies/secondary/procrastinator-1.jpg'],
            ['title' => 'The Procrastinator 2: Judgment Day (Eventually)',        'url' => '/images/procrastinator/movies/secondary/procrastinator-2.jpg'],
            ['title' => 'The Procrastinator 3: Rise of the Machines (Tomorrow)',  'url' => '/images/procrastinator/movies/secondary/procrastinator-3.jpg'],
            ['title' => 'The Procrastinator 4: Salvation (Pending)',              'url' => '/images/procrastinator/movies/secondary/procrastinator-4.jpg'],
            ['title' => 'The Procrastinator 5: Genisys (Loading...)',             'url' => '/images/procrastinator/movies/secondary/procrastinator-5.jpg'],
            ['title' => 'The Procrastinator 6: Dark Fate (In Drafts)',            'url' => '/images/procrastinator/movies/secondary/procrastinator-6.jpg'],
            ["title" => "The Procrastinator 7: I'll Be Back (In a Minute)",       'url' => '/images/procrastinator/movies/secondary/procrastinator-7.jpg'],
            ['title' => 'The Procrastinator 8: The Final Deadline',               'url' => '/images/procrastinator/movies/secondary/procrastinator-8.jpg'],
            // The Devil Wears Sweatpants
            ['title' => 'The Devil Wears Sweatpants',                             'url' => '/images/devil/movies/secondary/devil-sweatpants-1.jpg'],
            ['title' => 'The Devil Wears Sweatpants 2: The Muted Mic',            'url' => '/images/devil/movies/secondary/devil-sweatpants-2.jpg'],
            ["title" => "The Devil Wears Sweatpants 3: The 'Hard Pants' Rebellion", 'url' => '/images/devil/movies/secondary/devil-sweatpants-3.jpg'],
            ['title' => 'The Devil Wears Sweatpants 4: Camera Off',               'url' => '/images/devil/movies/secondary/devil-sweatpants-4.jpg'],
            ['title' => 'The Devil Wears Sweatpants 5: The Ugg Boot Ultimatum',   'url' => '/images/devil/movies/secondary/devil-sweatpants-5.jpg'],
            ['title' => 'The Devil Wears Sweatpants 6: Reply All',                'url' => '/images/devil/movies/secondary/devil-sweatpants-6.jpg'],
            ['title' => 'The Devil Wears Sweatpants 7: The Standing Desk',        'url' => '/images/devil/movies/secondary/devil-sweatpants-7.jpg'],
            ['title' => 'The Devil Wears Sweatpants 8: Return to Office (Denied)', 'url' => '/images/devil/movies/secondary/devil-sweatpants-8.jpg'],
        ];

        foreach ($secondaryImages as $entry) {
            $movieId = $getMovieId($entry['title']);
            if ($movieId) {
                DB::table('movie_images')->insert([
                    'movie_id'   => $movieId,
                    'url'        => $entry['url'],
                    'is_primary' => false,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
