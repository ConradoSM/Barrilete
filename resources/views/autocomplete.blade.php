@forelse ($response as $key => $items)
    <hr />
    <span>{{$key}}</span>
    @forelse($items as $item)
        <a href="{{$item['url']}}">{{$item['title']}}</a>
    @empty
        <p>No hay resultados.</p>
    @endforelse
@empty
    <p>No hay resultados.</p>
@endforelse
