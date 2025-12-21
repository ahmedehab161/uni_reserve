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
                    <section class="container">
                        <h2>Add Hall Availability</h2>

                        <form action="{{route('post_add_hall')}}" method="POST" enctype="multipart/form-data" id="addHallForm">
                            @csrf
                            <label>Hall Name</label>
                            <input type="text" name="hall_name" id="hallName" required>

                            <label>Capacity</label>
                            <input type="number" name="hall_capacity" id="hallCapacity" required>

                            <label>Date</label>
                            <input type="date" name="date" id="slotDate" required>

                            <label>Start Time</label>
                            <input type="time" name="start_time" id="startTime" step="900" required>

                            <label>End Time</label>
                            <input type="time" name="end_time" id="endTime" step="900" required>

                            <label>Price Per Hour</label>
                            <input type="number" name="price_per_hour" min="1" required class="w-full border rounded px-2 py-1">

                            <input type="submit" name="submit" class="button" value="add hall">
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
