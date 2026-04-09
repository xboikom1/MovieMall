<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class MovieController extends Controller
{
    public function show(string $slug): View
    {
        $movie = Arr::get($this->movies(), $slug);

        abort_unless($movie, 404);

        return view('movie-details', [
            'movie' => $movie,
        ]);
    }


    //Temporary catalog until movies are stored in DB.

    private function movies(): array
    {
        return [
            'supergrandpa' => [
                'slug' => 'supergrandpa',
                'title' => 'SuperGrandpa',
                'poster' => '/images/Supergrandpa.png',
                'genres' => ['Action', 'Adventure', 'Comedy'],
                'synopsis' => 'An extraordinary superhero adventure spanning multiple dimensions. SuperGrandpa must save the world from the Mad Squirrels before time runs out.',
                'rating' => '7.5/10',
                'duration' => '118 min',
                'release_date' => 'February 28, 2026',
                'director' => 'John Smith',
                'language' => 'English',
                'studio' => 'MovieMall Studios',
                'related_souvenirs' => [
                    [
                        'title' => 'SuperGrandpa Figurine',
                        'image' => '/images/SuperGrandpaSouvenir.png',
                        'movie' => 'SuperGrandpa',
                        'type' => 'Figurine',
                        'price' => '9.99EUR',
                    ],
                    [
                        'title' => 'Gollum\'s Ring',
                        'image' => '/images/gollumring.png',
                        'movie' => 'Gollum: Steal The Ring',
                        'type' => 'Accessory',
                        'price' => '9.99EUR',
                    ],
                    [
                        'title' => 'Hiding Nemo Plush Toy',
                        'image' => '/images/plushtoynemo.png',
                        'movie' => 'Hiding Nemo',
                        'type' => 'Plush Toy',
                        'price' => '9.99EUR',
                    ],
                    [
                        'title' => 'Blue Bulk Prop Replica',
                        'image' => '/images/bluebulkpropreplica.png',
                        'movie' => 'The Ordinary Blue Bulk',
                        'type' => 'Prop Replica',
                        'price' => '9.99EUR',
                    ],
                ],
            ],
            'gollum-steal-the-ring' => [
                'slug' => 'gollum-steal-the-ring',
                'title' => 'Gollum: Steal The Ring',
                'poster' => '/images/gollum.png',
                'genres' => ['Adventure', 'Action'],
                'synopsis' => 'A desperate anti-hero embarks on a dangerous quest to reclaim a legendary ring and outsmart enemies from every realm.',
                'rating' => '9.5/10',
                'duration' => '132 min',
                'release_date' => 'October 10, 2028',
                'director' => 'Ava Knight',
                'language' => 'English',
                'studio' => 'MovieMall Studios',
                'related_souvenirs' => [],
            ],
            'mission-possible' => [
                'slug' => 'mission-possible',
                'title' => 'Mission: Possible',
                'poster' => '/images/missionpossible.png',
                'genres' => ['Action', 'Drama'],
                'synopsis' => 'When every plan fails, one team improvises the impossible to stop a global catastrophe.',
                'rating' => '6.5/10',
                'duration' => '109 min',
                'release_date' => 'June 1, 2024',
                'director' => 'Mia Stone',
                'language' => 'English',
                'studio' => 'MovieMall Studios',
                'related_souvenirs' => [],
            ],
            'hiding-nemo' => [
                'slug' => 'hiding-nemo',
                'title' => 'Hiding Nemo',
                'poster' => '/images/hidingnemo.png',
                'genres' => ['Drama', 'Adventure'],
                'synopsis' => 'A heartfelt underwater journey about courage, friendship, and finding your way home.',
                'rating' => '4.5/10',
                'duration' => '103 min',
                'release_date' => 'March 15, 2018',
                'director' => 'Evan Lee',
                'language' => 'English',
                'studio' => 'MovieMall Studios',
                'related_souvenirs' => [],
            ],
            'the-ordinary-blue-bulk' => [
                'slug' => 'the-ordinary-blue-bulk',
                'title' => 'The Ordinary Blue Bulk',
                'poster' => '/images/bluebulk.png',
                'genres' => ['Action', 'Apocalypse', 'Drama'],
                'synopsis' => 'An unlikely hero rises in a collapsing world, balancing brute force with unexpected compassion.',
                'rating' => '9.8/10',
                'duration' => '124 min',
                'release_date' => 'May 12, 2025',
                'director' => 'Noah Grant',
                'language' => 'English',
                'studio' => 'MovieMall Studios',
                'related_souvenirs' => [],
            ],
            'the-squirrels-revenge' => [
                'slug' => 'the-squirrels-revenge',
                'title' => "The Squirrel's Revenge",
                'poster' => '/images/Squirrel.png',
                'genres' => ['Comedy'],
                'synopsis' => 'A mischievous squirrel mastermind turns a quiet city into chaos, forcing unlikely heroes to step up.',
                'rating' => '7.5/10',
                'duration' => '101 min',
                'release_date' => 'August 23, 2020',
                'director' => 'Liam Carter',
                'language' => 'English',
                'studio' => 'MovieMall Studios',
                'related_souvenirs' => [],
            ],
            'dr-normal' => [
                'slug' => 'dr-normal',
                'title' => 'Dr. Normal',
                'poster' => '/images/drnormal.png',
                'genres' => ['Drama', 'Documentary'],
                'synopsis' => 'A brilliant scientist tries to live an ordinary life while confronting the consequences of past experiments.',
                'rating' => '8.2/10',
                'duration' => '116 min',
                'release_date' => 'January 17, 2026',
                'director' => 'Sophie Hall',
                'language' => 'English',
                'studio' => 'MovieMall Studios',
                'related_souvenirs' => [],
            ],
        ];
    }
}

