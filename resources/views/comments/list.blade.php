@foreach($comments as $comment)
    @include('comments.item', ['comment' => $comment])
@endforeach
@if(method_exists($comments,'links'))
    {{$comments->links()}}
@endif
