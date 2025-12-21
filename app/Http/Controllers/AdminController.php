<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;


class AdminController extends Controller
{
    public function Add_Hall(){
        $totalHalls = Hall::count();
        $availableHalls = Hall::where('booked', false)->count();
        $bookedHalls = Hall::where('booked', true)->count();

        return view('admin.addhall' , compact('totalHalls', 
        'availableHalls', 
        'bookedHalls',));
    }

    public function postAdd_Hall(Request $request){

        Hall::create([
            'hall_name'        => $request->hall_name,
            'hall_capacity'    => $request->hall_capacity,
            'date'             => $request->date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'price_per_hour'   => $request->price_per_hour,
        ]);

        return redirect()->back()->with('success', 'Hall added successfully');
    }
}