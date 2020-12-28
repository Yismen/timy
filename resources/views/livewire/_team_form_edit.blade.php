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