<div>
    <h4 id="disposition_title">{{ __('timy::titles.dispositions') }}</h4>
    <div class="card">
        <div class="card-body m-0">
            <form
                autocomplete="off"
                @if ($this->isEditing)
                    wire:submit.prevent="updateDisposition"
                @else
                    wire:submit.prevent="createDisposition"
                @endif 
            >
                @include('timy::livewire._dispositions-form')
                @if ($this->isEditing)
                    <button type="submit" class="btn btn-warning mt-4 text-uppercase">{{ __("timy::titles.update") }}</button>
                    <button type="reset" class="btn btn-secondary mt-4 float-right" wire:click.prevent="resetForm">{{ __("timy::titles.cancel") }}</button>                
                @else
                    <button type="submit" class="btn btn-primary mt-4 text-uppercase">{{ __("timy::titles.create") }}</button>
                @endif
            </form>
        </div>
    </div>
    
    <div class="card mt-2">
        <div class="card-body m-0 p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm m-0">
                    <thead>
                        <tr>
                            <th>{{ __('timy::titles.name') }}</th>
                            <th>{{ __('timy::titles.payable') }}</th>
                            <th>{{ __('timy::titles.invoiceable') }}</th>
                            <th>{{ __('timy::titles.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dispositions as $disposition)
                            <tr>
                                <td>{{ $disposition->name }}</td>
                                <td>{{ $disposition->payable }}</td>
                                <td>{{ $disposition->invoiceable }}</td>
                                <td>
                                    <a class="btn btn-warning btn-sm" wire:click.prevent="editDisposition({{ $disposition }})">{{ __('timy::titles.edit') }}</a>
                                    {{-- <a href="{{ route('timy_web_disposition.edit', $disposition->id) }}" class="btn btn-warning btn-sm">{{ __('timy::titles.edit') }}</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push(config('timy.scripts_stack', 'scripts'))
    <script>
        (function() {
            window.addEventListener('editingDisposition', (event) => {
                document.getElementById('disposition_title').scrollIntoView({
                    behavior: 'smooth' 
                });
            })
        })()
    </script>
@endpush