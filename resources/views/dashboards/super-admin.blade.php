@extends(config('app.env') == "testing" ? 'timy::testing-app' : config('timy.layout'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8">
            <div class="row">
                <div class="col-lg-6 mb-sm-2">
                    @livewire('timy::forced-timer-management')

                    <div class="row">
                        <div class="col-12 border-top">
                            <h4 class="mt-2">{{ __('timy::titles.teams_app_header') }}</h4>
                            @livewire('timy::teams-table')
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    @livewire('timy::role-management')
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            @livewire('timy::dispositions')
        </div>
    </div>
</div>
@endsection
