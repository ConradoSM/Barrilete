@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('content')
@php ($i=0) @endphp
@forelse ($articles as $sec)
@php ($i++) @endphp
    <article class="pubIndex">
        <div class="seccion" onclick="location.href ='{{ route('section', ['section' => $sec -> section -> name ]) }}'">{{ $sec -> section -> name }}</div>
        @if ($sec -> video == 1)<img src="{{ asset('img/play-button.png') }}" class="video" />@endif
        @if ($i == 1)
        <img src="{{ asset('/img/articles/images/'.$sec -> photo) }}" title="{{ $sec -> title  }}" alt="{{ $sec -> title  }}" />
        @else
        <img src="{{ asset('/img/articles/.thumbs/images/'.$sec->photo) }}" title="{{ $sec -> title  }}" alt="{{ $sec -> title  }}" />
        @endif
        <a href="{{ route('article', ['id' => $sec -> id, 'section' => $sec -> section -> name ,'title' => str_slug($sec -> title, '-')]) }}">{{ $sec -> title  }}</a>
        <p>{{ $sec -> article_desc }}</p>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection

