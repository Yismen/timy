<div class="card p-0">
    <div class="card-header bg-white">
        <h4>
            {{ __('timy::titles.timers_registered') }}
        
            <a href="#" class="float-right btn btn-secondary btn-sm" wire:click.prevent='getTimers'>
                {{ __('timy::titles.refresh') }}
            </a>
        </h4>
    </div>
    @include('timy::_loading', ['target' => 'getTimers'])
    <div wire:loading.remove wire:target='getTimers'>
        <div class="card-body p-0">
            <table class="table table-striped table-sm table-inverse table-responsive-sm w-full">
                <thead class="thead-inverse">
                    <tr>
                        <th>{{ __('timy::titles.disposition') }}</th>
                        <th>{{ __('timy::titles.started_at') }}</th>
                        <th>{{ __('timy::titles.finished_at') }}</th>
                        <th>{{ __('timy::titles.total_hours') }}</th>
                        <th>{{ __('timy::titles.payable_hours') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($timers as $timer)
                            <tr class="{{ $timer->finished_at == null ? 'text-primary' : ($timer->is_payable == 1 ? 'text-success' : 'text-danger') }}">
                                <td>{{ $timer->disposition->name }}</td>
                                <td>{{ $timer->started_at }}</td>
                                <td>{{ $timer->finished_at }}</td>
                                <td>{{ number_format($timer->total_hours, 2) }}</td>
                                <td>{{ number_format($timer->payable_hours, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
            </table>
        </div>
    
        <div class="card-footer bg-white p-1">
            {{ $timers->links() }}
        </div>
    </div>
</div>