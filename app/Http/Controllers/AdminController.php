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

        $hall = new Hall();
        $hall->hall_name = $request->hall_name;
        $hall->hall_capacity = $request->hall_capacity;
        $hall->date = $request->date;
        $hall->start_time = $request->start_time;
        $hall->end_time = $request->end_time;

        $hall->save();
        return redirect()->back()->with('success', 'Hall added successfully');
    }
}