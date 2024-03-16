<div>
    <h5 class="mt-3 mb-3">Choose Category</h5>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="form-floating">
                <select class="form-select" name="category" wire:model.live="selectedParentCategory" required>
                    <option value="">Select Category</option>
                    @foreach ($parentCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">Category</label>
            </div>
            @if($errors->has('category'))
                <span class="text-danger">{{ $errors->first('category') }}</span>
            @endif
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            @if ($subCategories)
                <div class="form-floating">
                    <select class="form-select" name="subCategory" wire:model="selectedSubCategory" required>
                        <option value="0">Select Subcategory</option>
                        @foreach ($subCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label for="floatingSelect">Subcategory</label>
                </div>
            @endif
        </div>
        @if($errors->has('subCategory'))
            <span class="text-danger">{{ $errors->first('subCategory') }}</span>
        @endif
    </div>

    <hr>
    <h4>Geo Targeting: </h4>
    {{-- <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" wire:click.prevent="addPredefinedCountries([@foreach($arabCountries as $country)'{{ $country->country_name }}',@endforeach])">Arab Countries</a>
    <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2" wire:click.prevent="addPredefinedCountries([@foreach($europianCountries as $country)'{{$country}}',@endforeach])">Europian Countries</a> --}}
    <a id="romoveallbutton" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2" wire:click.prevent="removeAllCountries">Remove All</a>
    <a id="addallbutton" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2" wire:click.prevent="addAllCountries">Add All</a>
    
    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h6>Excluded Countries</h6>
            <select id="excludeselect" name="excludedCountries[]" size="12" style="width: 100%; background-color: transparent;" wire:change="toggleCountry($event.target.value, 'excluded')" multiple>
                @foreach($excludedCountries as $country)
                    <option id="option{{$country}}" style="background-color: transparent;" value="{{ $country }}" selected>{{ $country }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h6>Included Countries</h6>
            <select id="includeselect" size="12" style="width: 100%" wire:change="toggleCountry($event.target.value, 'included')" multiple>
                @foreach($includedCountries as $country)
                    <option id="option{{$country}}" style="background-color: transparent;" value="{{ $country }}" selected>{{ $country }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if ($subCategory)
    
    <table class="table mt-3" style="display: block; max-height: 300px; overflow-y: scroll;">
        <thead>
            <tr>
                <th scope="col">Country</th>
                <th scope="col">Amount</th>
                <th scope="col">Min Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($includedCountries as $country)
                <tr>
                    <td><p>{{ $country }}</p></td>
                    @foreach ($subCategory->rewards as $reward)
                        @if ($reward->country_name == $country)

                            <td>
                                <div class="input-group">
                                    <input name="includedCountries[{{ $country }}]" type="number" value="{{ $reward->min_reward_amount }}" step="0.01" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
                                </div>
                            </td>
                            <td>
                                {{ $reward->min_reward_amount }}
                                <a href="#">Match minimum</a>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

    <div class="d-grid gap-2 mt-3 mb-3">
        <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Optional Controls
        </a>
    </div>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <div class="mb-3">
                <label for="title" class="form-label">Max Campaign Budget</label>
                <input type="number" name="maxBudget" value="{{ old('maxBudget' , 0) }}" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Maximum Daily Spend</label>
                <input type="number" name="dailyBudget" value="{{ old('dailyBudget' , 0) }}" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Maximum Weekly Spend</label>
                <input type="number" name="weeklyBudget" value="{{ old('weeklyBudget' , 0) }}" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Max Hourly Spend</label>
                <input type="number" name="hourlyBudget" value="{{ old('hourlyBudget' , 0) }}" id="title" class="form-control"  required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Daily Maximum Submission</label>
                <input type="number" name="submissionPerDay" value="{{ old('submissionPerDay' , 0) }}" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Hourly Maximum Submission</label>
                <input type="number" name="submissionPerHour" value="{{ old('submissionPerHour' , 0) }}" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Weekly Maximum Submission</label>
                <input type="number" name="submissionPerWeek" value="{{ old('submissionPerWeek' , 0) }}" id="title" class="form-control" required>
            </div>

        </div>
    </div>

</div>


