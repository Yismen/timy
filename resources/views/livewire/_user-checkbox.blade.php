    <li class="list-group-item p-1 px-3 rounded-0">
        <div class="form-check">
            <label class="form-check-label" style="display: block; cursor: pointer;">
            <input type="checkbox" 
                class="form-check-input"
                @if (in_array($user->id, $selected))
                    checked
                @endif
                wire:change="toggleSelection({{ $user->id }})">
            {{ $user->name }}
            </label>
        </div>
    </li>