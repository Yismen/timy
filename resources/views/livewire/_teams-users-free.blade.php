@if (count($users_without_team) > 0)
<div class="card text-success mt-2 text-danger">
    <div class="card-header bg-light p-1">
            <h4 class="text-danger">
                {{ __('timy::titles.without_teams_header') }}
                <span class="badge badge-pill badge-danger float-right">{{ count($users_without_team) }}</span>
            </h4>
        </div>
        <div class="card-body m-0 p-0">
            @foreach ($users_without_team as $user)
                @include('timy::livewire._user-checkbox', ['user' => $user])
            @endforeach
        </div>
    </div>
@endif