@extends('layouts.main')

@section('content')
    <form method="post" action="{{route('notes.save')}}">
        @csrf
        <select name="lead_id">
            @foreach($leads as $lead)
                <option value="{{$lead->getId()}}">{{$lead->getName()}}</option>
            @endforeach
        </select>
        <label> Note
            <input type="text" name="note_text">
        </label>

        <input type="submit">
    </form>
@endsection
