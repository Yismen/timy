<div class="col-12">
    @if ($usersWithoutTimers && $usersWithoutTimers->count() > 0)
        <h4>
            {{ __('timy::titles.users_without_timers') }}
            <span class="badge badge-pill badge-danger text-light">{{ count($usersWithoutTimers) }}</span>
        </h4>
        <table class="table table-hover bg-white m-0 table-sm text-danger border">
            <thead class="thead-inverse">
                <tr>
                    <th> {{ __('timy::titles.user') }} </th>
                    <th>{{ __('timy::titles.user_group') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($usersWithoutTimers as $user)
                        <tr class="">
                            <td class=""> {{ $user['name'] }} </td>
                            <td class="">{{ $user['user_created_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
    @endif
</div>