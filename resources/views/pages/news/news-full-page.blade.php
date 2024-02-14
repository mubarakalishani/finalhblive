@include('includes.header-before-login')
<div class="container mt-3 mb-3">
    
    <div class="content-wrapper border p-3">
        <h4>{{ $newsToShow->title }}</h4>
        <p>Last Updated: {{ $newsToShow->updated_at->diffForHumans() }}</p>
        {!! $newsToShow->content !!}

        <h6 class="mt-3 mb-3">More Guides and Announcements:</h6>
        @foreach ($news as $item)
            <ul>
                <li>
                    <a href="/guides-and-announcements/{{ $item->id }}">{{ $item->title }}</a> <br>
                </li>
            </ul>
        @endforeach
    </div>
</div>
@include('includes.footer-before-login')