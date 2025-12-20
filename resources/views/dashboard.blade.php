<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Hello User :)") }}
                    <h2 class="text-xl font-bold mb-4">Available Halls</h2>

                    @if($availableHalls->count() > 0)
                    <table class="w-full border">
                        <thead>
                            <tr class="bg-gray-800 text-white">
                                <th>Hall Name</th>
                                <th>Capacity</th>
                                <th>Available Date</th>
                                <th>Time</th>
                                <th>Want It ?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableHalls as $hall)
                            <tr>
                                <td>{{ $hall->hall_name }}</td>
                                <td>{{ $hall->hall_capacity }}</td>
                                <td>{{ $hall->date }}</td>
                                <td>{{ $hall->start_time }} - {{ $hall->end_time }}</td>
                                <td>
                                    {{-- <form method="POST" action="{{ route('hall.reserve', $hall->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-black text-white px-4 py-1 rounded">
                                            Reserve
                                        </button>
                                    </form> --}}
                                    <a href="{{ route('hall.payment', $hall->id) }}"
                                    class="bg-black text-white px-4 py-1 rounded">
                                        Reserve
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No available halls at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
