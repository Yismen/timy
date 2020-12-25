<div class="text-danger">   
    @include('timy::_loading', ['target' => 'getUsers'])
    <div class="card mb-3" wire:loading.remove wire:target="getUsers" wire:init="getUsers">
        <div class="card-header bg-white">
            <h4>
                {{ __('timy::titles.forced_timer_title') }}
                <span class="badge badge-pill badge-danger">{{ $users ? $users->count() : 0 }}</span>
                <button class="btn btn-sm btn-secondary float-right" wire:click="getUsers">
                    {{ __('timy::titles.refresh') }}
                </button>
            </h4>
        </div>
   
        @if ($users)
            <div class="card-body p-0">
                <ul class="list-group">
                    @foreach ($users as $user)
                        @include('timy::livewire._user-checkbox', ['with_timers' => true])
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    @if (count($selected) > 0)
        <div class="position-fixed" style="top: 20%; left: 20%; z-index: 1000; width: 300px;">
            <div class="card-body bg-danger p-2" style="">
                <div class="row">
                    <div class="col-10 text-light">
                        <h4>{{ __('timy::titles.forced_timers_form_title') }}</h4>
                    </div>
                    <div class="col-2">
                        <a href="#" class="float-right btn btn-sm btn-secondary" 
                        title="{{ __("timy::titles.cancel") }}"
                        wire:click.prevent="closeForm"> X </a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-8">
                        <div class="row">
                            <div class="col-10 input-group-sm">
                                <select name="" id="" wire:model="selectedDisposition" class="form-control">
                                    <option value=""></option>
                                        @foreach ($dispositions as $disposition)
                                            <option 
                                                value="{{ $disposition->id }}"
                                                class="{{ $disposition->payable == 1 ? 'text-success font-weight-bold' : 'text-danger' }}"
                                            >{{ Str::title($disposition->name) }}</option>
                                        @endforeach
                                </select>
                                @error('selectedDisposition') <span class="text-light">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-2">
                                <span class="badge badge-pill badge-secondary text-light">{{ count($selected) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-warning" wire:click="createForcedTimers">
                            {{ __('timy::titles.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
