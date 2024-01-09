@extends('layouts.afterlogin')
@section('content')
<div class="container-fluid">
  <div class="all-advertisement-page">
    <div class="all-history-page">
      <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 my-4">
                <div class="heading-section">
                  <h4 class="text-center"><em>Create New Task Campaign</em> </h4>
                </div>
                <div class="alert alert-with-icon alert-warning alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i> Join support group for advertisers at <a
                    href="">@micro_task_advertiser</a>.
                </div>

                <form action="{{ route('advertiser.create_task') }}" method="POST" wire:submit.prevent="submit">
                    @csrf
                    <div class="mb-3">
                      <label for="title" class="form-label">Task Title </label>
                      <input type="text" name="title" value="{{ old('title') }}" id="title" class="form-control" maxlength="50" title="Title must be filled and must not be over 50 characters" required autofocus autocomplete="title">
                      @error('title')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    @livewire('category-selector')

                    {{-- <h5 class="mt-3 mb-3" data-bs-toggle="tooltip" data-bs-placement="left" title="Workers Level who will be able to submit this task, choose starter to show it to everyone">Choose Worker Level  <i class="fa-regular fa-circle-question"></i></h5> --}}
                    <div class="row" style="display:none">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="form-floating">
                                <select class="form-select" name="worker_level" required>
                                    <option value="starter" selected>Starter</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="advanced">Expert</option>
                                </select>
                                <label for="floatingSelect">Level</label>
                            </div>
                        </div>
                    </div>


                    <h5 class="mt-5 mb-3" data-bs-toggle="tooltip" data-bs-placement="left" title="This is the time in which you will rate a pending submitted proof, if not rated within this time limit, system will auto approve the submitted proofs">Approval Time   <i class="fa-regular fa-circle-question"></i></h5>
                    <div class="row">
                        <div class="col-lg-2 col-sm-2 col-md-2">
                            <div class="form-check">
                                <input name="rating_time" value="1" class="form-check-input" type="radio" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                  1 Day
                                </label>
                              </div>
                        </div>

                        <div class="col-lg-2 col-sm-2 col-md-2">
                            <div class="form-check">
                                <input name="rating_time" value="2" class="form-check-input" type="radio" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                  2 Days
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 col-md-2">
                            <div class="form-check">
                                <input name="rating_time" value="3" class="form-check-input" type="radio" id="flexRadioDefault3">
                                <label class="form-check-label" for="flexRadioDefault3">
                                  3 Days
                                </label>
                              </div>
                        </div>

                        <div class="col-lg-2 col-sm-2 col-md-2">
                            <div class="form-check">
                                <input name="rating_time" value="5" class="form-check-input" type="radio" id="flexRadioDefault4" checked>
                                <label class="form-check-label" for="flexRadioDefault4">
                                  5 Days
                                </label>
                              </div>
                        </div>

                        <div class="col-lg-2 col-sm-2 col-md-2">
                            <div class="form-check">
                                <input name="rating_time" value="7" class="form-check-input" type="radio" id="flexRadioDefault5">
                                <label class="form-check-label" for="flexRadioDefault5">
                                  7 Days
                                </label>
                              </div>
                        </div>
                      </div>
                    @livewire('dynamic-list')
                    @livewire('advertise.tasks.required-proofs')
                    <div class="d-grid gap-2 mt-3 mb-3">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection