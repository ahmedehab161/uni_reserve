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

        //  Total Price
        $slotCount = count($slots);
        $totalPrice = ($hall->price_per_hour / 2) * $slotCount;

        //  INSERT Data
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