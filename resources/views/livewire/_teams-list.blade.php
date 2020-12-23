<h4 class="mt-2">{{ __('timy::titles.teams_header') }}</h4>
@foreach ($teams as $team)
    <div class="card text-success mt-2">
        <div class="card-header p-1">
            <h5>
                {{ $team->name }} 
                <span class="badge badge-pill badge-success ml-2">{{ $team->users->count() }}</span>
                <a class="btn btn-sm btn-warning float-right" wire:click.prevent="editTeam({{ $team->id }})">{{ __('timy::titles.edit') }}</a>
            </h5>
        </div>

        <div class="card-body m-0 p-0">
            @foreach ($team->users as $user)
                @include('timy::livewire._user-checkbox', ['user' => $user])
            @endforeach
        </div>
    </div>
@endforeach

{{-- Edit form --}}
<!-- Modal -->
<div class="modal fade" id="editTeamModal" tabindex="-1" role="dialog" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-inline" wire:submit.prevent="updateTeam({{ $this->team->id }})">
                <div class="form-group">
                    <label for="teamName">{{ __('timy::titles.name') }}</label>
                    <input wire:model="team.name" type="text" name="teamName" id="teamName" class="form-control" aria-describedby="helpId">
                    {{-- <small id="helpId" class="text-muted">Help text</small> --}}
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  @push(config('timy.scripts_stack', 'scripts'))
  <script>
    window.addEventListener('show-edit-team-modal', event => {
        $('#editTeamModal').modal('show');
    });
  
    window.addEventListener('hide-edit-team-modal', event => {
        $('#editTeamModal').modal('hide');
      })
  </script>
  @endpush