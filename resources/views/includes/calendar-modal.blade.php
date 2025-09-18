<style>


    /* Event Creation Modal Styling */
    #eventCreateModal .modal-dialog {
        max-width: 500px; /* Set a smaller max width for the modal */
        margin: 30px auto; /* Center the modal */
    }



    /* Desktop styles */
    @media (min-width: 768px) {
        
        #eventCreateModal table.table td:first-child, #eventCreateModal table.table th:first-child {
            width: 2%;
        }
        #eventCreateModal table.table td:nth-child(2), #eventCreateModal table.table th:nth-child(2) {
            width: 1%;
        }
        #eventCreateModal table.table td:last-child, #eventCreateModal table.table th:last-child {
            text-align: unset; /* Align the text to the default value */
        }

        #eventCreateModal .modal-dialog {
            position: fixed;
            left: 30%;
            top: 2%;
            
        }

        #calendarModal.modal {
            padding: 0%;
            top: 4vw;
            left: 15vw;
            padding-right: 35% !important;
            overflow-y: scroll;
        }

        #eventCreateModal table.table td:first-child, #eventCreateModal table.table th:first-child {
        width: 4%;
        }

        #eventCreateModal table.table td:nth-child(2), #eventCreateModal table.table th:nth-child(2) {
            width: 1%;
        }
        #eventCreateModal table.table td:last-child, #eventCreateModal table.table th:last-child {
            text-align: unset; /* Align the text to the default value */
        }
    }

/* Mobile styles */
@media (max-width: 767.98px) {
    #eventCreateModal .modal-dialog {
        position: fixed;
        left: 0%;
        top: 0%;
        width: 100%;
        margin: 0;
        top: 1% !important;
        left: 10%;
    }

    #calendarModal.modal {
        padding: 0;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
    }
    
    #calendarModal .modal-dialog {
        margin: 0;
        max-width: 100%;
        height: 100%;
    }
    
    #calendarModal .modal-content {
        height: 100%;
        border-radius: 0;
    }

    .modal-open .modal {
        left: 0% !important;
        width: 100% !important;

}
}

#eventCreateModal .modal-header {
    font-size: 1.2rem; /* Slightly larger font for the header */
}

#eventCreateModal .modal-title {
    font-weight: bold; /* Make the title bold */
}

#eventCreateModal .modal-body {
    padding: 15px; /* Add some padding */
}

/* Form Group Styling */
#eventCreateModal .form-group {
    margin-bottom: 10px; /* Reduce bottom margin for form groups */
}

/* Row Styling for Compact Layout */
#eventCreateModal .row {
    margin-bottom: 0px; /* Add some space between rows */
    position: relative;
    left: 2%;
}

/* Adjust Column Widths */
#eventCreateModal .col-md-6 {
    flex: 0 0 48%; /* Set width to 48% for two columns */
    max-width: 48%; /* Ensure max width is 48% */
}

/* Button Styling */
#eventCreateModal .btn {
    margin-top: 10px; /* Add some space above buttons */
}
    /* Calendar Event Styles */
    #eventCreateModal .fc-event {
        background-color: rgba(0, 0, 0, 0.1) !important; /* Subtle transparent background */
        border: none !important; /* Remove borders for cleaner look */
        font-size: 0.7rem; /* Smaller text size for better fit */
        text-align: center; /* Center align the title */
        cursor: pointer; /* Show pointer on hover */
        transition: all 0.2s ease-in-out; /* Smooth hover effect */
        white-space: nowrap; /* Prevent text wrapping */
        overflow: hidden; /* Hide overflowing text */
        text-overflow: ellipsis; /* Add ellipsis to long titles */
    }

    #eventCreateModal .fc-event:hover {
        background-color: rgba(0, 0, 0, 0.2) !important; /* Slightly darken on hover */
        transform: scale(1.05); /* Scale up slightly on hover */
    }

    /* Event Title Styling */
    #eventCreateModal .fc-title {
        font-size: 0.7rem !important; /* Further reduce font size for title */
        white-space: nowrap; /* Prevent title wrapping */
        overflow: hidden; /* Hide overflowing text */
        text-overflow: ellipsis; /* Add ellipsis if the title is too long */
        color: black;
    }

/* Modal Styling */
#eventCreateModal .modal-dialog {
    max-width: 80%; /* Make modal wider on larger screens */
    margin: 20px auto; /* Adjust margin */
}

#eventCreateModal .modal-header {
    font-size: 0.8rem; /* Smaller header font size */
    padding: 10px; /* Reduce padding */
}

