<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="py-12">
        <form style="width: 50%; margin-left: 20%;" id="event-form">

           @csrf
            <div class="mb-6">
                <label for="event_subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                    Subject</label>
                <input type="text" id="event_subject" name="event_subject"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Subject" required>
                      <span class="error-message"></span>
            </div>
            <div class="mb-6">
                <label for="first_attendee_email"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Attendee Email</label>
                <input type="email" id="first_attendee_email" name="first_attendee_email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="attende@email.com" required>
                      <span class="error-message"></span>
            </div>
            <div class="mb-6">
                <label for="second_attendee_email"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Second Attendee Email</label>
                <input type="email" id="second_attendee_email" name="second_attendee_email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="attende@email.com" required>
                      <span class="error-message"></span>
            </div>
            <div class="mb-6">
                <label for="event_date"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event Date</label>
                <input type="datetime-local"  data-enabletime=true id="event_date" name="event_date"
                    class=" flatpickr bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Event Date" required>
                      <span class="error-message"></span>
            </div>
              <span class="error-message"></span>
            <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6 ">
                <button type="button" id = "create-event"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    wire:loading.attr="disabled" wire:target="photo">
                    Save
                </button>
            </div>

        </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    config = {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
    }
 flatpickr("input[type=datetime-local]",config);

   $(document).ready(function() {
       $('#create-event').click(function() {
            var formData = $('#event-form').serialize();
            $('.error-message').text("");
              $.ajax({
              url: "{{ route('events.store') }}", // Replace with your actual route URL
              type: 'POST', // or 'GET' depending on your route definition
              data: formData,
              success: function(response) {
              // Handle the success response
                if(response.message == "Success")
                {
                    window.location.href  = "{{ route('events.index') }}"
                }
              },
              error: function(xhr, status, error) {
               if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        console.log(messages[0]);
                        // Find the corresponding form element and display the error message
                        var $formField = $('#' + field);
                        $formField.addClass('border-red-500'); // Apply a red border to indicate the error
                        $formField.next('.error-message').text(messages[0]).show(); // Display the first error message
                      $formField.next('.error-message').css("color", "red"); // Display the first error message
                    });
                }
              }
              });
        });
    });
</script>
</x-app-layout>
