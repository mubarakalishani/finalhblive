
<div>
    <hr>
    <h4>Geo Targeting: </h4>
    <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" wire:click.prevent="addPredefinedCountries([@foreach($arabCountries as $country)'{{$country}}',@endforeach])">Arab Countries</a>
    <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2" wire:click.prevent="addPredefinedCountries([@foreach($europianCountries as $country)'{{$country}}',@endforeach])">Europian Countries</a>
    <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2" wire:click.prevent="removeAllCountries">Remove All</a>
    <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-2" wire:click.prevent="addAllCountries">Add All</a>
    
    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h6>Excluded Countries</h6>
            <select name="excludedCountries[]" size="12" style="width: 100%; background-color: transparent;" wire:change="toggleCountry($event.target.value, 'excluded')" multiple>
                @foreach($excludedCountries as $country)
                    <option style="background-color: transparent;" value="{{ $country }}" selected>{{ $country }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h6>Included Countries</h6>
            <select name="includedCountries[]" size="12" style="width: 100%" wire:change="toggleCountry($event.target.value, 'included')" multiple>
                @foreach($includedCountries as $country)
                    <option style="background-color: transparent;" value="{{ $country }}" selected>{{ $country }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>


