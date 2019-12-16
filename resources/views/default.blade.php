@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', 'Secciones de noticias, galerías de fotos, encuestas, toda la actualidad en un solo sitio')
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
    @forelse ($articlesIndex as $article)
        <article class="pubIndex translate">
            <img src="{{ asset('svg/placeholder.svg') }}" class="placeholder"/>
            <div class="seccion" onclick="location.href ='{{ route('section',['seccion'=>str_slug($article->section->name)]) }}'">{{ $article->section->name }}</div>
            @if ($article->video == 1)
                <img src="{{ asset('img/play-button.png') }}" class="video" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
            @endif
            <img src="{{ asset('img/before-load.png') }}" data-src="{{$loop->iteration == 1 ? asset('img/articles/images/'.$article->photo) : asset('img/articles/.thumbs/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" class="lazy" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
            <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}">{{ $article->title }}</a>
            <p>{{ ucfirst($article->created_at->diffForHumans()) }}</p>
        </article>
    @empty
        <h2>No hay artículos para mostrar</h2>
    @endforelse
    @if($galleryIndex)
        <div class="galeriasContainerIndex translate">
            <img src="{{ asset('svg/photo-camera.svg') }}" title="Galería de fotos" class="camera" />
            <img src="{{ asset('svg/placeholder.svg') }}" class="placeholder"/>
            <article class="galeriaIndex">
                <img src="{{ asset('img/before-load.png') }}" data-src="{{ asset('img/galleries/.thumbs/'.$galleryIndex->photos->first()->photo) }}" title="{{ $galleryIndex->title }}" alt="{{ $galleryIndex->title }}" class="lazy"/>
                <a href="{{ route('gallery',['id' => $galleryIndex->id, 'title' => str_slug($galleryIndex->title,'-')]) }}">{{ $galleryIndex->title }}</a>
            </article>
        </div>
    @endif
    @if($pollsIndex)
        <div class="pollsContainerIndex">
            <h1>Últimas encuestas</h1>
            @foreach ($pollsIndex as $pollIndex)
            <article class="pollIndex">
                <p>{{ ucfirst($pollIndex->created_at->diffForHumans()) }} · {{ $pollIndex->option->sum('votes') }} {{ $pollIndex->option->sum('votes') == 1 ? 'voto' : 'votos'}}</p>
                <a href="{{ route('poll',['id' => $pollIndex->id, 'title' => str_slug($pollIndex->title,'-')]) }}">{{ $pollIndex->title }}</a>
            </article>
            @endforeach
        </div>
    @endif
@endsection
