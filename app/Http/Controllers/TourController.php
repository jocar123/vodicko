<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\TourUsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TourController extends Controller
{
    public function index(){
        $tours = Tour::all();
        return view('home', ['tours' => $tours]);
    }

    public function usersInTours(Request $request)
    {
        $query = Tour::with('users');

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');

            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%");
            })
            ->orWhereHas('users', function($q2) use ($search) {
                $q2->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
            });
        }

        $tours = $query->paginate(5);
        $currentUser = auth()->user();
        return view('usersInTours', ['tours' => $tours, 'currentUser' => $currentUser]);
    }

    public function showTour($id)
    {
        $tour = Tour::with(['users', 'destinations'])->findOrFail($id);
        $user = auth()->user();

        $isRegistered = false;
        if ($user) {
            $isRegistered = $tour->users()->where('users.user_id', $user->user_id)->exists();
        }


        $isFull = $tour->users->count() >= $tour->capacity;

        return view('tour', [
            'tour' => $tour,
            'isRegistered' => $isRegistered,
            'users' => $tour->users,
            'isFull' => $isFull,
            'isLoggedIn' => $user != null
        ]);
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();
        return redirect()->route('home');
    }


    public function addUserToTour($tourId)
    {
        $user = auth()->user();
        if ($user && !$user->tours()->where('user_tour.tour_id', $tourId)->exists()) {
            $user->tours()->attach($tourId);
        }
        return redirect()->back();
    }

    public function removeUserFromTour($tourId)
    {
        $user = auth()->user();
        if ($user) {
            $user->tours()->detach($tourId);
        }
        return redirect()->back();
    }

    public function create()
    {
        $destinations = Destination::all();
        return view('addTour', ['destinations' => $destinations]);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:45',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'destinations' => 'array',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
        ]);

        $data = $request->only([
            'title', 'price', 'capacity', 'start_date', 'end_date', 'description'
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $data['thumbnail'] = $filename;
        }

        $tour = Tour::create($data);

        if ($request->has('destinations')) {
            $tour->destinations()->attach($request->destinations);
        }

        return redirect()->route('home');
    }

    public function exportUsersInTours()
    {
        return Excel::download(new TourUsersExport, 'users_in_tours.xlsx');
    }

    public function exportUsersInToursPdf()
    {
        $tours = Tour::with('users')->get();

        $pdf = Pdf::loadView('usersInTours_pdf', compact('tours'));
        return $pdf->download('users_in_tours.pdf');
    }


}
