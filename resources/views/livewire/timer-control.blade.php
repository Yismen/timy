<div class=""> 
    {{-- @include('timy::_loading', ['target' => 'updateUserDisposition'])    --}}
    <select 
        class="custom-select {{ isset($running['is_payable']) && $running['is_payable'] == 1 ? 'bg-success text-light' : 'bg-danger text-light' }}"
        wire:model='selectedDisposition'
        wire:loading.attr="disabled" wire:target="updateUserDisposition"
        wire:change.prevent='updateUserDisposition'
        name="selectedDisposition"
        id="selectedDisposition"
    >
    @foreach ($this->dispositions as $disposition)
        <option value="{{ $disposition->id }}"
            class="{{ $disposition->payable ? 'bg-success text-light' : 'bg-danger text-light' }}"
        >
            {{ $disposition->name }}{{ $disposition->payable ? ' - $$' : '' }}
        </option>
    @endforeach
    </select>
</div>

@push('scripts')
    <script>          
        window.onbeforeunload = function() {            
            fetch("{{ route('timy_timers.user_left', auth()->id()) }}")
                .finally(() => {
                    return true
                })
        };

        window.onbeforeunload
         
        window.addEventListener('timyShowAlert', event => {
            alert(event.detail.message)
        })
        
        setInterval(() => {
            fetch("{{ route('timy_ping_user') }}")
                .catch(error => window.location.reload())
        }, 5*60*1000) // Every five minutes
    </script>
@endpush
