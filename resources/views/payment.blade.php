<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl">Payment</h2>
</x-slot>

<div class="p-6 max-w-xl mx-auto bg-white shadow rounded">

    <h3 class="text-lg font-bold mb-4">Hall Reservation Payment</h3>

    <p><strong>Hall:</strong> {{ $hall->hall_name }}</p>
    <p><strong>Date:</strong> {{ $hall->date }}</p>
    <p><strong>Time:</strong> {{ $hall->start_time }} - {{ $hall->end_time }}</p>
    <p><strong>Price:</strong> $50</p>

    <form method="POST" action="{{ route('hall.pay', $hall->id) }}">
        @csrf
        <button class="mt-4 w-full bg-green-600 text-black py-2 rounded">
            Pay Now
        </button>
    </form>

</div>
</x-app-layout>
