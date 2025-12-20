<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hall;
use App\Models\Reservation;

class PaymentController extends Controller
{
    public function show($id)
    {
        $hall = Hall::findOrFail($id);

        if ($hall->booked) {
            return redirect()->back()->with('error', 'Hall already booked');
        }

        return view('payment', compact('hall'));
    }

    public function pay($id)
    {
        $hall = Hall::findOrFail($id);

        if ($hall->booked) {
            return redirect()->back()->with('error', 'Hall already booked');
        }

        Reservation::create([
        'user_id' => auth()->id(),
        'hall_id' => $hall->id,
        'date' => $hall->date,
        'start_time' => $hall->start_time,
        'end_time' => $hall->end_time,
        ]);
        // Simulated payment success
        $hall->booked = true;
        $hall->booked_by = Auth::user()->name;
        $hall->save();

        return redirect('/dashboard')->with('success', 'Payment successful & hall reserved');
    }
}
