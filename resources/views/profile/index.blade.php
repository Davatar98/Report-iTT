@extends('layouts.form')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="width: auto;">
                <div class="card-header" >{{ __('My Profile') }}</div>
                <div class="card-body">
                    
                    <div class="row mb-3 justify-content-center">
                  <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                  <label for="name">Name: </label>
                    <input name="name" type="text" value="{{ $profile->name }}">
                    
                 <label for="email">Email Address:</label>
                 <input type="email" name="email" value="{{ $profile->email }}">

                 <label for="phone"> Phone Number: </label>
                 <input name="phone" type="phone" value="{{ $number }}">

                 <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</div>
</div>

@endsection