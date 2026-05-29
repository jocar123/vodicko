<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function create(){
        return view('addDestination');
    }

        public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        Destination::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('home');
    }
}
