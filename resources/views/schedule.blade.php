@extends('layouts.main')

@section('content')
    <div class="container ">
        <div class="row">
            @if (session()->has('user') && session('status') == 0 || !isset($user->team))
                @include('link')
            @else
                @include('status', ['user' => $user])
                <div class="pt-1 col-md-12 w-100">
                    <div class="card bg-secondary">
                        <div class="card-body ">
                            <div class="row">
                                    <div class="col">
                                            <div class="card banner">
                                                <div class="card-body banner">
                                                    <h3 class="text-center text-light">Habilitación de Agendas Domingo</h3>
                                                </div>
                                            </div>
                                            <div class="mt-2 card bg-dark text-light">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mx-2 text-lg font-bold">{{'Platino: '.$platino . ':00'}}</div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mx-2 text-lg font-bold">{{'Oro: '.$oro . ':00'}}</div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mx-2 text-lg font-bold">{{'Plata: '.$plata . ':00'}}</div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mx-2 text-lg font-bold">{{'Bronce: '.$bronce . ':00'}}</div>
                                                    </div>
                                                </div>
                                            </div>

                                        @if (!$schedule_avaible)
                                            <div class="mt-2 card bg-dark text-light">
                                                    <div class="row">
                                                            <div class="col-12">
                                                                @if (!$active_time_zone)
                                                                <div class="mx-2 mt-2 mb-2 text-lg font-bold text-left text-danger">
                                                                   Debes actualizar tu perfil y agregar tu zona horaria</div>
                                                            </div>
                                                            @endif
                                                            <div class="mx-2 mt-2 mb-2 text-lg font-bold text-left">No están
                                                                habilitadas las reservas
                                                            </div>
                                                    </div>
                                            </div>
                                        @else

                                            <div class="py-2 col-12">
                                                <div class="card-body banner">
                                                    <h3 class="text-center text-light">Seleccione Día y Hora</h3>
                                                </div>
                                            </div>
                                            <div class="mt-2 card bg-dark text-light">
                                                    <div class="row">

                                                        {{-- @dump($days_with_time) --}}
                                                        @foreach ($days_with_time as $key_day => $day_with_time)
                                                            @if ($day_with_time['status'])
                                                            <div class="px-4 my-3 col-lg-4 col-md-4 col-sm-4">
                                                                    <input
                                                                    class="text-center form-control form-control-lg bg-warning"
                                                                        type="text" placeholder="{{ $key_day }}" disabled>
                                                                    <div class="col">
                                                                        <div mbsc-page class="demo-multiple-select">
                                                                            <div style="height:100%">
                                                                                <label>
                                                                                    {{-- Lunes --}}
                                                                                    <input mbsc-input
                                                                                        id="{{ 'demo-multiple-select-input-' . $key_day }}"
                                                                                        placeholder="Seleccione horario"
                                                                                        data-dropdown="true"
                                                                                        data-input-style="outline"
                                                                                        data-label-style="stacked" data-tags="true"
                                                                                        class="calendar" />
                                                                                </label>
                                                                                <select
                                                                                    id="{{ 'demo-multiple-select-' . $key_day }}"
                                                                                    multiple>

                                                                                    @foreach ($day_with_time['times'] as $key_time => $time)
                                                                                        @if ($time['disabled'])
                                                                                            <option value="{{ $time['hour'] }}"
                                                                                                disabled>
                                                                                                {{ $time['hour'] }}</option>
                                                                                        @else
                                                                                            <option value="{{ $time['hour'] }}">
                                                                                                {{ $time['hour'] }}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        <div class="py-4 mx-3 col-12">
                                                            <button class="btn btn-primary" type="submit"
                                                                onclick="btnClick()">Guardar Cambios</button>
                                                        </div>
                                                    </div>
                                            </div>

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <input type="text" class="form-control" aria-label="Default" name="times" id="times"
                    aria-describedby="inputGroup-sizing-default" value="{{ $times }}" style="display: none"> --}}
            @endif
        </div>
    </div>

    @include('layouts.footer')
