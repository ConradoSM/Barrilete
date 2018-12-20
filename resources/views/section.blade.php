@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('content')
@php ($i=0) @endphp
@forelse ($articles as $sec)
@php ($i++) @endphp
    <article class="pubIndex">
        <div class="seccion" onclick="location.href ='{{ route('section', ['section' => $sec -> section -> section ]) }}'">{{ $sec -> section -> section }}</div>
        @if ($sec -> video == 1)<img src="{{ asset('img/play-button.png') }}" class="video" />@endif
        @if ($i == 1)
        <img src="{{ asset('img/articles/'.$sec -> photo) }}" title="{{ $sec -> title  }}" alt="{{ $sec -> title  }}" />
        @else
        <img src="{{ route('imgFirst', ['image'=>$sec->photo]) }}" title="{{ $sec -> title  }}" alt="{{ $sec -> title  }}" />
        @endif
        <a href="{{ route('article', ['id' => $sec -> id, 'section' => $sec -> section -> section ,'title' => str_slug($sec -> title, '-')]) }}">{{ $sec -> title  }}</a>
        <p>{{ $sec -> articles_desc }}</p>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection

