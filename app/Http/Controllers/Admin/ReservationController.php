<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservationController extends Controller
{
    public function exportCsv()
    {
        $reservations = Reservation::with(['user', 'hall'])->get();

        $response = new StreamedResponse(function () use ($reservations) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'User Name',
                'User Email',
                'Hall Name',
                'Date',
                'Reserved From',
                'Reserved To'
            ]);

            foreach ($reservations as $r) {
                fputcsv($handle, [
                    $r->user->name,
                    $r->user->email,
                    $r->hall->hall_name,
                    $r->date,
                    $r->reserved_from,
                    $r->reserved_to
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="reservations.csv"'
        );

        return $response;
    }
}
