@extends(config('app.env') == "testing" ? 'timy::testing-app' : config('timy.layout'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-lg-8">
            @livewire('timy::open-timers-monitor')
        </div>
        <div class="col-lg-4 col-sm-12 mt-3 mt-lg-0">
            <div class="mb-3">
                @include('timy::downloads.hours')
            </div>
            <div>
                @include('timy::_user-hours-details')
            </div>
        </div>
    </div>
</div>
@endsection


