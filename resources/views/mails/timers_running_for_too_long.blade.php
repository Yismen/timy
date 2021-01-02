@component('mail::message')
### {{ __('timy::titles.hello', ['name' => $name]) }}.

{{ __('timy::titles.timers_too_long.introduction', ['threshold' => $threshold]) }}:

@component('mail::table')
| {{ __('timy::titles.name') }}      | {{ __('timy::titles.started_at') }}        | {{ __('timy::titles.total_hours') }}  |
| ------------- |:-------------:| --------:|
@foreach ($timers as $timer)
| {{ $timer->name }}      | {{ $timer->started_at->format('Y-M-d H:i:s') }} | {{ number_format(now()->diffInMinutes($timer->started_at) / 60, 2) }}      |
@endforeach
@endcomponent

@component('mail::button', ['url' => route('admin_dashboard')])
{{ __('timy::titles.timers_too_long.call_to_action') }}
@endcomponent

{{ __('timy::titles.thanks') }},<br>
{{ config('app.name') }}
@endcomponent