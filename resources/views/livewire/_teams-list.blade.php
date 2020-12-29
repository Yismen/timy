<h4 class="mt-2">{{ __('timy::titles.teams_header') }}</h4>
@foreach ($teams as $team)
    <div class="card text-success mt-2">
        <div class="card-header p-1">
            <h5>
                {{ $team->name }} 
                <span class="badge badge-pill badge-success ml-2">{{ $team->users->count() }}</span>
                <div class="float-right">
                    <a class="btn btn-sm btn-danger mx-2" wire:click.prevent="removeTeam({{ $team->id }})">{{ __('timy::titles.delete') }}</a>
                    <a class="btn btn-sm btn-warning" wire:click.prevent="editTeam({{ $team->id }})">{{ __('timy::titles.edit') }}</a>
                </div>
            </h5>
        </div>

        <div class="card-body m-0 p-0">
            @foreach ($team->users as $user)
                @include('timy::livewire._user-checkbox', ['user' => $user])
            @endforeach
        </div>
    </div>
@endforeach

