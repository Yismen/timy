<?php

return [
    'admin' => 'Administracion',
    'change_selected' => 'Cambiar los Seleccionados',
    'close_timer' => 'Cerrar Timer',
    'disposition' => 'Disposicion',
    'from' => 'Desde',
    'hours_download' => 'Descargar Horas',
    'menu_title' => 'Timy',
    'no_timers_running' => 'No Timers Running At This Moment. Nobody Logged In Apparently!',
    'open_timers_header' => 'Lista de Timers Abiertos',
    'out_of_shift' => "Timer no registrado. Nuestro turno corre de  " . config('timy.shift.starts_at') . " a " . config('timy.shift.ends_at') . " en los dias " . join(", ", config('timy.shift.working_days')) . ". You can contact your supervisor for more details",
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
