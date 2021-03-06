@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', 'Secciones de noticias, galerías de fotos, encuestas, toda la actualidad en un solo sitio')
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
    @if($breakingNews)
    <article class="breaking-news">
        <h1>Último momento <img src="{{ asset('svg/delete.svg') }}" alt="Cerrar" title="Cerrar" id="close-breakingnews"></h1>
        <div class="breaking-news-content">
            <div>
                <img class="lazy breaking-news-image" src="{{ asset('img/before-load.png') }}" data-src="{{asset('img/articles/.thumbs/'.$breakingNews->photo) }}" title="{{ $breakingNews->title }}" alt="{{ $breakingNews->title }}" />
            </div>
            <div>
                <h2><a href="{{ route('article',['id'=>$breakingNews->id,'section'=>str_slug($breakingNews->section->name),'title'=>str_slug($breakingNews->title,'-')]) }}">{{ $breakingNews->title }}</a></h2>
                <p>{{ $breakingNews->article_desc }}</p>
            </div>
        </div>
    </article>
    @endif
    <div class="articles-index">
    @forelse ($articlesIndex as $article)
        <article class="pubIndex">
            <span class="section" onclick="location.href ='{{ route('section',['name'=>str_slug($article->section->name)]) }}'">{{ $article->section->name }}</span>
            @if ($article->video == 1)
                <img class="video" alt="video" src="{{ asset('img/play-button.png') }}" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
            @endif
            <img class="lazy article-image" src="{{ asset('img/before-load.png') }}" data-src="{{$loop->iteration == 1 ? asset('img/articles/images/'.$article->photo) : asset('img/articles/.thumbs/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
            <a class="article-link" href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}">{{ $article->title }}</a>
            <span class="article-date">{{ ucfirst($article->created_at->diffForHumans()) }}</span>
        </article>
    @empty
        <h2>No hay artículos para mostrar</h2>
    @endforelse
    </div>
    @if($galleryIndex)
        <article class="gallery-container">
            <img class="camera" title="Galería de fotos" alt="galeria" src="{{ asset('svg/photo-camera.svg') }}" />
            <img class="lazy main-photo" src="{{ asset('img/before-load.png') }}" data-src="{{ asset('img/galleries/.thumbs/'.$galleryIndex->photos->first()->photo) }}" title="{{ $galleryIndex->title }}" alt="{{ $galleryIndex->title }}"/>
            <a class="gallery-link" href="{{ route('gallery',['id' => $galleryIndex->id, 'title' => str_slug($galleryIndex->title,'-')]) }}">{{ $galleryIndex->title }}</a>
        </article>
    @endif
    @if($pollsIndex)
        <article class="polls">
            <h2><img src="{{ asset('svg/poll_blue.svg') }}">Últimas encuestas</h2>
            @foreach ($pollsIndex as $pollIndex)
                @php($fromDate = \Carbon\Carbon::parse($pollIndex->valid_from))
                @php($toDate = \Carbon\Carbon::parse($pollIndex->valid_to))
                @php($now = \Carbon\Carbon::now())
            <p>
                <span class="poll-info">
                    {{ ucfirst($pollIndex->created_at->diffForHumans()) }} ·
                    {{ $pollIndex->option->sum('votes') }} {{ $pollIndex->option->sum('votes') == 1 ? 'voto' : 'votos'}}
                    @if($toDate->isPast())
                    · <text style="color: #922B21">Encuesta cerrada</text>
                    @elseif($now->between($fromDate, $toDate))
                    · <text style="color: #1D8348">Encuesta activa</text>
                    @endif
                </span>
                <a class="poll-link" href="{{ route('poll',['id' => $pollIndex->id, 'title' => str_slug($pollIndex->title,'-')]) }}">{{ $pollIndex->title }}</a>
            </p>
            @endforeach
        </article>
    @endif
    <section class="ranking">
        <div class="most">
            <h2><img src="{{ asset('svg/eye_blue.svg') }}">Más leídas</h2>
            @forelse($mostSeen as $post)
            <p>
                <span>{{ $post->views }} Lecturas · {{ ucfirst($post->created_at->diffForHumans()) }}</span>
                <a href="{{ route('article',['id'=>$post->id,'section'=>str_slug($post->section->name),'title'=>str_slug($post->title,'-')]) }}">{{ $post->title }}</a>
            </p>
            <hr>
            @empty
            @endforelse
        </div>
        <div class="most">
            <h2><img src="{{ asset('svg/like_blue.svg') }}">Más me gusta</h2>
            @forelse($mostLikes as $post)
            <p>
                <span>{{ $post->reactions_count }} Me gusta · {{ ucfirst($post->created_at->diffForHumans()) }}</span>
                <a href="{{ route('article',['id'=>$post->id,'section'=>str_slug($post->section->name),'title'=>str_slug($post->title,'-')]) }}">{{ $post->title }}</a>
            </p>
            <hr>
            @empty
            @endforelse
        </div>
        <div class="most">
            <h2><img src="{{ asset('svg/dialogue_blue.svg') }}">Más comentadas</h2>
            @forelse($mostCommented as $post)
            <p>
                <span>{{ $post->comments_count }} Comentarios · {{ ucfirst($post->created_at->diffForHumans()) }}</span>
                <a href="{{ route('article',['id'=>$post->id,'section'=>str_slug($post->section->name),'title'=>str_slug($post->title,'-')]) }}">{{ $post->title }}</a>
            </p>
            <hr>
            @empty
            @endforelse
        </div>
    </section>
@endsection
