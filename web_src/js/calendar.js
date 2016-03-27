jQuery(function ($) {

    var addEventClientFormModal = $('#event_client');
    var addEventClientCarFormModal = $('#event_client_car');
    var addEventWorkplace = $('#event_workplace');
    var addEventMessageFormModal = $('#event_message');
    var addEventStartFormModal = $('#event_start');
    var addEventEndFormModal = $('#event_end');

    var addWorkplaceTitle = $('#event_workplace_title');

    var addEventSubmitBtnModalForm = $('#addEventButtonSubmit');
    var addWorkplaceSubmitBtnModalForm = $('#addWorkplaceButtonSubmit');

    var getClientsUrl = Routing.generate('ajax_cto_get_clients');
    var getEventsUrl = Routing.generate('ajax_cto_get_events');
    var workplacePath = Routing.generate('ajax_cto_get_workplaces');

    var events = [];
    var resources = [];

    var returnUrl = Routing.generate('cto_jobs_home', {}, true);

    addEventSubmitBtnModalForm.click(function () {
        var url = Routing.generate("cto_new_event_fromJSONFORM");
        var errors = [];
        var requiredFields = [addEventMessageFormModal, addEventStartFormModal];

        var values = {
            "event": {
                "client": addEventClientFormModal.val(),
                "car": addEventClientCarFormModal.val(),
                "workplace": addEventWorkplace.val(),
                "description": addEventMessageFormModal.val(),
                "startAt": addEventStartFormModal.val(),
                "endAt": addEventEndFormModal.val()
            }
        };

        requiredFields.forEach(function(field){
            field.parent().find(".alert.alert-danger").remove();
            if (field.val() == ''){
                field.addClass('error').parent().append('<p class="alert alert-danger">Обовязкове поле *</p>');
                errors.push(field);
            }
        });

        if (errors.length == 0){
            $.post(url, JSON.stringify(values))
                .success(function (response) {
                    window.location.replace(returnUrl);
                })
                .error(function (error) {
                    console.log(error);
                });
        }
    });

    addWorkplaceSubmitBtnModalForm.click(function () {
        var url = Routing.generate("cto_new_workplace_fromJSONFORM");

        addWorkplaceTitle.parent().find('.alert.alert-danger').remove();

        var values = {
            "workplace": {
                "title": addWorkplaceTitle.val()
            }
        };

        if (values.workplace.title != ''){
            $.post(url, JSON.stringify(values))
                .success(function (response) {
                    window.location.replace(returnUrl);
                })
                .error(function (error) {
                    console.log(error);
                });
        } else {
            addWorkplaceTitle.parent().append('<p class="alert alert-danger">Обовязкове поле *</p>');
        }
    });

        $.get(workplacePath)
            .success(function (response) {
                console.log(response);
                addEventWorkplace.html('');
                if (response.workplaces.length > 0) {
                    $.each(response.workplaces, function (key, value) {
                        addEventWorkplace.append('<option value="' + value.id + '">' + value.title + '</option>');
                        resources.push({
                            "id": value.id,
                            "name": value.title
                        });
                    });
                } else {
                    addEventWorkplace.append('<option value="" disabled="disabled">Робочих місць не знайдено.</option>');
                }
                addEventWorkplace.selectpicker('refresh');
            })
            .error(function (error) {
                console.log(error);
            })
        ;

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

    addEventClientFormModal.change(function () {
        var val = $(this).val();
        getClientCars(val);
    });

    $('#addEvent').on('click', function (event) {
        event.preventDefault();
        $.get(getClientsUrl)
            .success(function (responseClient) {
                console.log(responseClient);
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

        $('#addEventForm').modal('show');
    });

    $('#addWorkplace').on('click', function (event) {
        event.preventDefault();

        $('#addWorkplaceForm').modal('show');
    });

    $("input[type=text].event-date-picker").datetimepicker();

    $.get(getEventsUrl)
        .success(function (response) {
            response.events.forEach(function (event) {
                events.push({
                    "id": event.id,
                    "title": event.description,
                    "resource": event.workplace.id,
                    "start": event.start.date,
                    "end": event.end.date,
                    //"url": Routing.generate('cto_show_event', {id: event.id}, true)
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
                resources: resources,
                unknownResourceTitle: null,
                columnFormat: {
                    month: 'dddd'
                },
                lang: 'uk',
                height: 470,
                events: events,
                eventClick: function(calEvent, jsEvent, view) {
                    console.log(calEvent);
                    $.get(Routing.generate('ajax_cto_get_event', {"id": calEvent.id}))
                        .success(function(response){
                            console.log(response);
                            var event = response.event;

                            console.log(event);

                            $('#client_name').html(event.client.name);
                            $('#client_profile').attr('href', Routing.generate('cto_client_show', { "slug" : event.client.slug }));

                            $('#event_start_date').html(moment(event.start.date).format('DD.MM.YYYY HH:mm'));
                            $('#event_end_date').html(moment(event.end.date).format('DD.MM.YYYY HH:mm'));
                            $('#event_car').html(event.car.name);
                            $('#event_description').html(event.description);

                            $('#showEventInfo').modal('show');
                        })
                    ;
                },
                dayClick: function ()
                {
                    $.get(getClientsUrl)
                        .success(function (responseClient) {
                            //console.log(responseClient);
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

                    $('#addEventForm').modal('show');
                },
                eventMouseover: function( event, jsEvent, view ) {
                    //console.log(event);
                },
                eventRender: function (event, element) {
                    element.hover(function(){
                        $(this).css({"cursor" : "pointer"});
                    });
                }
            });
        })
        .error(function (error) {
            console.log(error);
        })
    ;
});