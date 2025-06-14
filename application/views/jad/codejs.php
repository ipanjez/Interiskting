<script type="text/javascript">
    $(function() {

        function ini_events(ele) {
            ele.each(function() {

                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                }

                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject)

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1070,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                })

            })
        }




        var currentDate; // Holds the day clicked when adding a new event
        var currentEvent; // Holds the event object when editing an event

        //$('#color').colorpicker(); // Colopicker
        $(document).ready(function() {
            $('#color').colorpicker({
                colorSelectors: {
                    //   'grey': '#777777',
                    // 'blue': '#337ab7',
                    //'success': '#5cb85c',
                    //'info': '#5bc0de',
                    //'warning': '#f0ad4e',
                    //'red': '#d9534f'
                    '0': '#ABB8C3',
                    '1': '#FF6900',
                    '2': '#FCB900',
                    '3': '#7BDCB5',
                    '4': '#00D084',
                    '5': '#8ED1FC',
                    '6': '#0693E3',
                    '7': '#DFEB3B',
                    '8': '#F78DA7',
                    '9': '#D4C4FB',
                },

                format: 'hex'
            });
        });




        var base_url = 'http://localhost/interiskting/index.php'; // Here i define the base_url

        //   var base_url = 'https://interiskting.com/index.php'; // Here i define the base_url

        // Fullcalendar
        $('#calendar').fullCalendar({

            header: {
                left: 'prev, next, today',
                center: 'title',
                right: 'month, basicWeek, basicDay'
            },
            // Get all events stored in database
            eventLimit: true, // allow "more" link when too many events
            events: base_url + 'jad/getEvents',
            selectable: true,
            selectHelper: true,
            editable: true, // Make the event resizable true           
            select: function(start, end) {

                $('#start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
                $('#end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
                // Open modal to add event
                modal({
                    // Available buttons when adding
                    buttons: {
                        add: {
                            id: 'add-event', // Buttons id
                            css: 'btn-success', // Buttons class
                            label: 'Add' // Buttons label
                        }
                    },
                    title: 'Add Event' // Modal title
                });
            },

            eventDrop: function(event, delta, revertFunc, start, end) {

                start = event.start.format('YYYY-MM-DD HH:mm:ss');
                if (event.end) {
                    end = event.end.format('YYYY-MM-DD HH:mm:ss');
                } else {
                    end = start;
                }

                $.post(base_url + 'jad/dragUpdateEvent', {
                    id: event.id,
                    start: start,
                    end: end
                }, function(result) {
                    $('.alert').addClass('alert-success').text('Event updated successfuly');
                    hide_notify();


                });



            },
            eventResize: function(event, dayDelta, minuteDelta, revertFunc) {

                start = event.start.format('YYYY-MM-DD HH:mm:ss');
                if (event.end) {
                    end = event.end.format('YYYY-MM-DD HH:mm:ss');
                } else {
                    end = start;
                }

                $.post(base_url + 'jad/dragUpdateEvent', {
                    id: event.id,
                    start: start,
                    end: end
                }, function(result) {
                    $('.alert').addClass('alert-success').text('Event updated successfuly');
                    hide_notify();

                });
            },

            // Event Mouseover
            eventMouseover: function(calEvent, jsEvent, view) {

                var tooltip = '<div class="event-tooltip">' + 'Judul : ' + calEvent.description + '<br>' + 'PIC : ' + calEvent.pic + '<br>' + 'Tempat/Link : ' + calEvent.link + '<br>' + 'Waktu : ' + calEvent.waktu + '<br>' + 'Target : ' + calEvent.target + '<br>' + 'Status : ' + calEvent.tls + '</div>';
                $("body").append(tooltip);

                $(this).mouseover(function(e) {
                    $(this).css('z-index', 10000);
                    $('.event-tooltip').fadeIn('500');
                    $('.event-tooltip').fadeTo('10', 1.9);
                }).mousemove(function(e) {
                    $('.event-tooltip').css('top', e.pageY + 10);
                    $('.event-tooltip').css('left', e.pageX + 20);
                });
            },
            eventMouseout: function(calEvent, jsEvent) {
                $(this).css('z-index', 8);
                $('.event-tooltip').remove();
            },
            // Handle Existing Event Click
            eventClick: function(calEvent, jsEvent, view) {
                // Set currentEvent variable according to the event clicked in the calendar
                currentEvent = calEvent;

                // Open modal to edit or delete event
                modal({
                    // Available buttons when editing
                    buttons: {
                        delete: {
                            id: 'delete-event',
                            css: 'btn-danger',
                            label: 'Delete'
                        },
                        update: {
                            id: 'update-event',
                            css: 'btn-success',
                            label: 'Update'
                        }
                    },
                    title: 'Edit Event "' + calEvent.title + '"',
                    event: calEvent
                });
            }

        });

        // Prepares the modal window according to data passed
        function modal(data) {
            // Set modal title
            $('.modal-title').html(data.title);
            // Clear buttons except Cancel
            $('.modal-footer button:not(".btn-default")').remove();
            // Set input values
            $('#title').val(data.event ? data.event.title : '');
            $('#description').val(data.event ? data.event.description : '');
            $('#pic').val(data.event ? data.event.pic : '');
            $('#link').val(data.event ? data.event.link : '');
            $('#waktu').val(data.event ? data.event.waktu : '');
            $('#target').val(data.event ? data.event.target : '');
            $('#tls').val(data.event ? data.event.tls : '');
            $('#color').val(data.event ? data.event.color : '#ABB8C3');
            // Create Butttons
            $.each(data.buttons, function(index, button) {
                $('.modal-footer').prepend('<button type="button" id="' + button.id + '" class="btn ' + button.css + '">' + button.label + '</button>')
            })
            //Show Modal
            $('.modal').modal('show');
        }

        // Handle Click on Add Button
        $('.modal').on('click', '#add-event', function(e) {
            if (validator(['title', 'description'])) {
                $.post(base_url + 'jad/addEvent', {
                    title: $('#title').val(),
                    description: $('#description').val(),
                    pic: $('#pic').val(),
                    link: $('#link').val(),
                    waktu: $('#waktu').val(),
                    target: $('#target').val(),
                    tls: $('#tls').val(),
                    color: $('#color').val(),
                    start: $('#start').val(),
                    end: $('#end').val()
                }, function(result) {
                    $('.alert').addClass('alert-success').text('Event added successfuly');
                    $('.modal').modal('hide');
                    $('#calendar').fullCalendar("refetchEvents");
                    hide_notify();
                });
            }
        });


        // Handle click on Update Button
        $('.modal').on('click', '#update-event', function(e) {
            if (validator(['title', 'description'])) {
                $.post(base_url + 'jad/updateEvent', {
                    id: currentEvent._id,
                    title: $('#title').val(),
                    description: $('#description').val(),
                    pic: $('#pic').val(),
                    link: $('#link').val(),
                    waktu: $('#waktu').val(),
                    target: $('#target').val(),
                    tls: $('#tls').val(),
                    color: $('#color').val()
                }, function(result) {
                    $('.alert').addClass('alert-success').text('Event updated successfuly');
                    $('.modal').modal('hide');
                    $('#calendar').fullCalendar("refetchEvents");
                    hide_notify();

                });
            }
        });



        // Handle Click on Delete Button
        $('.modal').on('click', '#delete-event', function(e) {
            $.get(base_url + 'jad/deleteEvent?id=' + currentEvent._id, function(result) {
                $('.alert').addClass('alert-success').text('Event deleted successfully !');
                $('.modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
            });
        });

        function hide_notify() {
            setTimeout(function() {
                $('.alert').removeClass('alert-success').text('');
            }, 2000);
        }


        // Dead Basic Validation For Inputs
        function validator(elements) {
            var errors = 0;
            $.each(elements, function(index, element) {
                if ($.trim($('#' + element).val()) == '') errors++;
            });
            if (errors) {
                $('.error').html('Please insert title and description');
                return false;
            }
            return true;
        }
    });
</script>