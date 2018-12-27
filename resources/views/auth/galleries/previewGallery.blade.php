<h1>Preview del artículo</h1>
<p>así se verá la galería cuando se publique</p>
<hr />
<article class="pub_galeria">
    <p class="info"><img class="svg" src="{{asset('svg/calendar.svg')}}" /> {{$gallery->date}}</p>
    <h2>{{$gallery->title}}</h2>
    <p class="copete">{{$gallery->article_desc}}</p>
    <p class="info">
    <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$gallery->user->name}}
    <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$gallery->views}} lecturas
    </p>
    <br />
</article>
@forelse ($photos as $photo)
<article class="fotos">
    <img src="{{ asset('img/galleries/'.$photo->photo)}}" />
    <p>{{$photo->title}}</p>
</article>
@empty
    <h1>No hay fotos</h1>
@endforelse