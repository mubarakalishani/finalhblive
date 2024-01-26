@extends('layouts.afterlogin')
@section('content')
<div class="container-fluid">

    <!-- user dashboard start -->
    <div class="all-history-page all-surveys-page">
        <!-- ***** My jobs Start ***** -->
        <div class="container">
            <div class="row">
                <div class="col-lg-12 my-4">
                    <div class="heading-section">
                        <h4><em>Offerwalls</em> and Surveywalls</h4>
                    </div>
                    <div class="row">
                        <a @if($offerwall->is_target_blank !=0 ) target="_blank" @endif
                            class="offerwall-button" data-toggle="modal" data-target="#myModal" data-header="{{ $offerwall->name }}" data-url="{{ $offerwall->url }}">
                            <div class="col">
                                <div class="card-item">
                                    <img src="{{$offerwall->image_url}}" alt="{{$offerwall->name}}">
                                    <h4>{{$offerwall->name}}</h4>
                                </div>
                            </div>
                        </a>
                        <!-- ***** Most Popular Features End ***** -->

                    </div>
                </div>
            </div>
        </div>
        <!-- My jobs end -->
    </div>
</div>
<div class="modal fade" id="myModal" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-full-width">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeader">Website Loading </h4>
                <span>
                    <a id="modalFullPage" href="#" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </span>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Loader container within the modal -->
            <div class="loader-container">
                <div class="loader"></div>
            </div>

            <!-- Modal body -->
            <iframe id="modalIframe" style="width: 100%; height: 90vh;" onload="hideLoader()"></iframe>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>
</div>
<script>
// JavaScript to set the iframe src, modal header, and open the external link in a new tab when a button is clicked
$('.offerwall-button').on('click', function() {
      var url = $(this).data('url');
      var header = $(this).data('header');

      // Show the loader within the modal
      showLoader();

      $('#modalFullPage').attr('href', url); // Set the href attribute of the anchor tag
      $('#modalIframe').attr('src', url);
      $('#modalHeader').text(header);
  });

  // Function to show the loader within the modal
  function showLoader() {
      $('.loader-container').show();
  }

  // Function to hide the loader within the modal
  function hideLoader() {
      $('.loader-container').hide();
  }
</script>
@endsection