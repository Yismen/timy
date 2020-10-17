<div class="position-relative">
    <h4>
        {{ __('timy::titles.open_timers_header') }}
        <a href="#" class="float-right btn btn-secondary btn-sm" wire:click.prevent='userChangedTimer'>
            {{ __('timy::titles.refresh') }}
        </a>
    </h4>
    @if (!$timers || count($timers) == 0)
        <div class="alert alert-warning" role="alert">
            <strong>{{ __('timy::titles.no_timers_running') }}</strong>
        </div>
    @else
        <table class="table table-hover bg-white m-0 table-sm">
            <thead class="thead-inverse">
                <tr>
                    <th>{{ __('timy::titles.user') }}</th>
                    <th>{{ __('timy::titles.user_group') }}</th>
                    <th>{{ __('timy::titles.timer_started_at') }}</th>
                    <th>{{ __('timy::titles.disposition') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($timers as $timer)
                        <tr class="{{ in_array($timer['user_id'], $selected) ? 'text-danger font-italic font-weight-bold' : '' }}">
                            <td class="">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" 
                                            class="form-check-input" 
                                            @if (in_array($timer['user_id'], $selected))
                                                checked
                                            @endif
                                            wire:change="toggleSelected({{ $timer['user_id'] }})">
                                        {{ $timer['name'] }}
                                    </label>
                                </div>
                            </td>
                            <td class="">{{ $timer['user_created_at'] }}</td>
                            <td class="">{{ $timer['started_at'] }}</td>
                            <td>
                                <span class="badge badge-{{ $timer['is_payable'] == 0 ? 'danger' : 'success' }}">{{ $timer['disposition'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>

        @if (count($selected))
            <div class="form-group position-fixed bg-secondary text-light p-2 rounded border border-dark" style="bottom: 35%; left: 35%; z-index: 10000; width: 240px;">
                <label for="changeDispo" class="position-relative w-100">{{ __('timy::titles.change_selected') }}: 
                    <span class="badge badge-pill badge-info text-light">{{ count($selected) }}</span>
                    <a href="#" class="btn btn-sm btn-danger float-right" 
                        title="{{ __("timy::titles.close_timer") }}"
                        wire:click.prevent="closeSelectedTimers"
                    > X </a>
                </label>
                <select 
                    class="form-control form-control-sm" 
                    name="changeDispo" 
                    id="changeDispo" 
                    wire:model="selected_to_change"
                    wire:change='updateSelectedTimers'>
                    <option></option>
                    @foreach ($dispositions as $disposition)
                        <option value="{{ $disposition->id }}"
                            class="text-light {{ $disposition->payable == 1 ? 'bg-success' : 'bg-danger' }}"    
                        >{{ $disposition->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    @endif
</div>