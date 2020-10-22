@extends(config('app.env') == "package_development" ? 'timy::local-layout' : config('timy.layout'))

@section('content')
<div class="container">
    <div class="row justify-content-center pt-2">
        :TODO <br>
        - Change to livewire <br>
        - Translate <br>
        - Preffer the select to change aproach on both create forced and move from Roles <br>
        <div class="col-sm-12 col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    Forced Timers
                </div>
                <div class="col-sm-6">
                    @livewire('timy::role-management')
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            @include('timy::_dispositions-pannel')
        </div>
    </div>
</div>
@endsection
