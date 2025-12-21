<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Available Halls') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h2 class="text-xl font-bold mb-4">Available Halls</h2>

                    @if($availableHalls->count() > 0)

                        <!-- TABLE WRAPPER (overflow safe) -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr class="bg-gray-800 text-white">
                                        <th class="px-3 py-2 whitespace-nowrap">Hall Name</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Capacity</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Date</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Available Time</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($availableHalls as $hall)
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">

                                            <td class="px-3 py-2 whitespace-nowrap">
                                                {{ $hall->hall_name }}
                                            </td>

                                            <td class="px-3 py-2 whitespace-nowrap">
                                                {{ $hall->hall_capacity }}
                                            </td>

                                            <td class="px-3 py-2 whitespace-nowrap">
                                                {{ $hall->date }}
                                            </td>

                                            <td class="px-3 py-2 whitespace-nowrap">
                                                {{ $hall->start_time }} - {{ $hall->end_time }}
                                            </td>

                                            <td class="px-3 py-2 whitespace-nowrap">
                                                @if($hall->booked)
                                                    <span class="bg-emerald-600 text-white px-3 py-1 rounded text-sm font-semibold">
                                                        Fully Booked
                                                    </span>
                                                @else
                                                    <a href="{{ route('hall.payment', $hall->id) }}"
                                                       class="bg-gray-800 text-white px-4 py-1 rounded
                                                              hover:bg-emerald-600 transition-colors duration-200">
                                                        Reserve
                                                    </a>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <p class="text-gray-500">No available halls at the moment.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
