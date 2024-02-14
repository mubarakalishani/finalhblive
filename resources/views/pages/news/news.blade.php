@include('includes.header-before-login')
<div class="container mt-3 mb-3">
    
    <div class="content-wrapper border p-3">
        <h5>{{ $latestNews->title }}</h5>
        <p>Last Updated: {{ $latestNews->updated_at->diffForHumans() }}</p>
        {!! $latestNews->content !!}
    </div>
    @foreach ($news as $item)
        <ul>
            <li>
                <a href="/guides-and-announcements/{{ $item->id }}">{{ $item->title }}</a> <br>
            </li>
        </ul>
    @endforeach
</div>
@include('includes.footer-before-login')