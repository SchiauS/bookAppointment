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
                    <form role="form" method="POST" action="{{ route('createAppointment') }}">
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
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input id="dayPicker" name="day" type="text" class="form-control" value="{{now()->format("d/m/Y")}}">
                            </div>
                        </div>
                        <input hidden name="hour_interval">
                        <div class="form-group">                   
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
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        var date = moment();
        const currentDate = date.format('d/m/Y');
        function getData(ajaxurl, values) { 
            console.log(values);
            return $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: values
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
            const hours = getHours(day);
            hours.then((a) => {
                $.each(a, function(index, value){
                    if(index % 5 == 0 && index != 0)
                        $('#hoursTable').append('/<tr>');
                    if(index % 5 == 0)
                        $('#hoursTable').append('<tr>');        
                    $('#hoursTable').append("<td><button type='button' class='btn btn-sm btn btn-outline-primary hourBtn'>"+value+"</button></td>");    
                });
            });
            
                
        }
        $(function() {
            $("#dayPicker").datepicker().on('changeDate', function(e) {
                console.log(this.value);
                this.value && appendHours(this.value);
            });    
            appendHours();    
        });
    </script>
@endpush
