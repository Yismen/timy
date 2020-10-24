@extends(config('app.env') == "package_development" ? 'timy::local-layout' : config('timy.layout'))

@section('content')
<div class="container">
    <div class="row justify-content-center pt-2">
        <div class="col-sm-12 col-md-8">
            <div class="row">
                <div class="col-lg-6 mb-sm-2">
                    @livewire('timy::forced-timer-management')
                </div>
                <div class="col-lg-6">
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
