@include('includes.header-before-login')
<!-- ***** Header Area End ***** -->

<div class="all-page">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="container">
                    <div class="most-popular-1 faqs-page">
                        <div class="py-4 draggable">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <div class="card card-style1 border-0">
                                            <div class="card-body p-4 p-md-5 p-xl-6">
                                                <div class="text-center mb-2-3 mb-lg-6">
                                                    <h2 class="h1 mb-0 text-secondary">Frequently Asked Questions</h2>
                                                </div>
                                                <div id="accordion" class="accordion-style">
                                                    @foreach ($faqs as $faq)
                                                    <div class="card mb-3">
                                                        <div class="card-header" id="headingTwo">
                                                            <h5 class="mb-0">
                                                                <button
                                                                    class="btn btn-link @if($faq->s_no!=1) collapsed @endif"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse{{ $faq->s_no }}"
                                                                    aria-expanded="@if($faq->s_no==1)true @else false @endif"
                                                                    aria-controls="collapse{{ $faq->s_no }}"><span
                                                                        class="text-theme-secondary me-2">Q.{{
                                                                        $faq->s_no }}</span> {{ $faq->question
                                                                    }}</button>
                                                            </h5>
                                                        </div>
                                                        <div id="collapse{{ $faq->s_no }}"
                                                            class="collapse @if($faq->s_no==1) show @endif"
                                                            aria-labelledby="heading{{ $faq->s_no }}"
                                                            data-bs-parent="#accordion">
                                                            <div class="card-body">
                                                                {{ $faq->answer }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <! -- ***** Footer Start ***** -->
        @include('includes.footer-before-login')