@extends(config('app.env') == "package_development" ? 'timy::local-layout' : config('timy.layout'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="mb-2 col-6 col-lg-4 col-xl-3">
                    @livewire('timy::info-box', [
                        'title' => __('timy::titles.hours_today'), 'data' => $hours_today['hours'] ?? 0, 'tooltip' => $hours_today['date'] ?? ''
                    ])
                </div>
                <div class="mb-2 col-6 col-lg-4 col-xl-3">  

                    @livewire('timy::info-box', [
                        'title' => __('timy::titles.hours_last_date'), 
                        'data' => $hours_last_date['hours'] ?? 0, 'tooltip' => $hours_last_date['date'] ?? ''
                    ])
                </div>
                <div class="mb-2 col-6 col-lg-4 col-xl-3">
                    @livewire('timy::info-box', [
                            'title' => __('timy::titles.hours_this_payroll'), 'data' => $hours_payrolltd['hours'] ?? 0, 'tooltip' => ''
                    ])
                </div>
                <div class="mb-2 col-6 col-lg-4 col-xl-3">
                    @livewire('timy::info-box', ['title' => __('timy::titles.hours_last_payroll'), 'data' => $hours_last_payroll['hours'] ?? 0, 'tooltip' => ''])
                </div>
            </div>
            
            <div class="row">
                {{-- @dump($hours_daily) --}}
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
