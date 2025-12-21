<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Hall;
use App\Models\Reservation;

class PaymentController extends Controller
{
    public function show(Request $request,$id)
    {
        // $hall = Hall::findOrFail($id);

        // // redirect if hall is booked
        // if ($hall->booked) {
        //     return redirect('/dashboard')
        //         ->withErrors('This hall is fully booked');
        // }


        // // Guard against bad data
        // if (!$hall->start_time || !$hall->end_time) {
        //     abort(500, 'Hall time range is not set');
        // }

        // $start = Carbon::parse($hall->start_time);
        // $end   = Carbon::parse($hall->end_time);

        // $reserved = Reservation::where('hall_id', $hall->id)
        //     ->where('date', $hall->date)
        //     ->get(['reserved_from', 'reserved_to']);

        // $taken = [];

        // foreach ($reserved as $r) {
        //     $t = Carbon::parse($r->start_time);
        //     while ($t < Carbon::parse($r->end_time)) {
        //         $taken[] = $t->format('H:i');
        //         $t->addHour();
        //     }
        // }

        // $slots = [];

        // while ($start < $end) {
        //     $slot = $start->format('H:i');
        //     if (!in_array($slot, $taken)) {
        //         $slots[] = $slot;
        //     }
        //     $start->addHour();
        // }

        // return view('payment', compact('hall', 'slots'));


    //     $hall = Hall::findOrFail($id);

    // $start = Carbon::parse($hall->start_time);
    // $end   = Carbon::parse($hall->end_time);

    // // Already booked slots
    // $booked = Reservation::where('hall_id', $hall->id)
    //     ->where('date', $hall->date)
    //     ->pluck('reserved_from')
    //     ->toArray();

    // $slots = [];

    // while ($start < $end) {
    //     $next = $start->copy()->addMinutes(30);

    //     $label = $start->format('H:i') . ' - ' . $next->format('H:i');

    //     if (!in_array($start->format('H:i'), $booked)) {
    //         $slots[] = $label;
    //     }

    //     $start = $next;
    // }

    // return view('payment', compact('hall', 'slots'));

    $hall = Hall::findOrFail($id);

    // Get reserved ranges for this hall/date
    $reserved = Reservation::where('hall_id', $hall->id)
        ->where('date', $hall->date)
        ->get(['reserved_from', 'reserved_to']);

    $start = Carbon::parse($hall->start_time);
    $end   = Carbon::parse($hall->end_time);
    

    $slots = [];

    while ($start < $end) {
        $next = $start->copy()->addMinutes(30);

        // Check if this slot overlaps any reservation
        $isReserved = $reserved->contains(function ($r) use ($start, $next) {
            return
                // Carbon::parse($r->reserved_from) < $next &&
                // Carbon::parse($r->reserved_to)   > $start;

                Carbon::parse($r->reserved_from)->lt($next) &&
                Carbon::parse($r->reserved_to)->gt($start);
        });

        // ONLY add free slots
        if (!$isReserved) {
            $slots[] = [
                'label' => $start->format('H:i') . ' - ' . $next->format('H:i'),
                'from'  => $start->format('H:i'),
                'to'    => $next->format('H:i'),
            ];
        }

        $start = $next;
    }

    return view('payment', [
        'hall' => $hall,
        'slots' => $slots,
        'pricePerHour' => $hall->price_per_hour,
    ]);

    }

    public function pay(Request $request, $id)
    {
//     $hall = Hall::findOrFail($id);

//     $slots = $request->input('slots', []);

// if (count($slots) === 0) {
//     return back()->withErrors('Please select at least one time slot.');
// }

// $parsedSlots = [];

// foreach ($slots as $slot) {

//     // SAFETY CHECK
//     if (!str_contains($slot, '|')) {
//         continue;
//     }

//     [$from, $to] = explode('|', $slot);

//     $parsedSlots[] = [
//         'from' => $from,
//         'to'   => $to,
//     ];
// }

//     $hours = (count($slots) * 30) / 60;
//     $totalPrice = $hours * $hall->price_per_hour;

//     Reservation::create([
//         'user_id'    => auth()->id(),
//         'hall_id'    => $hall->id,
//         'date'       => $hall->date,
//         'reserved_from' => $from,
//         'reserved_to'   => $to,
//         'subject'    => $request->subject,
//         'total_price'      => $totalPrice,
//     ]);

//     // total slots
//         $rangeStart = Carbon::parse($hall->start_time);
//         $rangeEnd   = Carbon::parse($hall->end_time);

//         $totalSlots = $rangeStart->diffInHours($rangeEnd);

//         // collect reserved slots
//         $reservedSlots = [];

//         $reservations = Reservation::where('hall_id', $hall->id)
//             ->where('date', $hall->date)
//             ->get(['reserved_from', 'reserved_to']);

//         foreach ($reservations as $r) {
//             $t = Carbon::parse($r->start_time);
//             $end = Carbon::parse($r->end_time);

//             while ($t < $end) {
//                 $reservedSlots[] = $t->format('H:i');
//                 $t->addHour();
//             }
//         }

//         // unique reserved slots
//         $reservedSlots = array_unique($reservedSlots);

//         //  FINAL DECISION
//         if (count($reservedSlots) >= $totalSlots) {
//             $hall->booked = true;
//         } else {
//             $hall->booked = false;
//         }

//         $hall->save();

//     return redirect('/dashboard')->with('success', 'Reservation completed');
// }
 $hall = Hall::findOrFail($id);

    //  FORCE slots to always be an array
    $slots = $request->has('slots')
        ? (array) $request->input('slots')
        : [];

    //  NOTHING SELECTED → STOP
    if (count($slots) === 0) {
        return back()->withErrors(['slots' => 'Please select at least one slot']);
    }

    //  SORT slots chronologically
    sort($slots);

    //  EXTRACT FROM / TO
    $first = explode('|', $slots[0]);
    $last  = explode('|', $slots[count($slots) - 1]);

    $reservedFrom = $first[0];
    $reservedTo   = $last[1];

    //  PRICE
    $slotCount = count($slots);
    $totalPrice = ($hall->price_per_hour / 2) * $slotCount;

    //  INSERT (THIS WAS FAILING BEFORE)
    Reservation::create([
        'user_id'       => auth()->id(),
        'hall_id'       => $hall->id,
        'date'          => $hall->date,
        'reserved_from' => $reservedFrom,
        'reserved_to'   => $reservedTo,
        'subject'       => $request->subject,
        'total_price'   => $totalPrice,
    ]);

  /* ===============================
   AUTO DISABLE HALL WHEN FULL
   =============================== */

$hall = Hall::findOrFail($id);

/* total minutes hall is open */
$totalMinutes =
    Carbon::parse($hall->start_time)
        ->diffInMinutes(Carbon::parse($hall->end_time));

/* total reserved minutes */
$reservedMinutes = Reservation::where('hall_id', $hall->id)
    ->where('date', $hall->date)
    ->get()
    ->sum(function ($r) {
        return Carbon::parse($r->reserved_from)
            ->diffInMinutes(Carbon::parse($r->reserved_to));
    });

/* mark hall as booked if fully reserved */
if ($reservedMinutes >= $totalMinutes) {
    $hall->booked = true;
    $hall->save();
}

    return redirect()->route('dashboard')->with('success', 'Hall reserved successfully');
}
}