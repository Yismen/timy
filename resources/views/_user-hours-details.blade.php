<h4>{{ __('timy::titles.user_details') }}</h4>
    <div class="card p-3">
        <div class="row">
            @foreach ($users as $chunk)
                <div class="col-6">
                    <ul class="list-group">
                        @foreach ($chunk as $user)
                        <li class="list-group-item p-1">
                            <a href="{{ route('timy_user_profile', $user->id) }}" 
                                onclick="fetchUserHours()"
                                class="timy-user-profile">{{ $user->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        @include('timy::_user-profile-modal')
    </div>
    
    @push(config('timy.scripts_stack', 'scripts'))
    <script>
        function fetchUserHours() {        
            
            let event = window.event
            let href = event.target.href
            let profileEl = document.getElementById('profile-content')
            event.preventDefault()
    
            profileEl.innerHTML = '<h1 class="p-5">Loading...</h1>'
    
           $('#userIdProfile').modal()
    
            fetch(href)
                .then(response => response.text())
                .then(html => profileEl.innerHTML = html)
        }
    </script>
    @endpush