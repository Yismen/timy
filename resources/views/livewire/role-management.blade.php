<div class="position-relative">
    <div class="card">
        <div class="card-header bg-white">
            <h4>{{ __('timy::titles.role_management_title') }}</h4>
        </div>
    </div>

    @if ($unassigned->count() > 0)
        <div class="card mt-2">
            <div class="card-header bg-white">
                <h5>
                    {{ __('timy::titles.unasigned') }}
                    <span class="badge badge-primary badge-pill float-right">{{ $unassigned->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group">
                    @foreach ($unassigned as $user)
                        @include('timy::livewire._user-checkbox')
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    
    @foreach ($roles as $role)
        <div class="card mt-2">
            <div class="card-header bg-white">
                <h5>
                    {{ Str::studly($role->name) }}
                    <span class="badge badge-primary badge-pill float-right">{{ $role->users->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @foreach ($role->users as $user)
                    @include('timy::livewire._user-checkbox')
                @endforeach
            </div>
        </div>
    @endforeach

    @if (count($selected) > 0)
        <div class="position-absolute" style="top: 20px; left: 150px;">
            <div class="position-fixed bg-warning row p-2 justify-content-between" style="z-index: 1000;">
                <div class="col-8">
                    <select name="" id="" wire:model="selectedRole">
                        <option value="">{{ __('timy::titles.remove_assignment') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ Str::studly($role->name) }}</option>
                        @endforeach
                    </select>
                    <span class="badge badge-pill badge-secondary text-light">{{ count($selected) }}</span>
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