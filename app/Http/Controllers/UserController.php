<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hall;
use App\Models\Reservation;

class UserController extends Controller
{
    public function Dashboard() {
        if(Auth::check() && Auth::user()->usertype == 'user')
        {
            $availableHalls = Hall::where('booked', false)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

            return view('dashboard', compact('availableHalls'));
        }
        else if(Auth::check() && Auth::user()->usertype == 'admin')
        {
            $totalHalls = Hall::count();
            $availableHalls = Hall::where('booked', false)->count();
            $bookedHalls = Hall::where('booked', true)->count();
            $reservations = Reservation::with(['user', 'hall'])->get();

            return view('admin.dashboard' , compact('totalHalls', 
            'availableHalls', 
            'bookedHalls',
            'reservations'));
        }
        else
        {
            return redirect('/');
        }
    }

    public function Index()
    {
        return view('welcome');
    }

    public function reserveHall($id)
    {
        $hall = Hall::findOrFail($id);

        if ($hall->booked) {
            return back()->with('error', 'Hall already reserved');
        }

        $hall->booked = true;
        $hall->booked_by = Auth::user()->name;
        $hall->save();

        return back()->with('success', 'Hall reserved successfully');
    }

}
