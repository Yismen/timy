{{-- Edit form --}}
<!-- Modal -->
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