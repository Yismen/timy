<h4>@lang("timy::titles.hours_download")</h4>
    <div class="card p-3">
        <x-dc-form route="{{ route('timy_hours_download') }}">
            <div class="row">
                <div class="col-sm-6">
                    <x-dc-input-field 
                        type="date"
                        field-value="{{ old('date_from', now()->startOfMonth()->format('Y-m-d')) }}"
                        field-name="date_from"
                        label-name="{{ __('From') }}:"
                    />
                </div>
                <div class="col-sm-6">
                    <x-dc-input-field 
                        type="date"
                        field-value="{{ old('date_to', now()->endOfMonth()->format('Y-m-d')) }}"
                        field-name="date_to"
                        label-name="{{ __('To') }}:"
                    />
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2">{{ __('Download') }}</button>
        </x-dc-form>
    </div>