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