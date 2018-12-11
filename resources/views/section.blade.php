@extends('layout')
@section('title', 'Barrilete')
@section('content')
@php ($i=0) @endphp
@forelse ($section as $sec)
@php ($i++) @endphp
    <article class="pubIndex">
        <div class="seccion" onclick="location.href ='{{ route('section', ['seccion' => str_slug($sec -> seccion)]) }}'">{{ $sec -> seccion }}</div>
        @if ($sec -> video == 1)<img src="{{ asset('img/play-button.png') }}" class="video" />@endif
        @if ($i == 1)
        <img src="{{ asset('img/articles/'.$sec -> foto) }}" title="{{ $sec -> titulo  }}" alt="{{ $sec -> titulo  }}" />
        @else
        <img src="{{ route('imgFirst', ['image'=>$sec->foto]) }}" title="{{ $sec -> titulo  }}" alt="{{ $sec -> titulo  }}" />
        @endif
        <a href="{{ route('article', ['id' => $sec -> id, 'seccion' => str_slug($sec -> seccion, '-') ,'titulo' => str_slug($sec -> titulo, '-')]) }}">{{ $sec -> titulo  }}</a>
        <p>{{ $sec -> copete }}</p>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection

