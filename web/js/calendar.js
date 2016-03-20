jQuery(function ($) {

    var addEventClientFormModal = $('#event_client');
    var addEventClientCarFormModal = $('#event_client_car');
    var addEventMessageFormModal = $('#event_message');
    var addEventStartFormModal = $('#event_start');
    var addEventEndFormModal = $('#event_end');

    var addEventSubmitBtnModalForm = $('#addEventButtonSubmit');

    var getClientsUrl = Routing.generate('ajax_cto_get_clients');
    var getEventsUrl = Routing.generate('ajax_cto_get_events');

    var events = [];

    var returnUrl = Routing.generate('cto_jobs_home', {}, true);

    addEventSubmitBtnModalForm.click(function () {
        var url = Routing.generate("cto_new_event_fromJSONFORM");

        var values = {
            "event": {
                "client": addEventClientFormModal.val(),
                "car": addEventClientCarFormModal.val(),
                "description": addEventMessageFormModal.val(),
                "startAt": addEventStartFormModal.val(),
                "endAt": addEventEndFormModal.val()
            }
        };

        $.post(url, JSON.stringify(values))
            .success(function (response) {
                window.location.replace(returnUrl);
            })
            .error(function (error) {
                console.log(error);
            });
    });

    function getClientCars(clientId) {
        var carPath = Routing.generate('ajax_cto_cars_from_client', {"id": clientId});
        $.get(carPath)
            .success(function (response) {
                addEventClientCarFormModal.html('');
                if (response.cars.length > 0) {
                    $.each(response.cars, function (key, value) {
                        addEventClientCarFormModal.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                } else {
                    addEventClientCarFormModal.append('<option value="" disabled="disabled">Клієнт не має автомобілів.</option>');
                }
                addEventClientCarFormModal.selectpicker('refresh');
            })
            .error(function (error) {
                console.log(error);
            })
        ;
    }

    $.get(getClientsUrl)
        .success(function (responseClient) {
            addEventClientFormModal.html('');
            if (responseClient.clients.length > 0) {
                $.each(responseClient.clients, function (key, value) {
                    addEventClientFormModal.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                getClientCars(responseClient.clients[0].id);
            } else {
                addEventClientFormModal.append('<option value="" disabled="disabled">Клієнтів не знайдено.</option>');
            }
            addEventClientFormModal.selectpicker('refresh');
        })
        .error(function (error) {
            console.log(error);
        })
    ;

    addEventClientFormModal.change(function () {
        var val = $(this).val();
        getClientCars(val);
    });

    $('#addEvent').on('click', function (event) {
        event.preventDefault();

        $('#addEventForm').modal('show');
    });

    $("input[type=text].event-date-picker").datetimepicker();

    $.get(getEventsUrl)
        .success(function (response) {
            response.events.forEach(function (event) {
                events.push({
                    "title": event.description,
                    "start": event.start.date,
                    "end": event.end.date,
                    "url": Routing.generate('cto_show_event', {id: event.id}, true)
                });
            });

            $("#calendar").fullCalendar({
                header: {
                    left: 'title',
                    center: '',
                    right: 'month, basicWeek, resourceAgendaDay, prev,next'
                },
                views: {
                    month: { // name of view
                        titleFormat: 'MMMM YYYY'
                    },
                    basicWeek: { // name of view
                        titleFormat: 'MMMM YYYY'
                    },
                    basicDay: { // name of view
                        titleFormat: 'MMMM YYYY'
                    },
                    resourceAgendaDay: {
                        type: "resourceAgenda",
                        duration: {
                            days: 1
                        },
                        buttonText: "День"
                    }
                },
                resources: [
                    {id: "room101", name: "Room 101"},
                    {id: "room102", name: "Room 102"}
                ],
                columnFormat: {
                    month: 'dddd'
                },
                lang: 'uk',
                height: 470,
                dayClick: function (date, jsEvent, view) {
                    $('#showDayInfo').modal('show');
                    var getEventsByDateUrl = Routing.generate('ajax_cto_get_events_by_date', {date: date.format('DD-MM-YYYY')});
                    $.get(getEventsByDateUrl)
                        .success(function (response) {
                            console.log(response);
                        })
                        .error(function (error) {
                            console.log(error);
                        })
                    ;
                },
                events: events
            });
        })
        .error(function (error) {
            console.log(error);
        })
    ;
});