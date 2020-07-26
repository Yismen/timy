@extends('timy::app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-4">
        <div class="col-sm-12 col-lg-7 ">
            <timy-admin-dashboard class="w-100"></timy-admin-dashboard>
        </div>
        <div class="col-sm-12 col-lg-5">
            <div class="mb-3">
                @include('timy::_download-hours')
            </div>
            <div>
                @include('timy::_user-hours-details')
            </div>
        </div>
    </div>
</div>
@endsection

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
