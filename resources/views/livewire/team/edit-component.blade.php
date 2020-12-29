<div>
    {{-- Edit form --}}
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="editTeamModal" tabindex="-1" role="dialog" aria-labelledby="editTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('timy::titles.edit_team_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="updateTeam({{ optional($this->team)->id }})">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="teamName" class="col-sm-2 col-form-label">{{ __('timy::titles.name') }}</label>
                            <div class="col-sm-10">
                                <input wire:model="team.name" type="text" name="teamName" id="teamName" class="form-control" aria-describedby="helpId">
                                @error('team.name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('timy::titles.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('timy::titles.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div wire:ignore.self class="modal fade" id="deleteTeamModal" tabindex="-1" role="dialog" aria-labelledby="deleteTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="exampleModalLabel">
                        {{ __('timy::titles.delete_team_title') }} - 
                        {{ $team->name }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <span class="text-danger">
                        {{ __('timy::titles.delete_team_body') }}
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('timy::titles.close') }}</button>
                    <button type="button" class="btn btn-danger" wire:click.prevent="removeTeam()">{{ __('timy::titles.delete') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push(config('timy.scripts_stack', 'scripts'))
<script>          
    (function() {
        window.addEventListener('show-edit-team-modal', event => {
            $('#editTeamModal').modal('show');
        });
        
        window.addEventListener('hide-edit-team-modal', event => {
            $('#editTeamModal').modal('hide');
        });
        window.addEventListener('show-delete-team-modal', event => {
            $('#deleteTeamModal').modal('show');
        });
        
        window.addEventListener('hide-delete-team-modal', event => {
            $('#deleteTeamModal').modal('hide');
        });
    })()
</script>
@endpush