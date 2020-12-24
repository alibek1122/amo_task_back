@extends('layouts.main')

@section('content')
    <form method="post" action="{{route('contacts.save')}}">
        @csrf
        <label>Лид
            <select name="lead_id">
                @foreach($leads as $lead)
                    <option value="{{$lead->getId()}}">{{$lead->getName()}}</option>
                @endforeach
            </select>
        </label>

        <label> Компания
            <select name="company_id">
                @foreach($companies as $company)
                    <option value="{{$company->getId()}}">{{$company->getName()}}</option>
                @endforeach
            </select>
        </label>
        <label> Контакт
            <input type="text" name="contact_name">
        </label>
        <input type="submit">
    </form>
@endsection
