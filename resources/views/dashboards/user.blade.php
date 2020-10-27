@extends(config('app.env') == "testing" ? 'timy::testing-app' : config('timy.layout'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-12">
                    @livewire('timy::user-hours-info')
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @livewire('timy::timers-table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
