<x-dc-input-field
    field-value="{{ old('name', optional($disposition ?? null)->name) }}"
    field-name='name'
    label-name="Disposition:"
/>
<div class="form-check">
    <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="payable" id="payable" value="1"
            @if (old('name', optional($disposition ?? null)->payable) == 1) checked @endif
        > Payable?
    </label>
    <label class="form-check-label float-right">
        <input class="form-check-input" type="checkbox" name="invoiceable" id="invoiceable" value="1"
            @if (old('name', optional($disposition ?? null)->invoiceable) == 1) checked @endif
        > Invoiceable?
    </label>
</div>