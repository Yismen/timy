@component('mail::message')
### {{ __('timy::titles.hello', ['name' => $notifiable->name]) }}.

{{ __('timy::titles.previous_date_hours_report.introduction') }}:

@component('mail::table')
| {{ __('timy::titles.name') }}  |  {{ __('timy::titles.date') }}  | {{ __('timy::titles.total_hours') }}    |
| ------------- | -------------:| --------:|
@foreach ($timy_users as $user)
| {{ $user->name }} | {{ now()->subDay()->format('Y-M-d') }} | {{ number_format($user->total_hours, 2) }} |
@endforeach
|        | ***Total:***  | ***{{ number_format($timy_users->sum('total_hours'), 2) }}*** |
@endcomponent

@component('mail::button', ['url' => route('admin_dashboard')])
{{ __('timy::titles.previous_date_hours_report.call_to_action') }}
@endcomponent

{{ __('timy::titles.thanks') }},<br>
{{ config('app.name') }}
@endcomponent