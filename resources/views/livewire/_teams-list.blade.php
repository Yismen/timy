<h4 class="mt-2">{{ __('timy::titles.teams_header') }}</h4>
@foreach ($teams as $team)
    <div class="card text-success mt-2">
        <div class="card-header p-1">
            <h5>
                {{ $team->name }} 
                <span class="badge badge-pill badge-success float-right">{{ $team->users()->count() }}</span>
            </h5>
        </div>

        <div class="card-body m-0 p-0">
            @foreach ($team->users as $user)
                @include('timy::livewire._user-checkbox', ['user' => $user])
            @endforeach
        </div>
    </div>
@endforeach