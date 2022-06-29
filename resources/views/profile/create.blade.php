@extends('layouts.form')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="width: auto;">
                <div class="card-header" >{{ __('My Profile') }}</div>
                <div class="card-body">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                  <form action="{{ route('profile.store') }}" method="POST">
                  <label for="name">Name: </label>
                    <input name="name" type="text" placeholder="{{ $profile->name }}">
                    
                 <label for="email">Email Address:</label>
                 <input type="email" name="email" placeholder="{{ $profile->email }}">

                 <label for="phone"> Phone Number: </label>
                 <input name="phone" type="text" placeholder="{{ $number}}">

                 <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</div>
</div>

@endsection