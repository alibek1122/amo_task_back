@extends('layouts.main')

@section('content')
    <form method="post" action="{{route('leads.save')}}">
        @csrf
        <select name="user_id">
            @foreach($users as $user)
                <option value="{{$user->getId()}}">{{$user->getName()}}</option>
            @endforeach
        </select>
        <label> Лид
            <input type="text" name="lead_name">
        </label>
        <input type="submit">
    </form>
@endsection
