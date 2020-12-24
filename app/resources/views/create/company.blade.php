@extends('layouts.main')

@section('content')
    <form method="post" action="{{route('companies.save')}}">
        @csrf
        <select name="lead_id">
            @foreach($leads as $lead)
                <option value="{{$lead->getId()}}">{{$lead->getName()}}</option>
            @endforeach
        </select>
        <label> Компания
            <input type="text" name="company_name">
        </label>

        <input type="submit">
    </form>
@endsection
