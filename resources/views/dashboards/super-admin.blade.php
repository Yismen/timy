@extends(config('app.env') == "package_development" ? 'timy::local-layout' : config('timy.layout'))

@section('content')
<div class="container">
    <div class="row justify-content-center pt-2">
        :TODO <br>
        - Change to livewire <br>
        - Translate <br>
        - Preffer the select to change aproach on both create forced and move from Roles <br>
        <div class="col-sm-12 col-md-8">
            <timy-super-admin-dashboard></timy-super-admin-dashboard>
        </div>
        <div class="col-sm-12 col-md-4">
            <h4>Dispositions</h4>
            <div class="card">
                <div class="card-body m-0">
                    <x-dc-form route="{{ route('timy_web_disposition.store') }}">
                        @include('timy::_dispositions-form')
                        <button type="submit" class="btn btn-primary mt-4">CREATE</button>
                    </x-dc-form>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body m-0 p-0">
                    <table class="table table-hover table-responsive-xl w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Payable</th>
                                <th>Invoiceable</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dispositions as $disposition)
                                <tr>
                                    <td>{{ $disposition->name }}</td>
                                    <td>{{ $disposition->payable }}</td>
                                    <td>{{ $disposition->invoiceable }}</td>
                                    <td>
                                        <a href="{{ route('timy_web_disposition.edit', $disposition->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
