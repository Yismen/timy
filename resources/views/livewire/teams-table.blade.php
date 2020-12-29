<div class="mt-3">
{{-- <div class="mt-3" wire:ignore> --}}

    @livewire('timy::team-create-component')
    @livewire('timy::team-edit-component')

    @unless ($teams->count() > 0)
        <div class="alert alert-warning border-warning" role="alert">
            <strong>{{ __('timy::titles.no_teams_created_yet') }}</strong>
        </div>
    @else
        @include('timy::livewire._teams-list')
    @endunless
    @include('timy::livewire._teams-users-free')
    
    @if (count($selected) > 0)
        <div class="position-fixed" style="top: 60%; right: 25%; z-index: 1000; max-width: 300px;">
            <div class="bg-success row p-2 justify-content-between">
                <div class="col-12 mb-2">
                    <h5 class="text-dark row justify-content-between">
                        <div class="col-10 text-light">
                            {{ __('timy::titles.teams_management_form_title') }}
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
                            <select name="" id="" wire:model="selectedTeam" class="form-control">
                                <option></option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedTeam')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-3">
                            <span class="badge badge-pill badge-secondary text-light">{{ count($selected) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <button class="btn btn-sm btn-primary " wire:click="assignTeam">
                        {{ __('timy::titles.update') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

