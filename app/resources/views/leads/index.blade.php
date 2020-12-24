@extends('layouts.main')

@section('content')
    @if($leads)
        @foreach ($leads as $lead)
            <h3>Название {{$lead['name']}}</h3>
            <p>Ответственный {{$lead['resusr']}}</p>
            @foreach($lead['contacts'] as $contact)
                <p>Имя {{$contact['name']}}</p>
                <p>Имя {{$contact['phone']}}</p>
            @endforeach
        @endforeach
    @endif
@endsection
