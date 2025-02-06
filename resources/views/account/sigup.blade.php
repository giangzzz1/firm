@extends('account.master')

@section('title')
    Sign Up
@endsection

@section('content')
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <input type="text" id="login" class="fadeIn second" name="email" placeholder="Email" required>
        <input type="password" id="password" class="fadeIn third" name="password" placeholder="Password" required>
        <input type="password" id="password" class="fadeIn third" name="password_confirmation"
            placeholder="Confirm Password" required>
        <input type="text" id="referral_code" class="fadeIn second" name="referral_code"
            placeholder="Referral Code (optional)">
        <input type="submit" class="fadeIn fourth" value="Sign Up">
    </form>
@endsection
