@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', $galeria -> copete)
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
@php ($i=0) @endphp
@forelse ($galleries as $galeria)
@php ($i++) @endphp
    <article class="pubIndex">
        @if ($i == 1)
        <img src="{{ asset('img/articles/'.$galeria -> foto) }}" title="{{ $galeria -> titulo  }}" alt="{{ $galeria -> titulo  }}" />
        @else
        <img src="{{ route('imgFirst', ['image'=>$galeria->foto]) }}" title="{{ $galeria -> titulo  }}" alt="{{ $galeria -> titulo  }}" />
        @endif
        <a href="{{ route('gallery', ['id' => $galeria -> id, 'titulo' => str_slug($galeria -> titulo, '-')]) }}">{{ $galeria -> titulo  }}</a>
        <p>{{ $galeria -> copete }}</p>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection

