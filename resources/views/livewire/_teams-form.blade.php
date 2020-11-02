<div class="card mb-3">
    <div class="card-header">
        <h5>
            {{ __('timy::titles.create_teams_form_header') }}
        </h5>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="createTeam" method="post" autocomplete="off">
            <div class="form-group">
                <label for="nameField">
                    {{ __('timy::titles.name') }} 
                    <span class="text-danger"> **</span>
                </label>
                <div class="input-group">
                    <input type="text"
                        wire:model.lazy="name"
                        class="form-control" name="nameField" id="nameField" 
                        aria-describedby="nameHelpId" placeholder="">     
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-primary text-uppercase" type="submit" id="button-addon1">{{ __('timy::titles.create') }}</button>
                        </div>                   
                </div>    
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </form>
    </div>
</div>