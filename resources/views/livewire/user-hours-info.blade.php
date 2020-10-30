    <div wire:init='getInfoData'>
        @include('timy::_loading', ['target' => 'getInfoData'])
        <div class="row" wire:loading.remove wire:target="getInfoData">
            <div class="mb-2 col-6 col-lg-4 col-xl-3">
                <x-timy-info-box
                    id="todaysHoursBox"
                    title="{{ __('timy::titles.hours_today') }}"
                    number="{{ $hours_today['hours'] ?? 0 }}"
                    tooltip="{{ $hours_today['date'] ?? '' }}"
                ></x-timy-info-box>
            </div>
            <div class="mb-2 col-6 col-lg-4 col-xl-3">  
                <x-timy-info-box
                    title="{{ __('timy::titles.hours_last_date') }}"
                    number="{{ $hours_last_date['hours'] ?? 0 }}"
                    tooltip="{{ $hours_last_date['date']  ?? '' }}"
                ></x-timy-info-box>
            </div>
            <div class="mb-2 col-6 col-lg-4 col-xl-3">
                <x-timy-info-box
                    id="thisPayrollBox"
                    title="{{ __('timy::titles.hours_this_payroll') }}"
                    number="{{ $hours_payrolltd['hours'] ?? 0 }}"
                    tooltip="{{ join(' - ', [$hours_payrolltd['since'] ?? '', $hours_payrolltd['to'] ?? '']) }}"
                ></x-timy-info-box>
            </div>
            <div class="mb-2 col-6 col-lg-4 col-xl-3">
                <x-timy-info-box
                    title="{{ __('timy::titles.hours_last_payroll') }}"
                    number="{{ $hours_last_payroll['hours'] ?? 0 }}"
                    tooltip="{{ join(' - ', [$hours_last_payroll['since'] ?? '', $hours_last_payroll['to'] ?? '']) }}"
                ></x-timy-info-box>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                var timerInterval = null;          
                
                function updateInfoboxesHours() {
                    let todaysHoursBox = document.querySelector('#todaysHoursBox h1')
                    let thisPayrollBox = document.querySelector('#thisPayrollBox h1')
                    let todaysHoursBoxHours = Number(todaysHoursBox.textContent)
                    let thisPayrollBoxHours = Number(thisPayrollBox.textContent)

                    clearInterval(timerInterval)
                    timerInterval = setInterval(() => {
                        fetch("{{ route('timy.getOpenTimersHours') }}")
                        .then(response => response.text())
                        .then(response => {
                            let runningHours = Number(JSON.parse(response).hours)

                            document.querySelector('#todaysHoursBox h1').innerText = Number(Number(runningHours + todaysHoursBoxHours).toFixed(2))
                            document.querySelector('#thisPayrollBox h1').innerText = Number(Number(runningHours + thisPayrollBoxHours).toFixed(2))
                        })
                        .catch(error => location.reload())
                    }, 35000)
                }

                updateInfoboxesHours();
                window.addEventListener('timerControlUpdated', function() {
                    updateInfoboxesHours();
                })
            })()
        </script>
    @endpush