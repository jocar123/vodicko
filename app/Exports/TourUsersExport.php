<?php

namespace App\Exports;

use App\Models\Tour;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TourUsersExport implements FromArray, WithHeadings
{
   public function array(): array
    {
        $data = [];
        $tours = Tour::with('users')->get();

        foreach ($tours as $tour) {
            $firstUser = true;

            if ($tour->users->count() > 0) {
                foreach ($tour->users as $user) {
                    $data[] = [
                        $firstUser ? $tour->title : '',
                        $user->name,
                        $user->surname,
                        $user->email,
                        "'" . $user->phone_number
                    ];
                    $firstUser = false;
                }
            } else {
                $data[] = [$tour->title, 'Nema prijavljenih', '', '', ''];
            }
            $data[] = ["", "", "", "", ""];
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Tura', 'Korisnik', 'Email', 'Telefon'];
    }
}
