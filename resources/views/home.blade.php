@extends('layout')
@section('title', 'Barrilete')
@section('content')
@php ($i=0) @endphp
@forelse ($titularesIndex as $tituloIndex)
    @php ($i++) @endphp
    <article class="pubIndex">
        <div class="seccion" onclick="location.href ='{{ route('section', ['seccion' => str_slug($tituloIndex -> seccion)]) }}'">{{ $tituloIndex -> seccion }}</div>
        @if ($tituloIndex -> video == 1)<img src="img/play-button.png" class="video" />@endif
        @if ($i == 1)
        <img src="img/articles/{{ $tituloIndex->foto }}" title="{{ $tituloIndex -> titulo  }}" alt="{{ $tituloIndex -> titulo  }}" />
        @else
        <img src="{{ route('imgFirst', ['image'=>$tituloIndex->foto]) }}" title="{{ $tituloIndex -> titulo  }}" alt="{{ $tituloIndex -> titulo  }}" />
        @endif
        <a href="{{ route('article', ['id' => $tituloIndex -> id, 'seccion' => str_slug($tituloIndex -> seccion), 'titulo' => str_slug($tituloIndex -> titulo, '-')]) }}">{{ $tituloIndex -> titulo  }}</a>
        <p>{{ $tituloIndex -> copete }}</p>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
<hr />
<div class="galeriasContainerIndex">
<h1>Galerías de fotos</h1>
@forelse ($galeriasIndex as $galeriaIndex)
    <article class="galeriaIndex">
        <img src="{{ route('imgSecond', ['image' => $galeriaIndex -> foto]) }}" title="{{ $galeriaIndex -> titulo  }}" alt="{{ $galeriaIndex -> titulo  }}" />
        <a href="{{ route('gallery', ['id' => $galeriaIndex -> id, 'titulo' => str_slug($galeriaIndex -> titulo, '-')]) }}">{{ $galeriaIndex -> titulo  }}</a>       
    </article>
@empty
<h1>NO HAY GALERÍAS PARA MOSTRAR</h1>
@endforelse
</div>
<div class="pollsContainerIndex">
<h1>Últimas encuestas</h1>
    @forelse ($pollsIndex as $pollIndex)
    <article class="pollIndex">
        <p>{{ $pollIndex -> fecha }}</p>
        <a href="#">{{ $pollIndex -> titulo  }}</a>
    </article>
@empty
<h1>NO HAY ENCUESTAS PARA MOSTRAR</h1>
@endforelse
</div>
@endsection

