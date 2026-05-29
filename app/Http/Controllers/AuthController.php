<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function showHome(){
        return view('home');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:45',
            'surname'     => 'required|string|max:45',
            'username'    => 'required|string|max:45|unique:users,username',
            'dob'         => 'required|date',
            'email'       => 'required|email|max:45|unique:users,email',
            'phone_number'=> 'required|string|max:45',
            'password'    => 'required|min:5|confirmed',
        ], [
            'name.required' => 'Ime je obavezno.',
            'surname.required' => 'Prezime je obavezno.',
            'username.unique' => 'Korisničko ime već postoji.',
            'email.unique' => 'Email je već registrovan.',
            'email.email' => 'Unesite ispravnu email adresu.',
            'password.confirmed' => 'Lozinke se ne poklapaju.',
            'password.min' => 'Lozinka mora imati najmanje 5 karaktera.'
        ]);

        $user = User::create([
            'name'         => $request->name,
            'surname'      => $request->surname,
            'username'     => $request->username,
            'dob'          => $request->dob,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password_hash'=> Hash::make($request->password),
        ]);

        return redirect()->route('login');
    }

    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return redirect()->route('login')->with('error', 'Neispravno korisničko ime ili lozinka.');
        }

        auth()->login($user);
        Log::info('Ulogovan korisnik', [
            'user_id' => auth()->id(),
            'date_time' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Log::info('Izlogovan korisnik', [
            'user_id' => auth()->id(),
            'date_time' => date('Y-m-d H:i:s')
        ]);

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}