@include('includes.header-before-login')
<div class="container mt-3 mb-3">
    <div class="row most-popular-1">
        <div class="col-lg-8 col-sm-12 col-md-12">
            <h5>{{ $latestNews->title }}</h5>
            <p>Last Updated: {{ $latestNews->updated_at->diffForHumans() }}</p>
            {!! $latestNews->content !!}
        </div>
        <div class="col-lg-4 col-sm-12 col-md 12" style="border-left: 2px solid aliceblue;">
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
</div>
@include('includes.footer-before-login')