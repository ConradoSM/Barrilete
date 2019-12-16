@foreach($comments as $comment)
    @include('comments.item', ['comment' => $comment])
@endforeach
