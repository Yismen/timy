<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center">
                {{ $user->name }}
            </h3>
           <div class="row">
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                   <div class="card">
                       <div class="card-body">
                        <h5>@lang('Today')</h5>
                        <h1 class="text-center font-weight-bold" title="{{ $data['hours_today']->date ?? null }}">
                            {{ number_format($data['hours_today']->hours ?? 0, 2) }}
                         </h1>
                       </div>
                   </div>
               </div>
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                   <div class="card">
                       <div class="card-body">
                        <h5>@lang('Previous')</h5>
                        <h1 class="text-center font-weight-bold" title="{{ $data['hours_last_date']->date ?? null }}">
                            {{ number_format($data['hours_last_date']->hours ?? 0, 2) }}
                         </h1>
                       </div>
                   </div>
               </div>
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                   <div class="card">
                       <div class="card-body">
                        <h5>@lang('PTD')</h5>
                        <h1 class="text-center font-weight-bold" title="{{ $data['hours_payrolltd']->date ?? null }}">
                            {{ number_format($data['hours_payrolltd']->hours ?? 0, 2) }}
                         </h1>
                       </div>
                   </div>
               </div>
               
               <div class="col-6 col-lg-4 col-xl-3 mb-3">
                <div class="card">
                    <div class="card-body">
                     <h5>@lang('Last P.')</h5>
                     <h1 class="text-center font-weight-bold" title="{{ $data['hours_last_payroll']->date ?? null }}">
                         {{ number_format($data['hours_last_payroll']->hours ?? 0, 2) }}
                      </h1>
                    </div>
                </div>
            </div>
           </div>

           <div class="row">
               
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h4 class="font-weight-bold">@lang('Last 17 Dates')</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm m-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Hours</th>
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
