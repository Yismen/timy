@if(config('app.env') == "package_development")
@extends('timy::local')
@else
Layout
{{-- @extends('layouts.app') --}}
@endif