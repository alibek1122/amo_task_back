@extends('layouts.main')

@section('content')
    <form method="post" action="{{route('tasks.save')}}">
        @csrf
        <select name="lead_id">
            @foreach($leads as $lead)
                <option value="{{$lead->getId()}}">{{$lead->getName()}}</option>
            @endforeach
        </select>
        <label> Task
            <input type="text" name="task_text">
        </label>

        <input type="submit">
    </form>
@endsection
