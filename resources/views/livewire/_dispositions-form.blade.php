
<div class="form-group">
  <label for="dispositionName" class="font-bold {{ $this->isEditing ? 'text-danger' : '' }}">
    @if ($this->isEditing)        
     {{ __('timy::titles.edit') }} - {{ $disposition['name'] }}
    @else
        {{ __('timy::titles.create') }} {{ __('timy::titles.disposition') }}
    @endif
    </label>
  <input type="text"
    wire:model="disposition.name"
    class="form-control" name="dispositionName" id="dispositionName" aria-describedby="helpId" placeholder="">
    @error('disposition.name')<span class="text-danger">{{ $message }}</span>@enderror
</div>
<div class="form-check">
    <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="payable" id="payable" value="1"
            wire:model="disposition.payable"
        > {{ __('timy::titles.payable') }}?
    </label>
    <label class="form-check-label float-right">
        <input class="form-check-input" type="checkbox" name="invoiceable" id="invoiceable" value="1"
            wire:model="disposition.invoiceable"
        > {{ __('timy::titles.invoiceable') }}?
    </label>
</div>