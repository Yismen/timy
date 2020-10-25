@extends('timy::app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8 mt-4">
            <h4>
                {{ __('timy::titles.edit') }} {{ __('timy::titles.disposition') }} {{ $disposition->name }}
                <a href="{{ route('super_admin_dashboard') }}" class="float-right">{{ __('timy::titles.list') }}</a>
            </h4>
            <div class="card p-2">
                <x-dc-form route="{{ route('timy_web_disposition.update', $disposition->id) }}">
                    @include('timy::_dispositions-form')
                    @method('PUT')
                    <button type="submit" class="btn btn-warning mt-4">{{ __('timy::titles.update') }}</button>
                </x-dc-form>
            </div>
        </div>
    </div>
</div>
@endsection