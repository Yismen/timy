<div class="form-group">
    <select 
        class="custom-select {{ $running['is_payable'] ? 'bg-success text-white' : 'bg-danger text-white' }}"
        wire:model='selectedDisposition'
        wire:change='updateUserDisposition'
    >
    @foreach ($dispositions as $disposition)
        <option
            value="{{ $disposition->id }}"
            @if ($disposition->id === $current_disposition_id)
                selected
            @endif
            class="{{ $disposition->payable ? 'bg-success text-white' : 'bg-danger text-white' }}"
        >
            {{ $disposition->name }}{{ $disposition->payable ? ' - $$' : '' }}
        </option>
    @endforeach
    </select>
</div>
