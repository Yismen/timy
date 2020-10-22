<div class="">    
    TODO: <br />
- Create a command to check if the ip is active and the user is authenticated <br />
- Resolver intermitencia al refrescar. Esta que una vez lo crea y otra elige el anterior. <br />
    <select 
        class="custom-select {{ isset($running['is_payable']) && $running['is_payable'] == 1 ? 'bg-success text-light' : 'bg-danger text-light' }}"
        wire:model='selectedDisposition'
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
        window.addEventListener('timyShowAlert', event => {
            alert(event.detail.message)
        })
        
        setInterval(() => {
            fetch("{{ route('timy_ping_user') }}")
                .catch(error => window.location.reload())
        }, 5*60*1000) // Every five minutes

        window.onbeforeunload = () => {
            fetch("{{ route('timy_timers.user_left', auth()->id()) }}")
                .then(() => {
                    return true
                })
        };
    </script>
@endpush
