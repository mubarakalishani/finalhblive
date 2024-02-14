@include('includes.header-before-login')
<div class="container mt-3 mb-3">
    <div class="row" style="padding: 30px;background-color: #E6F3F8;border-radius: 10px;">
        <div class="col-lg-8 col-sm-12 col-md-12">
            <h4>{{ $newsToShow->title }}</h4>
            <p>Last Updated: {{ $newsToShow->updated_at->diffForHumans() }}</p>
            {!! $newsToShow->content !!}
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