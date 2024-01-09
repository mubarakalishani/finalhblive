<div>
    <form wire:submit.prevent="submit">
        <div class="row">
          <div class="col">
            <label for="adTitle" class="form-label">Ad Title</label>
            <input type="text" class="form-control" id="adTitle" wire:model.live="title">
            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
          </div>

          <div class="col">
            <label for="url" class="form-label">Url: </label>
            <input type="url" class="form-control" id="url" wire:model.live="url">
            @error('url') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Description:</label>
          <textarea name="description" class="form-control" id="description" wire:model.live="description"></textarea>
           @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- duration and package selection --}}
        <div class="row">
      <div class="col">
        <label for="exampleInputEmail1">Duration</label>
        <select class="form-select" aria-label="Default select example" wire:model.live="packagePrice">
            @foreach ($ptcAdPackage as $item)
              <option value="{{ $item->reward_per_view }}">{{ $item->seconds }}seconds  ${{ $item->reward_per_view }}</option>
            @endforeach
        </select>
      </div>

      {{-- ad type, iframe or windows --}}
      <div class="col">
        <label for="exampleInputEmail1">Type</label>
        <select class="form-select" aria-label="Default select example" wire:model="type">
          <option value="0">Iframe</option>
          <option value="1">Windows</option>
        </select>
      </div>
        </div>

        <div class="form-group">
      <label for="exampleInputEmail1">Number Views</label>
      <input type="number" class="form-control" id="viewsAmount" wire:model.lazy="viewsAmount">
        @error('viewsAmount') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        {{-- start of optional controls --}}
        <div class="d-grid gap-2 mt-3 mb-3">
      <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button"
          aria-expanded="false" aria-controls="collapseExample">
          Optional Controls
      </a>
        </div>

        <div class="collapse" id="collapseExample" wire:ignore.self>
      <div class="card card-body">
          <h6>Choose Ad Interval: </h6>
          <div class="row mb-3 mt-3">
              <div class="col">
                  <div class="form-check">
                      <input name="rating_time" wire:model="adRevisionInterval" value="5" class="form-check-input"
                          type="radio" id="flexRadioDefault4">
                      <label class="form-check-label" for="flexRadioDefault4">
                          5 hours
                      </label>
                  </div>
              </div>
              <div class="col">
                  <div class="form-check">
                      <input wire:model="adRevisionInterval" value="10" class="form-check-input" type="radio"
                          id="flexRadioDefault1">
                      <label class="form-check-label" for="flexRadioDefault1">
                          10 hours
                      </label>
                  </div>
              </div>

              <div class="col">
                  <div class="form-check">
                      <input wire:model="adRevisionInterval" wire:init="setDefaultInterval" value="24"
                          class="form-check-input" type="radio" id="flexRadioDefault2" checked>
                      <label class="form-check-label" for="flexRadioDefault2">
                          1 Day
                      </label>
                  </div>
              </div>
              <div class="col">
                  <div class="form-check">
                      <input wire:model="adRevisionInterval" value="48" class="form-check-input" type="radio"
                          id="flexRadioDefault3">
                      <label class="form-check-label" for="flexRadioDefault3">
                          2 Days
                      </label>
                  </div>
              </div>
              @error('adRevisionInterval') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
      </div>
      <h4>Geo Targeting: </h4>
      <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
          wire:click.prevent="addPredefinedCountries([@foreach($arabCountries as $country)'{{ $country->country_name }}',@endforeach])">Arab
          Countries</a>
      <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2"
          wire:click.prevent="removeAllCountries">Remove All</a>
      <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2"
          wire:click.prevent="addAllCountries">Add All</a>

      <div class="row mt-3">
          <div class="col-lg-6 col-md-6 col-sm-12">
              <h6>Excluded Countries</h6>
              <select size="12" style="width: 100%; background-color: transparent;"
                  wire:change="toggleCountry($event.target.value, 'excluded')" multiple>
                  @foreach($excludedCountries as $country)
                  <option style="background-color: transparent;" value="{{ $country }}" selected>{{ $country }}
                  </option>
                  @endforeach
              </select>
          </div>

          <div class="col-lg-6 col-md-6 col-sm-12">
              <h6>Included Countries</h6>
              <select size="12" style="width: 100%" wire:change="toggleCountry($event.target.value, 'included')"
                  multiple>
                  @foreach($includedCountries as $country)
                  <option style="background-color: transparent;" value="{{ $country }}" selected>{{ $country }}
                  </option>
                  @endforeach
              </select>
          </div>
      </div>
        </div>
        {{-- end of optional controls --}}

        <div class="my-4">
            <h6>Total Cost : ${{ $totalCost }}</h6>
        </div>
        
        @error('lessAmountError') <div><span class="text-danger">{{ $message }}</span></div> @enderror
        <button class="btn btn-primary" type="submit">Submit</button>
    </form>
</div>