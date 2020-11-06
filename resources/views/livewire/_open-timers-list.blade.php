<div class="col-12">
    @if (!$timers || count($timers) == 0)
        <div class="alert alert-warning p-4 border mt-3 border-secondary" role="alert">
            <h3><strong>{{ __('timy::titles.no_timers_running') }}</strong></h3>
        </div>
    @else
        <table class="table table-hover bg-white m-0 table-sm border">
            <thead class="thead-inverse">
                <tr>
                    <th>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer;">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    @if ($all)
                                        checked
                                    @endif
                                    wire:change="toggleSelectAll()"
                                >
                                Toggle All
                            </label>
                        </div>
                        {{ __('timy::titles.user') }}
                    </th>
                    <th>{{ __('timy::titles.user_group') }}</th>
                    <th>{{ __('timy::titles.timer_started_at') }}</th>
                    <th>{{ __('timy::titles.disposition') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($timers as $timer)
                        <tr class="{{ in_array($timer['user_id'], $selected) ? 'text-primary font-italic font-weight-bold' : '' }}">
                            <td class="">
                                <div class="form-check">
                                    <label class="form-check-label" style="cursor: pointer;">
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
                            <td class="">{{ $timer['user_team'] }}</td>
                            <td class="">{{ $timer['started_at'] }}</td>
                            <td>
                                <span class="badge badge-{{ $timer['is_payable'] == 0 ? 'danger' : 'success' }}">{{ $timer['disposition'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>

        @if (count($selected))
            <div class="form-group position-fixed bg-light p-2 rounded border border-dark" style="top: 35%; left: 35%; z-index: 10000; max-width: 350px;">
                <label for="changeDispo" class="row flex-row justify-content-between px-4">
                    <div class="">
                        <span class="font-weight-bold">{{ __('timy::titles.change_selected') }}: </span>
                        <span class="badge badge-pill badge-info text-light m-2">{{ count($selected) }}</span>
                    </div>
                    <a href="#" class="btn btn-sm btn-secondary" 
                        title="{{ __("timy::titles.cancel") }}"
                        wire:click.prevent="resetSelectors"
                    > X </a>
                </label>
                <div class="">
                    <select 
                        class="form-control form-control-sm" 
                        name="changeDispo" 
                        id="changeDispo" 
                        wire:model="selectedDisposition"
                    >
                        <option></option>
                        @foreach ($dispositions as $disposition)
                            <option value="{{ $disposition->id }}"
                                class="text-light {{ $disposition->payable == 1 ? 'bg-success' : 'bg-danger' }}"    
                            >{{ $disposition->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedDisposition') 
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mt-3 row m-0 justify-content-between">                    
                    <a href="#" 
                        class="btn btn-sm btn-primary" 
                        title="{{ __("timy::titles.close_timer") }}"
                        wire:click.prevent="updateSelectedTimers"
                    > {{ __("timy::titles.update") }} </a>   

                    <a href="#" 
                        class="btn btn-sm btn-danger" 
                        title="{{ __("timy::titles.close_timer") }}"
                        wire:click.prevent="closeSelectedTimers"
                    > {{ __("timy::titles.close_timer") }} </a>
                </div>
            </div>
        @endif
    @endif
</div>