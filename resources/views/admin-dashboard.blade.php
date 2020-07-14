@extends('timy::app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="row">
            <div class="col-sm-12 col-lg-7">
                <timy-admin-dashboard class="w-100"></timy-admin-dashboard>
            </div>
            <div class="col-sm-12 col-lg-5">
                <h4>Download Hours</h4>
                <x-dc-form route="{{ route('timy_hours_download') }}">
                    <div class="row">
                        <div class="col-sm-6">
                            <x-dc-input-field 
                                type="date"
                                field-value="{{ old('date_from', now()->startOfMonth()->format('Y-m-d')) }}"
                                field-name="date_from"
                                label-name="From:"
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
        </div>
    </div>
</div>
@endsection
