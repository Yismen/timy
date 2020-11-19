<div class="mb-3">
    <h4>{{ __('timy::titles.role_management_title') }}</h4>
    @include('timy::_loading', ['target' => 'getRoles'])
    <div wire:loading.remove wire:target='getRoles'>
        @if ($unassigned->count() > 0)
            <div class="card mt-2 text-dark">
                <div class="card-header bg-white">
                    <h5>
                        {{ __('timy::titles.unasigned') }}
                        <span class="badge badge-primary badge-pill float-right">{{ $unassigned->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group">
                        @foreach ($unassigned as $user)
                            @include('timy::livewire._user-checkbox', ['with_timers' => false])
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        @foreach ($roles as $role)
            <div class="card mt-2  text-primary">
                <div class="card-header bg-white">
                    <h5>
                        {{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}
                        <span class="badge badge-primary badge-pill float-right">{{ $role->users->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach ($role->users as $user)
                        @include('timy::livewire._user-checkbox', ['with_timers' => false])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @if (count($selected) > 0)
        <div class="position-fixed" style="top: 40%; right: 20%; z-index: 1000; max-width: 300px;">
            <div class="bg-warning row py-3 justify-content-between">
                <div class="col-12 mb-2">
                    <h5 class="text-dark row justify-content-between">
                        <div class="col-10">
                            {{ __('timy::titles.roles_management_form_title') }}
                        </div>
                        <div class="col-2">
                            <a href="#" 
                                title="{{ __("timy::titles.cancel") }}"
                                class="float-right btn btn-sm btn-secondary" wire:click.prevent="closeForm"> X </a>
                        </div>
                    </h5>
                </div>
                <div class="col-8">
                    <div class="row justify-content-between">
                        <div class="col-9 input-group input-group-sm">
                            <select name="" id="" wire:model="selectedRole" class="form-control">
                                <option value=""></option>
                                <option value="{{ $remove_role_key }}" class="bg-danger border border-bottom text-white">{{ __('timy::titles.remove_assignment') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-3">
                            <span class="badge badge-pill badge-secondary text-light">{{ count($selected) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <button class="btn btn-sm btn-primary " wire:click="updateRoles">
                        {{ __('timy::titles.update') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>