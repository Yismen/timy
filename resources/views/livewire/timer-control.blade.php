
    <div class="">
        <select 
            class="custom-select {{ isset($running['is_payable']) && $running['is_payable'] == 1 ? 'bg-success text-light' : 'bg-danger text-light' }}"
            wire:model='selectedDisposition'
            {{-- wire:ignore --}}
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
