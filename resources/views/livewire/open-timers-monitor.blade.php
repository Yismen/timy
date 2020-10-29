<div class="position-relative">
    <h4>
        {{ __('timy::titles.open_timers_header') }} 
        <span class="badge badge-pill badge-info text-light">{{ count($timers) }}</span>
        <a href="#" class="float-right btn btn-secondary btn-sm" wire:click.prevent='getOpenTimers'>
            {{ __('timy::titles.refresh') }}
        </a>
    </h4>
    @include('timy::_loading', ['target' => 'getOpenTimers'])
    <div class="row" wire:loading.remove wire:target="getOpenTimers" wire:init="getOpenTimers">
        @include('timy::livewire._open-timers-list')
    </div>
    <div class="row mt-3" wire:loading.remove wire:target="getOpenTimers" wire:init="getOpenTimers">
        @include('timy::livewire._users-without-timers-list')
    </div>
</div>