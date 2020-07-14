@extends('timy::app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8 mt-4">
            <h4>
                Edit Disposition {{ $disposition->name }}
                <a href="{{ route('super_admin_dashboard') }}" class="float-right">List</a>
            </h4>
            <div class="card p-2">
                <x-dc-form route="{{ route('timy_web_disposition.update', $disposition->id) }}">
                    @include('timy::_dispositions-form')
                    @method('PUT')
                    <button type="submit" class="btn btn-warning mt-4">UPDATE</button>
                </x-dc-form>
            </div>
        </div>
    </div>
</div>
@endsection