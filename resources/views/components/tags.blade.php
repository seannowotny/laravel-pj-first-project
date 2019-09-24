<p>
    @foreach($tags as $tag)
        <a href="#" class="badge badge-lg badge-success">{{ $tag->name }}</a>
    @endforeach
</p>
