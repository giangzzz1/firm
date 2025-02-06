@extends('account.master')

@section('title')
    Sign In
@endsection

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="text" id="login" class="fadeIn second" name="email" placeholder="Email" required>
        <input type="password" id="password" class="fadeIn third" name="password" placeholder="Password" required>
        <input type="submit" class="fadeIn fourth" value="Log In">
    </form>
@endsection
