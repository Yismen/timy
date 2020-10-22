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
            @if ($with_timers)
                @foreach ($user->timers as $timer)
                    <span class="badge badge-pill badge-info float-right text-light">{{ $timer->disposition->name }}</span>
                @endforeach
            @endif
            </label>
        </div>
    </li>