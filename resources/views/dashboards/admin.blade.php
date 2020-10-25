@extends(config('app.env') == "package_development" ? 'timy::local-layout' : config('timy.layout'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-lg-8">
            @livewire('timy::open-timers-monitor')
        </div>
        <div class="col-sm-12 col-lg-4">
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


