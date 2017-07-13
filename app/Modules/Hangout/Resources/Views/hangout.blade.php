@extends('layouts.app')
@section('title')
Itweetup :: Activities
@endsection
@section('content')
<?php //dd($token,$errors); ?>
<div class="flex-item updates-block">
    <div class="box">

        @include('search::search_form')


        @if (count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors  as $key=>$value)
                <li >{{ $value }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(isset($status) == 1)
        <div class="alert alert-success text-center">
            <strong>Success!</strong>
            <p>Successful sent hangout Request.</p>
        </div>
        <script type="text/javascript">
            //here double curly bracket
            window.setTimeout(function () {
                window.location = "{{ asset('/profile') }}";
            }, 1000);
        </script>
        @endif


        <div class="box pad">
            {{ Form::open(array('url' => 'hangout/sent'.'/'.$token)) }}
            <div class="thick-text">Send Hangout Request</div>
            <div class="accordion-group">
                <div class="accordion">
                    <div class="row">
                        <label>Event <span class="text text-danger">*</span></label>
                        <input type="text" name="event" value="{{ $data['event']  or "" }}" required >                        
                    </div>
                    <div class="row">
                        <label>Location <span class="text text-danger">*</span></label>
                        <input type="text" name="location" value="{{ $data['location']  or "" }}" required >                      
                    </div>

                    <div class="row">
                        <label>Date <span class="text text-danger">*</span></label>
                        <input type="text" name="date"  value="{{ $data['date']  or "" }}" id="hangoutDate" required >
                    </div>
                    <div class="row">
                        <label>Time <span class="text text-danger">*</span></label>
                        <input type="text" name="time" value="{{ $data['time']  or "" }}" id="hangoutTime" required>
                    </div>
                    <div class="row">
                        <label>Private <span class="text text-danger">*</span></label>
                        <input type="text" name="private" value="{{ $data['private']  or "" }}" required>
                    </div>
                    <div class="row">
                        <label>Accompany <span class="text text-danger">*</span></label>
                        <input type="text" name="accompany" value="{{ $data['accompany']  or "" }}" required >
                    </div>
                    <div class="row">
                        <label>Family Member <span class="text text-danger">*</span></label>
                        <input type="text" name="family_member" value="{{ $data['family_member']  or "" }}" required>
                    </div>
                    <div class="flex-item text-right">
                        <input type="submit" name="submit" class="button" value="Submit">
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>


</script>