#eventCreateModal .modal-title {
    font-size: 0.9rem; /* Smaller title font size */
}

#eventCreateModal .modal-body {
    padding: 5px; /* Reduce padding */
}


/* Transaction Table Styling */
#eventCreateModal table.table {
    width: 100%;
    margin-bottom: 0;
}

#eventCreateModal table.table th, #eventCreateModal table.table td {
    text-align: left;
    padding: 4px; /* Reduce padding */
    font-size: 0.85rem; /* Smaller font size */
}

#eventCreateModal table.table th {
    font-weight: bold;
}

/* Column Specific Styling */
#eventCreateModal table.table td:first-child,
#eventCreateModal table.table th:first-child {
    width: 40%; /* Adjust width for title */
}

#eventCreateModal table.table td:nth-child(2),
#eventCreateModal table.table th:nth-child(2) {
    width: 14%; /* Adjust width for amount */
}

#eventCreateModal table.table td:last-child,
#eventCreateModal table.table th:last-child {
    width: 30%; /* Adjust width for description */
}

/* Modal Footer Styling */
#eventCreateModal .modal-footer {
    padding: 5px; /* Reduce padding */
    justify-content: center;
}

    /* Media Queries for Responsiveness */
    @media (max-width: 767px) {
        /* Adjust modal for smaller devices */
        #eventCreateModal .modal-dialog {
            width: 100%;
            margin: 0;
            top: 0;
        }

        /* Table Adjustments for Mobile */
        #eventCreateModal table.table th,
        #eventCreateModal table.table td {
            font-size: 0.75rem; /* Smaller font size for mobile */
            padding: 3px; /* Reduce padding */
        }

        #eventCreateModal table.table td:first-child,
        #eventCreateModal table.table th:first-child {
            width: 45%; /* Adjust width for title */
        }

        #eventCreateModal table.table td:nth-child(2),
        #eventCreateModal table.table th:nth-child(2) {
            width: 30%; /* Adjust width for amount */
        }

        #eventCreateModal table.table td:last-child,
        #eventCreateModal table.table th:last-child {
            width: 25%; /* Adjust width for description */
        }
    }
    @media (max-width: 576px) {
        /* Calendar Adjustments */
        #eventCreateModal #calendar {
            font-size: 0.75rem;
        }

        /* Adjust event size further on very small screens */
        #eventCreateModal .fc-event {
            font-size: 0.5rem;
            padding: 2px;
        }

        /* More compact modal */
        #eventCreateModal .modal-header {
            font-size: 0.8rem;
        }

        #eventCreateModal .modal-title {
            font-size: 0.9rem;
        }

        #eventCreateModal table.table th,
        #eventCreateModal table.table td {
            font-size: 0.75rem;
            padding: 4px;
        }

        #eventCreateModal table.table td:first-child,
        #eventCreateModal table.table th:first-child {
            width: 40%;
        }

        #eventCreateModal table.table td:nth-child(2),
        #eventCreateModal table.table th:nth-child(2) {
            width: 30%;
        }

        #eventCreateModal table.table td:last-child,
        #eventCreateModal table.table th:last-child {
            width: 30%;
        }

        /* Modal Footer - ensure it remains usable */
        #eventCreateModal .modal-footer {
            padding: 8px;
        }
    }
</style>


<div id="calendarModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-size:1rem !important;">Calendar</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Event Creation Modal -->
<div id="eventCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Event Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="eventForm" action="{{ route('events.store') }}" method="POST" data-parsley-validate>
                    @csrf
                    <input type="hidden" id="eventId" name="id">
                    <div class="form-group">
                        <label for="eventTitle">Title:</label>
                        <input type="text" class="form-control" id="eventTitle" name="title" required data-parsley-required-message="Please enter a title.">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required data-parsley-required-message="Please enter a description."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventStartDate">Start Date:</label>
                                <input type="date" class="form-control" id="eventStartDate" name="start_date" required data-parsley-required-message="Please select a start date.">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventEndDate">End Date:</label>
                                <input type="date" class="form-control" id="eventEndDate" name="end_date" required data-parsley-required-message="Please select an end date." data-parsley-date-end>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventStartTime">Start Time:</label>
                                <select class="form-control select2" id="eventStartTime" name="start_time" required data-parsley-required-message="Please select a start time.">
                                    <option value="" selected disabled>Select Start Time</option>
                                    @foreach (range(0, 23) as $hour)
                                        @foreach (range(0, 45, 15) as $minute)
                                            <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventEndTime">End Time:</label>
                                <select class="form-control select2" id="eventEndTime" name="end_time" required data-parsley-required-message="Please select an end time." data-parsley-time-end>
                                    <option value="" selected disabled>Select End Time</option>
                                    @foreach (range(0, 23) as $hour)
                                        @foreach (range(0, 45, 15) as $minute)
                                            <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" id="save" class="btn btn-primary">Save Event</button>
                    <button type="button" id="deleteEventButton" class="btn btn-danger" style="display: none;">Delete Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Event Creation Modal -->
