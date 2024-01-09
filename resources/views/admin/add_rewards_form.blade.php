<form method="post" action="{{ route('admin.task-categories.store-rewards', ['id' => $category->id]) }}">
    @csrf
    <!-- Add form fields for each country -->
    @foreach ($countries as $country)
        <label>{{ $country->country_name }}</label>
        <input type="text" name="rewards[{{ $country->id }}]" value="{{ old('rewards.' . $country->id) }}">
        <br>
    @endforeach
    <button type="submit">Save Rewards</button>
</form>
