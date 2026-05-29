<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function showUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        $users = $query->get();
        $currentUser = auth()->user();

        return view('users', [
            'users' => $users,
            'currentUser' => $currentUser
        ]);
    }


    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users.show');
    }

    public function addManager($id)
    {
        $user = User::findOrFail($id);

        $managerRole = Role::where('name', 'manager')->firstOrFail();

        if (!$user->roles()->where('name', 'manager')->exists()) {
            $user->roles()->attach($managerRole->getKey());

            Log::info('Korisnik je postao menadžer', [
            'by_user_id' => auth()->id(),
            'target_user_id' => $user->user_id
        ]);
        }

        return redirect()->back();
    }

    public function removeManager($id)
    {
        $user = User::findOrFail($id);

        $managerRole = Role::where('name', 'manager')->firstOrFail();

        if ($user->roles()->where('name', 'manager')->exists()) {
            $user->roles()->detach($managerRole->id);
            Log::info('Uklonjen menadžer', [
            'by_user_id' => auth()->id(),
            'target_user_id' => $user->user_id
        ]);

        }

        return redirect()->back();
    }

    public function exportUsers()
    {
        $users = User::with('roles')->get();
        return Excel::download(new UsersExport($users), 'users.xlsx');
    }

    public function exportUsersPdf()
    {
        $users = User::with('roles')->get();

        $pdf = Pdf::loadView('users_pdf', ['users' => $users])
          ->setPaper('A4', 'landscape')
          ->setOption('isHtml5ParserEnabled', true)
          ->setOption('isPhpEnabled', true)
          ->setOption('dpi', 150);


        return $pdf->download('users.pdf');
    }
}
