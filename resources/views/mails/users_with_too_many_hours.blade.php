@component('mail::message')
### {{ __('timy::titles.hello', ['name' => $notifiable->name]) }}.

{{ __('timy::titles.users_with_too_many_hours.introduction', ['threshold' => $threshold]) }}:

@component('mail::table')
| {{ __('timy::titles.name') }}      | {{ __('timy::titles.total_hours') }}  |
| ------------- | --------:|
@foreach ($timy_users as $user)
| {{ $user->name }}     | {{ number_format($user->total_hours, 2) }}      |
@endforeach
@endcomponent

@component('mail::button', ['url' => route('admin_dashboard')])
{{ __('timy::titles.users_with_too_many_hours.call_to_action') }}
@endcomponent

{{ __('timy::titles.thanks') }},<br>
{{ config('app.name') }}
@endcomponent