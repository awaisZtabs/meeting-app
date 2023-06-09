<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>

        <a type="button" href="{{ route("events.create") }}" id="event-add" style="margin-top: -27px; float: right;" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:loading.attr="disabled" wire:target="photo">
    Add Event
</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Subject
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    First Attendee
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Second Attendee
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                            </tr>
                        </thead>
                    <tbody>
    @if (count($events) > 0)
        @foreach ($events as $event)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td class="px-6 py-4">
                    {{ $event->subject }}
                </td>
                <td class="px-6 py-4">
                    {{ $event->first_attendee_email }}
                </td>
                <td class="px-6 py-4">
                    {{ $event->second_attendee_email }}
                </td>
                <td class="px-6 py-4">
                    {{ $event->event_date }}
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('events.edit', $event->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    &nbsp;
                    <button class="delete-event font-medium text-red-600 dark:text-red-500 hover:underline" data-event-id="{{ $event->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" class="px-6 py-4">
                {{ $events->links() }}
            </td>
        </tr>
    @else
        <tr>
            <td colspan="5" class="px-6 py-4 text-center">No Events found.</td>
        </tr>
    @endif
</tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    $(document).ready(function() {
       $('#delete-event').click(function() {
          var eventId = $(this).data('event-id');
        var url = "{{ route('events.destroy', ':eventId') }}";
        url = url.replace(':eventId', eventId);
              $.ajax({
              url: url,
              type: 'DELETE',
              data: {
                    _token: '{{ csrf_token() }}'
                },
              success: function(response) {
                if(response.message == "Success")
                {
                    window.location.href  = "{{ route('events.index') }}"
                }
              },

              });
        });
    });
</script>
</x-app-layout>