@endsection
@push('schedule')
    <script>
        let sites = {!! json_encode($days_with_time) !!};
        let lunes = [];
        let martes = [];
        let miercoles = [];
        let jueves = [];
        let viernes = [];
        let sabado = [];
        let week = [];
        let data_lunes = [];
        let data_martes = [];
        let data_miercoles = [];
        let data_jueves = [];
        let data_viernes = [];
        let data_sabado = [];

        if (sites != null) {
            let arrayLunes = sites['lunes']['times'];
            let arrayMartes = sites['martes']['times'];
            let arrayMiercoles = sites['miercoles']['times'];
            let arrayJueves = sites['jueves']['times'];
            let arrayViernes = sites['viernes']['times'];
            let arraySabado = sites['sabado']['times'];

            data_lunes = crateSelectDay(arrayLunes);
            data_martes = crateSelectDay(arrayMartes);
            data_miercoles = crateSelectDay(arrayMiercoles);
            data_jueves = crateSelectDay(arrayJueves);
            data_viernes = crateSelectDay(arrayViernes);
            data_sabado = crateSelectDay(arraySabado);
            mobiscroll.setOptions({
                locale: mobiscroll
                    .localeEs, // Specify language like: locale: mobiscroll.localePl or omit setting to use default
                theme: 'ios', // Specify theme like: theme: 'ios' or omit setting to use default
                themeVariant: 'light' // More info about themeVariant: https://docs.mobiscroll.com/5-27-3/javascript/select#opt-themeVariant
            });

            mobiscroll.select('#demo-multiple-select-lunes', {
                selectMultiple: true,
                invalid: ['1:00'],
                Animation: 'slide-down',
                className: 'text-class',
                data: data_lunes,
                renderItem: function(item) {
                    // console.log(item);
                    if (item.data.duplicated) {
                        return `<div class="duplicatedInput">${item.value}</div>`;
                    }
                    if (item.data.disabled) {
                        return `<div class="disbledInput">${item.value}</div>`;
                    } else {
                        return `<div class="avaibleInput">${item.value}</div>`;
                    }

                },
                inputElement: document.getElementById(
                        'demo-multiple-select-input-lunes'
                    ) // More info about inputElement: https://docs.mobiscroll.com/5-27-3/javascript/select#opt-inputElement

                    ,
                onChange: function(ev, inst) {
                    // lunes =ev.valueText;
                    lunes = ev.value;
                },
            });

            mobiscroll.select('#demo-multiple-select-martes', {
                selectMultiple: true,
                invalid: ['value'],
                Animation: 'slide-down',
                data: data_martes,
                renderItem: function(item) {
                    // console.log(item);
                    if (item.data.duplicated) {
                        return `<div class="duplicatedInput">${item.value}</div>`;
                    }
                    if (item.data.disabled) {
                        return `<div class="disbledInput">${item.value}</div>`;
                    } else {
                        return `<div class="avaibleInput">${item.value}</div>`;
                    }

                },
                inputElement: document.getElementById(
                        'demo-multiple-select-input-martes'
                    ) // More info about inputElement: https://docs.mobiscroll.com/5-27-3/javascript/select#opt-inputElement

                    ,
                onChange: function(ev, inst) {
                    martes = ev.value;
                    console.log(ev)
                },
            });

            mobiscroll.select('#demo-multiple-select-miercoles', {
                selectMultiple: true,
                invalid: ['value'],
                Animation: 'slide-down',
                data: data_miercoles,
                renderItem: function(item) {
                    // console.log(item);
                    if (item.data.duplicated) {
                        return `<div class="duplicatedInput">${item.value}</div>`;
                    }
                    if (item.data.disabled) {
                        return `<div class="disbledInput">${item.value}</div>`;
                    } else {
                        return `<div class="avaibleInput">${item.value}</div>`;
                    }

                },
                inputElement: document.getElementById(
                        'demo-multiple-select-input-miercoles'
                    ) // More info about inputElement: https://docs.mobiscroll.com/5-27-3/javascript/select#opt-inputElement

                    ,
                onChange: function(ev, inst) {
                    miercoles = ev.value;
                },
            });

            mobiscroll.select('#demo-multiple-select-jueves', {
                selectMultiple: true,
                invalid: ['value'],
                Animation: 'slide-down',
                data: data_jueves,
                renderItem: function(item) {
                    // console.log(item);
                    if (item.data.duplicated) {
                        return `<div class="duplicatedInput">${item.value}</div>`;
                    }
                    if (item.data.disabled) {
                        return `<div class="disbledInput">${item.value}</div>`;
                    } else {
                        return `<div class="avaibleInput">${item.value}</div>`;
                    }

                },
                inputElement: document.getElementById(
                        'demo-multiple-select-input-jueves'
                    ) // More info about inputElement: https://docs.mobiscroll.com/5-27-3/javascript/select#opt-inputElement

                    ,
                onChange: function(ev, inst) {
                    jueves = ev.value;
                },
            });

            mobiscroll.select('#demo-multiple-select-viernes', {
                selectMultiple: true,
                invalid: ['value'],
                Animation: 'slide-down',
                data: data_viernes,
                renderItem: function(item) {
                    // console.log(item);
                    if (item.data.duplicated) {
                        return `<div class="duplicatedInput">${item.value}</div>`;
                    }
                    if (item.data.disabled) {
                        return `<div class="disbledInput">${item.value}</div>`;
                    } else {
                        return `<div class="avaibleInput">${item.value}</div>`;
                    }

                },
                inputElement: document.getElementById(
                        'demo-multiple-select-input-viernes'
                    ) // More info about inputElement: https://docs.mobiscroll.com/5-27-3/javascript/select#opt-inputElement

                    ,
                onChange: function(ev, inst) {
                    viernes = ev.value;
                },
            });

            mobiscroll.select('#demo-multiple-select-sabado', {
                selectMultiple: true,
                invalid: ['value'],
                Animation: 'slide-down',
                data: data_sabado,
                renderItem: function(item) {
                    // console.log(item);
                    if (item.data.duplicated) {
                        return `<div class="duplicatedInput">${item.value}</div>`;
                    }
                    if (item.data.disabled) {
                        return `<div class="disbledInput">${item.value}</div>`;
                    } else {
                        return `<div class="avaibleInput">${item.value}</div>`;
                    }

                },
                inputElement: document.getElementById(
                        'demo-multiple-select-input-sabado'
                    ) // More info about inputElement: https://docs.mobiscroll.com/5-27-3/javascript/select#opt-inputElement

                    ,
                onChange: function(ev, inst) {
                    sabado = ev.value;
                },
            });
        }



        // console.log(data_lunes);


        function crateSelectDay(arrayDay) {
            let data_day = [];
            Object.entries(arrayDay).forEach(entry => {
                const [key, value] = entry;
                data_day.push({
                    text: value.hour,
                    disabled: value.disabled,
                    duplicated: value.duplicated,
                    value: value.hour
                })
            });
            return data_day;
        }

        function btnClick() {
            week = [];
            if (lunes.length > 0) {

                week.push({
                    day: 1,
                    horarios: lunes
                });
            }
            if (martes.length > 0) {
                week.push({
                    day: 2,
                    horarios: martes
                });
            }
            if (miercoles.length > 0) {
                week.push({
                    day: 3,
                    horarios: miercoles
                });
            }
            if (jueves.length > 0) {
                week.push({
                    day: 4,
                    horarios: jueves
                });
            }
            if (viernes.length > 0) {
                week.push({
                    day: 5,
                    horarios: viernes
                });
            }
            if (sabado.length > 0) {
                week.push({
                    day: 6,
                    horarios: sabado
                });
            }
            console.log('week');
            console.log(week);
            if (week.length > 0) {
                $.ajax({
                    url: 'schedule/update',
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        days: week,

                    },
                    success: function(response) {
                        console.log(response);

                        if (response.status === 'ok') {
                            // $("#edit-dialog").modal("hide");
                            // table.draw();
                            console.log('okkkk');
                            console.log(response);
                            window.location.href = "{{ route('my_agendas') }}";
                        } else {
                            console.log('error');
                            window.alert(response.message);
                            // window.location.href = "{{ route('schedule') }}";
                            location.reload()
                        }
                        // $(".loading").hide();
                    },
                    error: function(data) {

                    }
                });
            } else {
                alert('Debe agregar horas');
            }


        }
    </script>
@endpush
@push('chatters')
    {{-- @if (env('APP_ENV') == 'local')
        <script src="{{ asset('/js/setTime.js') }}"></script>
    @else
        <script src="./public/js/setTime.js"></script>
    @endif --}}
@endpush
