<div class="position-relative text-danger">
    <div class="card">
        <div class="card-header bg-white">
            <h4>
                {{ __('timy::titles.forced_timer_title') }}
                <button class="btn btn-sm btn-secondary float-right" wire:click="getUsers()">
                    {{ __('timy::titles.update') }}
                </button>
            </h4>
        </div>
   
        <div class="card-body p-0">
            <ul class="list-group">
                @foreach ($users as $user)
                    @include('timy::livewire._user-checkbox', ['with_timers' => true])
                @endforeach
            </ul>
        </div>
        @if (count($selected) > 0)
            <div class="position-absolute" style="top: 20px; left: 100px;">
                <div class="position-fixed bg-danger row p-2 justify-content-between" style="z-index: 1000;">
                    <div class="col-8 row justify-content-between">
                        <div class="col-9">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
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
                            </div>
                        </div>
                        <div class="col-3">
                            <span class="badge badge-pill badge-secondary text-light">{{ count($selected) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-warning" wire:click="updateUsers">
                            {{ __('timy::titles.update') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>