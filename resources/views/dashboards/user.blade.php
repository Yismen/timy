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
            <div class="col-12 mb-2">
                <div id="hours" style="height: 250px;" class="border bg-white">
                    {!! $chart->container() !!}
                </div>
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

@push('scripts')    
    <!-- Charting library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $chart->script() !!}
@endpush
