<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $movieFilter = $request->query('movie_id');

        $query = DB::table('schedule_slots')
            ->join('movies', 'schedule_slots.movie_id', '=', 'movies.id')
            ->join('halls', 'schedule_slots.hall_id', '=', 'halls.id')
            ->select(
                'schedule_slots.id',
                'schedule_slots.starts_at',
                'schedule_slots.ends_at',
                'movies.id as movie_id',
                'movies.title as movie_title',
                'halls.id as hall_id',
                'halls.name as hall_name'
            )
            ->orderBy('schedule_slots.starts_at');

        if ($movieFilter) {
            $query->where('schedule_slots.movie_id', $movieFilter);
        }

        $slots   = $query->paginate(20)->withQueryString();
        $movies  = DB::table('movies')->orderBy('title')->get(['id', 'title']);
        $halls   = DB::table('halls')->orderBy('name')->get(['id', 'name']);

        return view('admin.schedule', compact('slots', 'movies', 'halls', 'movieFilter'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'movie_id'   => ['required', 'integer', 'exists:movies,id'],
            'hall_id'    => ['required', 'integer', 'exists:halls,id'],
            'starts_at'  => ['required', 'date', 'after:now'],
        ]);

        $length = (int) DB::table('movies')->where('id', $data['movie_id'])->value('length_minutes');
        $endsAt = Carbon::parse($data['starts_at'])->addMinutes($length + 15);

        DB::table('schedule_slots')->insert([
            'movie_id'  => $data['movie_id'],
            'hall_id'   => $data['hall_id'],
            'starts_at' => Carbon::parse($data['starts_at'])->format('Y-m-d H:i:s'),
            'ends_at'   => $endsAt->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('admin.schedule', array_filter(['movie_id' => $request->query('movie_id')]))
            ->with('status', 'Screening added.');
    }

    public function destroy(int $id): RedirectResponse
    {
        DB::table('schedule_slots')->where('id', $id)->delete();

        return redirect()->back()->with('status', 'Screening deleted.');
    }
}
