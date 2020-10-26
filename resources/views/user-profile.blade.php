<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center">
                {{ $user->name }}
            </h3>
           <div class="row">
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                   @livewire('timy::info-box', [
                       'title' => __('timy::titles.hours_today'),
                       'data' => $data['hours_today']->hours ?? 0,
                       'tooltip' => $data['hours_today']->date ?? null,
                   ])
               </div>
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                    @livewire('timy::info-box', [
                        'title' => __('timy::titles.hours_last_date'),
                        'data' => $data['hours_last_date']->hours ?? 0,
                        'tooltip' => $data['hours_last_date']->date ?? null,
                    ])
               </div>
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                    @livewire('timy::info-box', [
                        'title' => __('timy::titles.hours_this_payroll'),
                        'data' => $data['hours_payrolltd']['hours'],
                        'tooltip' => join(' - ', [$data['hours_payrolltd']['since'], $data['hours_payrolltd']['to']])
                    ])
               </div>
               
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                    @livewire('timy::info-box', [
                        'title' => __('timy::titles.hours_last_payroll'),
                        'data' => $data['hours_last_payroll']['hours'],
                        'tooltip' =>  join(' - ', [$data['hours_last_payroll']['since'], $data['hours_last_payroll']['to']]),
                    ])
                </div>
            </div>

           <div class="row">               
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h4 class="font-weight-bold">@lang('timy::titles.user_details_title')</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm m-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('timy::titles.date') }}</th>
                                        <th>{{ __('timy::titles.hours') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['hours_daily'] as $daily)
                                        <tr>
                                            <td>{{ $daily->date }}</td>
                                            <td>{{ number_format($daily->hours, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>
