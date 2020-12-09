<div class="position-relative">
    <h4>
        {{ __('timy::titles.open_timers_header') }} 
        <span class="badge badge-pill badge-info text-light">{{ count($timers) }}</span>
    </h4>
    <div class="d-flex justify-content-between align-items-center bg-white border mb-2 p-2">
        <div class="row align-items-center">
            <div class="col-auto">
                <label for="Teams">{{ __('timy::titles.filter_by_teams') }}</label>                    
            </div>
            <div class="col-auto">
                <select class="custom-select" name="Teams" id="Teams" wire:model="team" wire:change="getOpenTimers">
                    <option selected></option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <a href="#" class="btn btn-secondary btn-sm" wire:click.prevent='getOpenTimers'>
                {{ __('timy::titles.refresh') }}
            </a>
        </div>
    </div>
    @include('timy::_loading', ['target' => 'getOpenTimers'])
    <div class="row" wire:loading.remove wire:target="getOpenTimers" wire:init="getOpenTimers">
        @include('timy::livewire._open-timers-list')
    </div>
    <div class="row mt-3" wire:loading.remove wire:target="getOpenTimers">
        @include('timy::livewire._users-without-timers-list')
    </div>
</div>