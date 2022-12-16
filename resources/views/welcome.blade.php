@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
@include('layouts.headers.guest')

<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card bg-secondary shadow border-0">
                <div class="card-body px-lg-5 py-lg-5">
                    <div class="text-center text-muted mb-4">
                        <small>
                                Book your appointment NOW and we will assign a best consilier for you!
                        </small>
                    </div>
                    <form role="form" method="POST" action="{{ route('createAppointment') }}" novalidate>
                        @csrf
                        <div class="row">
                        <div class="col-md-6 form-group{{ $errors->has('first_name') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative">
                                <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" placeholder="First name" name="first_name" type="text" required>
                            </div>
                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>   
                        <div class="col-md-6 col-6 form-group{{ $errors->has('last_name') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative">
                                <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" placeholder="Last name" name="last_name" type="text" required>
                            </div>
                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>     
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" value="admin@argon.com" required autofocus>
                            </div>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group date">
                            @if ($errors->has('date'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('date') }}</strong>
                                </span>
                            @endif  
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input id="dayPicker" name="date" type="text" class="form-control" required>
                            </div>
                        </div>
                        <input id="hourInterval" hidden name="hour_interval" placeholder="{{ __('Please select hour interv') }}"  required>
                        <div class="form-group">
                            @if ($errors->has('hour_interval'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('hour_interval') }}</strong>
                                </span>
                            @endif                   
                            <table class="table table-bordered">
                                <tbody id="hoursTable"></tbody>
                              </table>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary my-4">{{ __('Make an appointment') }}</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>
@endsection

@push('css')
    <style>
        .table td{
            padding: 8px!important;
            text-align: center;
        }
    </style>
@endpush

@push('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    </script>
    <script>
        var date = moment();
        const currentDate = date.format('DD/MM/YYYY');
        function getData(ajaxurl, values) { 
            return $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: values,
                        dataType:'json',
            });
        };

        async function getHours(day) {
            try {
                const res = await getData('/getAvailableHours', {day:day})
                return res;
            } catch(err) {
                console.log(err);
            }
        }
        function appendHours(day = currentDate){
            $('#hoursTable').html('');
            const hours = getHours(day);
            hours.then((a) => {
                $.each(a, function(index, value){
                    const {available, interval} = value;
                    if(index % 3 == 0 && index != 0)
                        $('#hoursTable').append('/<tr>');
                    if(index % 3 == 0)
                        $('#hoursTable').append('<tr>');  
                    if(available == 1)              
                        $('#hoursTable').append("<td><button type='button' onclick='setInterval(this)' data-available='"+available+"' class='btn btn-sm btn btn-outline-primary hourBtn'>"+interval+"</button></td>");    
                    else if(available == 0)
                        $('#hoursTable').append("<td><button type='button' disabled data-available='"+available+"' class='btn btn-sm btn btn-outline-primary'>"+interval+"</button></td>");    
                });
            });
        }

        function setInterval(element) {
            if($(element).attr('data-available') == 1){
                    $(".hourBtn[data-available=0]").removeClass('btn-primary');
                    $(".hourBtn[data-available=0]").addClass('btn-outline-primary');
                    $(".hourBtn[data-available=0]").attr('data-available', 1);
                    $(element).removeClass('btn-outline-primary');
                    $(element).addClass('btn-primary');
                    $(element).attr('data-available', 0);
                    $("#hourInterval").val($(element).html());
                }else{
                    $(".hourBtn[data-available=1]").removeClass('btn-primary');
                    $(".hourBtn[data-available=1]").addClass('btn-outline-primary');
                    $(".hourBtn[data-available=1]").attr('data-available', 0);
                    $(element).removeClass('btn-primary');
                    $(element).addClass('btn-outline-primary');
                    $(element).attr('data-available', 1);
                    $("#hourInterval").val('');
                }
        }
        $(function() {
            $("#dayPicker").attr('value', currentDate);
            $("#dayPicker").datepicker({
                setDate:currentDate,
                format: 'dd/mm/yyyy'
            }).on('changeDate', function(e) {
                this.value && appendHours(this.value);
            });
            appendHours();    
        });
    </script>
@endpush
