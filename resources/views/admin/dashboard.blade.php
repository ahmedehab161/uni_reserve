<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section class="container admin">
                        <div class="card">
                            <h3>Booked Halls</h3>
                            <p>{{$bookedHalls}}</p>
                        </div>
                        <div class="card">
                            <h3>Available Halls</h3>
                            <p>{{ $availableHalls  }}</p>
                        </div>
                        <div class="card">
                            <h3>Total Halls</h3>
                            <p>{{ $totalHalls }}</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">

        <a href="/reservations/export" class="bg-gray-800 text-white px-4 py-2 rounded-md mb-4 inline-block
          hover:bg-emerald-600 transition-all duration-200">
            Export CSV
        </a>

        <table class="w-full border">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-2">User</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Hall</th>
                    <th class="p-2">Date</th>
                    <th class="p-2">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                    <tr class="border-b text-white">
                        <td class="p-2">{{ $r->user->name }}</td>
                        <td class="p-2">{{ $r->user->email }}</td>
                        <td class="p-2">{{ $r->hall->hall_name }}</td>
                        <td class="p-2">{{ $r->date }}</td>
                        <td class="p-2">{{ $r->reserved_from }} - {{ $r->reserved_to }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 p-4">
                            No Reservations Made For Now.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-app-layout>