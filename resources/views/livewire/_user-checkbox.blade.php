    <li class="list-group-item p-1 px-3 rounded-0">
        <div class="form-check">
            <label class="form-check-label" style="display: block; cursor: pointer;">
            <input type="checkbox" 
                class="form-check-input"
                @if (in_array($user->id, $selected))
                    checked
                @endif
                value="{{ $user->id }}"
                wire:model="selected"
                >
            {{ $user->name }}
            @isset ($with_timers)
                @if ($with_timers)
                    @foreach ($user->timers as $timer)
                        <span class="text-light badge badge-pill badge-{{ $timer->is_payable == 1 ? 'success' : 'danger' }} float-right ">
                            {{ $timer->disposition->name }}
                        </span>
                    @endforeach
                @endif
            @endif
            </label>
        </div>
    </li>