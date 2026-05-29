<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FilterController extends Controller
{
    public function showHome(Request $request){
        $query = Tour::query();

        if($request->filled('name')){
            $query->where('title', 'like', '%' . $request->name . "%");
        }
        if($request->filled('start')){
            $query->where('start_date', '>=', $request->start);
        }
        if($request->filled('end')){
            $query->where('end_date', '<=', $request->end);
        }

        if($request->filled('sort')){
            switch($request->sort){
                case "price_asc":
                    $query->orderBy('price','asc');
                    break;
                case "capacity_asc":
                    $query->orderBy('capacity','asc');
                    break;
                case "start_date_asc":
                    $query->orderBy('start_date', 'asc');
                    break;
                case "price_desc":
                    $query->orderBy('price','desc');
                    break;
                case "capacity_desc":
                    $query->orderBy('capacity','desc');
                    break;
                case "start_date_desc":
                    $query->orderBy('start_date', 'desc');
                    break;
            }
        }

        $tours = $query->get();
        
        $user = auth()->user();
        $isManager = false;
        if ($user) {
            $isManager = $user->roles()->where('name', 'manager')->exists();
        }
        
        $isAdmin = false;
        if ($user) {
            $isAdmin = $user->roles()->where('name', 'admin')->exists();
        }

        return view('home', [
            'tours' => $tours,
            'isManager' => $isManager,
            'isAdmin' => $isAdmin,
            'currentSort' => $request->get('sort'),
            'homeRoute' => route('home')
        ]);


    }

}