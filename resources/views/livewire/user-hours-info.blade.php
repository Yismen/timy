    <div wire:init='getInfoData'>
        @include('timy::_loading', ['target' => 'getInfoData'])
        <div class="row" wire:loading.remove wire:target='getInfoData'>
            <div class="mb-2 col-6 col-lg-4 col-xl-3">
                <x-timy-info-box
                    title="{{ __('timy::titles.hours_today') }}"
                    number="{{ $hours_today['hours'] ?? 0 }}"
                    tooltip="{{ $hours_today['date'] }}"
                ></x-timy-info-box>
            </div>
            <div class="mb-2 col-6 col-lg-4 col-xl-3">  
                <x-timy-info-box
                    title="{{ __('timy::titles.hours_last_date') }}"
                    number="{{ $hours_last_date['hours'] ?? 0 }}"
                    tooltip="{{ $hours_last_date['date'] }}"
                ></x-timy-info-box>
            </div>
            <div class="mb-2 col-6 col-lg-4 col-xl-3">
                <x-timy-info-box
                    title="{{ __('timy::titles.hours_this_payroll') }}"
                    number="{{ $hours_payrolltd['hours'] ?? 0 }}"
                    tooltip="{{ join(' - ', [$hours_payrolltd['since'], $hours_payrolltd['to']]) }}"
                ></x-timy-info-box>
            </div>
            <div class="mb-2 col-6 col-lg-4 col-xl-3">
                <x-timy-info-box
                    title="{{ __('timy::titles.hours_last_payroll') }}"
                    number="{{ $hours_last_payroll['hours'] ?? 0 }}"
                    tooltip="{{ join(' - ', [$hours_last_payroll['since'], $hours_last_payroll['to']]) }}"
                ></x-timy-info-box>
            </div>
        </div>
    </div>