<!-- Event Creation Modal -->
<div id="eventModal" class="modal fade" role="dialog" style="width: 67%; left: -4%; @media (max-width: 767.98px) { left: 0%;  }">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transactions</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 0%;">Title</th>
                            <th style="width: 0%;">Amount</th>
                            <th style="text-align: unset;">Description</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTableBody">
                        <!-- Transactions will be populated here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        function clearForm() {
            $('#eventId').val('');
            $('#eventTitle').val('');
            $('#description').val('');
            $('#eventStartDate').val('');
            $('#eventEndDate').val('');
            $('#eventStartTime').val('').trigger('change'); // Reset dropdown and trigger change
            $('#eventEndTime').val('').trigger('change'); // Reset dropdown and trigger change
            $('#deleteEventButton').hide(); // Hide the delete button
        }

        $('#eventCreateModal').on('hidden.bs.modal', function () {
            clearForm(); // Clear the form when the modal is hidden
        });
        
        $('#calendar').fullCalendar({
            events: function (start, end, timezone, callback) {
                var events = [];

                // Fetch transactions
                $.ajax({
                    url: '{{ route("events.load") }}', // Adjust this route as necessary
                    method: 'GET',
                    data: {
                        start: start.format(),
                        end: end.format(),
                    },
                    success: function (data) {
                        // Loop through each day in the data for transactions
                        for (var date in data) {
                            data[date].forEach(function (category) {
                                events.push({
                                    title: category.category_title + " " + category.total_amount,
                                    start: date,
                                    allDay: true,
                                    category_id: category.category_id,
                                    type: category.type,
                                    total_amount: category.total_amount,
                                });
                            });
                        }

                        // Fetch events
                        $.ajax({
                            url: '{{ route("events.fetch") }}', // New route for fetching events
                            method: 'GET',
                            data: {
                                start: start.format(),
                                end: end.format(),
                            },
                            success: function (eventData) {
                                // Loop through each event and add it to the events array
                                eventData.forEach(function (event) {
                                    // Handle multi-day events
                                    var startDate = moment(event.start_date + ' ' + event.start_time);
                                    var endDate = moment(event.end_date + ' ' + event.end_time);
                                    var currentDate = startDate.clone();

                                    while (currentDate.isSameOrBefore(endDate, 'day')) {
                                        events.push({
                                            title: event.title,
                                            start: currentDate.format('YYYY-MM-DD'),
                                            allDay: true,
                                            type: 'event', // Custom type to identify it as an event
                                            id: event.id, // Include event ID if needed
                                            description: event.description, // Include description if needed
                                        });
                                        currentDate.add(1, 'days');
                                    }
                                });

                                // Callback with all events (transactions and user events)
                                callback(events);
                            },
                            error: function (xhr, status, error) {
                                console.error('Error fetching events:', error);
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching transactions:', error);
                    },
                });
            },
            eventRender: function (event, element) {
                // Set background color based on event type (income, expense, or event)
                var color;
                if (event.type === 'income') {
                    color = 'rgba(76, 175, 80, 0.9)'; // Green for income
                } else if (event.type === 'expense') {
                    color = 'rgba(244, 67, 54, 0.9)'; // Red for expense
                } else if (event.type === 'event') {
                    color = 'rgba(33, 150, 243, 0.9)'; // Blue for events
                }
                element[0].style.backgroundColor = color;
                element[0].style.color = '#ffffff'; // White text
                element[0].style.textAlign = 'center'; // Center text
                element.find('.fc-title').text(event.title);
            },
            eventClick: function (calEvent) {
                // Fetch transaction details when event is clicked
                if (calEvent.type === 'event') {
                    $.ajax({
                        url: '{{ route('events.show', ':id') }}'.replace(':id', calEvent.id),
                        method: 'GET',
                        success: function(event) {
                            // Populate the modal with fetched event data
                            $('#eventId').val(event.id);
                            $('#eventTitle').val(event.title);
                            $('#description').val(event.description);
                            $('#eventStartDate').val(event.start_date); // Assuming this is in 'YYYY-MM-DD' format
                            $('#eventEndDate').val(event.end_date); // Assuming this is in 'YYYY-MM-DD' format
                            // Extract 'HH:mm' from 'HH:mm:ss' for both start and end times
                            let startTime = event.start_time.substring(0, 5); // 'HH:mm' format
                            let endTime = event.end_time.substring(0, 5); // 'HH:mm' format
                            console.log(startTime,endTime);
                            // Set the selected values in the dropdown
                            $('#eventStartTime').val(startTime).trigger('change');  // Trigger change event to update dropdown
                            $('#eventEndTime').val(endTime).trigger('change');  // Trigger change event to update dropdown

                            $('#deleteEventButton').show(); // Show the delete button

                            // Show the modal for event editing
                            $('#eventCreateModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching event details:', error);
                            alert('Could not fetch event details. Please try again.');
                        }
                    });
                } else {
                    // Fetch transaction details for transactions
                    $.ajax({
                        url: '{{ route("events.details") }}',
                        method: 'GET',
                        data: {
                            category_id: calEvent.category_id,
                            date: calEvent.start.format('YYYY-MM-DD'),
                            type: calEvent.type
                        },
                        success: function (data) {
                            // Clear the existing table rows
                            $('#transactionTableBody').empty();

                            // Populate the table with transaction data
                            data.forEach(function (transaction) {
                                var row = `<tr>
                                    <td>${transaction.title}</td>
                                    <td>${transaction.amount}</td>
                                    <td style="text-align: unset;">${transaction.description}</td>
                                </tr>`;
                                $('#transactionTableBody').append(row);
                            });

                            // Show the modal with the populated data
                            $('#eventModal').modal('show');
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching event details:', error);
                        }
                    });
                }
            },
            dayClick: function(date) {
                // Show the event creation modal
                $('#eventCreateModal').modal('show');

                // Pre-fill the start and end date fields with the clicked date
                $('#eventStartDate').val(date.format('YYYY-MM-DD'));
                $('#eventEndDate').val(date.format('YYYY-MM-DD'));
            },
        });

        // Show calendar modal and go to today's date
        $('#calendarModal').on('shown.bs.modal', function () {
            $('#calendar').fullCalendar('gotoDate', new Date());
        });

        $('#eventForm').parsley();

        // Handle form submission
        $('#eventForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            const startTime = $('#eventStartTime').val();
            const endTime = $('#eventEndTime').val();

            if (startTime && endTime) {
                const start = new Date('1970-01-01T' + startTime + 'Z');
                const end = new Date('1970-01-01T' + endTime + 'Z');

                // Check if end time is less than start time
                if (end < start) {
                    alert('End time cannot be less than start time.');
                    $('#save').prop('disabled', false); // Re-enable the button
                    return; // Stop the form submission
                }
            }

            // Check if the form is valid
            if ($(this).parsley().isValid()) {
                // Serialize the form data
                var formData = $(this).serialize();
                var eventId = $('#eventId').val();
                var url = eventId ? '{{ route('events.update', ':id') }}'.replace(':id', eventId) : '{{ route('events.store') }}';
                var method = eventId ? 'PUT' : 'POST'; // Use PUT for updates, POST for new events

                // Send AJAX request
                $.ajax({
                    url: url, // Use the form's action attribute
                    method: method,
                    data: formData,
                    success: function (response) {
                        // Handle success response
                        if (response) {
                            // Close the modal
                            $('#eventCreateModal').modal('hide');

                            // Optionally, refresh the calendar or update the UI
                            $('#calendar').fullCalendar('refetchEvents');

                            // Show success message
                            alert(eventId ? 'Event updated successfully!' : 'Event created successfully!');
                        } else {
                            // Handle error response
                            alert('Error creating event. Please try again.');
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle AJAX error
                        console.error('AJAX Error:', error);
                        alert('An error occurred while saving the event. Please try again.');
                    }
                });
            }
        });



        // Add event deletion handler
        $('#deleteEventButton').click(function() {
            var eventId = $('#eventId').val();
            if (eventId) {
                if (confirm('Are you sure you want to delete this event?')) {
                    $.ajax({
                        url: '{{ route('events.destroy', ':id') }}'.replace(':id', eventId),
                        method: 'DELETE',
                        success: function(response) {
                            // Handle success response
                            console.log(response);
                            // Refresh calendar
                            $('#calendar').fullCalendar('refetchEvents');
                            // Clear form inputs
                            clearForm(); // Assuming you have a function to clear the form
                            // Close modal
                            $('#eventCreateModal').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            console.error('Error:', error);
                            alert('An error occurred while deleting the event. Please try again.');
                        }
                    });
                }
            }
        });
    });
</script>
