<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-white">Time Selection</h2>
</x-slot>

<div class="p-6 max-w-xl mx-auto bg-white shadow rounded">

    <h3 class="text-lg font-bold mb-4">Hall Reservation</h3>

    <p><strong>Hall:</strong> {{ $hall->hall_name }}</p>
    <p><strong>Date:</strong> {{ $hall->date }}</p>
    <p><strong>Price / hour:</strong> {{ $pricePerHour }} L.E</p>

    <div class="mt-4 text-lg font-bold">
        Total Price:
        <span id="totalPrice">0</span> L.E
    </div>

    <form method="POST" action="{{ route('hall.pay', $hall->id) }}">
        @csrf
        <input type="hidden" name="total_price" id="totalPriceInput">
        <h4 class="mt-4 font-semibold">Select Time Slots</h4>

        <div class="grid grid-cols-2 gap-2 mt-2">
            <label class="block mt-4 font-semibold">Subject</label>
                <input
                    type="text"
                    name="subject"
                    required
                    class="w-full border rounded px-3 py-2 mt-1"
                >
            @foreach($slots as $slot)
                <label class="border rounded px-2 py-1 flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="slots[]"
                        value="{{ $slot['from'] }}|{{ $slot['to'] }}"
                        class="slot-checkbox"
                    >
                    {{ $slot['label'] }}
                </label>
            @endforeach
        </div>

        <button class="mt-4 w-full bg-emerald-600 text-white py-2 rounded">
            Pay Now
        </button>
    </form>
</div>

{{-- LIVE PRICE SCRIPT --}}
<script>
    const pricePerHour = {{ $pricePerHour }};
    const slotPrice = pricePerHour / 2;

    function updatePrice() {
        const count = document.querySelectorAll('.slot-checkbox:checked').length;
        const total = (count * slotPrice).toFixed(2);

        document.getElementById('totalPrice').innerText = total;
        document.getElementById('totalPriceInput').value = total;
    }

    document.querySelectorAll('.slot-checkbox').forEach(cb => {
        cb.addEventListener('change', updatePrice);
    });
</script>
</x-app-layout>


<script>
document.querySelector('select[name="start_time"]').addEventListener('change', function () {
    if (!this.value) return;

    let [h, m] = this.value.split(':').map(Number);

    // convert to total minutes
    let totalMinutes = h * 60 + m + 30;

    // convert back
    let endH = Math.floor(totalMinutes / 60) % 24;
    let endM = totalMinutes % 60;

    document.getElementById('end_time').value =
        String(endH).padStart(2, '0') + ':' + String(endM).padStart(2, '0');
});
</script>
