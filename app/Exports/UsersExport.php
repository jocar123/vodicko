<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Ime',
            'Korisničko ime',
            'Korisničko prezime',
            'Email',
            'Telefon',
            'Uloga'
        ];
    }

    public function map($user): array
    {
        return [
            $user->user_id,
            $user->name,
            $user->surname,
            $user->username, 
            $user->email,
            "'".$user->phone_number,
            $user->roles->pluck('name')->implode(', ')
        ];
    }
}
