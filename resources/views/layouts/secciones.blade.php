<nav class="none">
    <ul>
        @forelse ($categories as $section)
        <li><a href="{{route('section',['seccion'=>$section->name])}}" title="{{$section->name}}">{{$section->name}}</a></li>
        @empty

        @endforelse 
    </ul>
</nav>
