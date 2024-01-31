@extends('layouts.afterlogin')
@section('content')
<div class="container-fluid">
  <!-- all games start -->
  <div class="all-advertisement-page">
    <!-- ***** All Games start ***** -->
    <div class="all-history-page">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 my-4">
            <div class="heading-section">
              <h4 class="text-center"><em>advertisement</em> </h4>
            </div>
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="alert alert-with-icon alert-warning alert-dismissible fade show" role="alert">
                  <i class="fa-solid fa-triangle-exclamation"></i> Join support group for advertisers at <a
                    href="/social?name=telegram" target="_blank">telegram</a>.
                </div>


                @livewire('advertise.ptc.create-ptc')


              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- All games end -->
  </div>
</div>
@endsection