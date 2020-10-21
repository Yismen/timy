<?php

return [
    'admin' => 'Admin',
    'change_selected' => 'Change Selected',
    'close_timer' => 'Close Timer',
    'disposition' => 'Disposition',
    'from' => 'From',
    'hours_download' => 'Download Hours',
    'menu_title' => 'Timy Dashboards',
    'no_timers_running' => 'No Timers Running At This Moment. Nobody Logged In Apparently!',
    'open_timers_header' => 'Open Timers List',
    'out_of_shoft' => "Timer not registered. Our shift runs from " . config('timy.shift.starts_at') . " to " . config('timy.shift.ends_at') . " on days " . join(", ", config('timy.shift.working_days')) . ". You can contact your supervisor for more details",
    'refresh' => 'Refresh',
    'stopped_remotedly' => 'Your timer has been stopped. Contact you supervisor',
    'super_admin' => 'Super Admin',
    'timer_started_at' => 'Timer Starte At',
    'to' => 'To',
    'updated_remotedly' => 'Timer updated remotedly. Most likely your supervisor updated your timer',
    'user_details' => 'User Details',
    'user_group' => 'User Group',
    'user' => 'User',
];
