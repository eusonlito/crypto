<select name="time" class="form-select form-select-lg bg-white" {{ $attributes }}>
    @foreach ($options as $key => $each)
    <option value="{{ $key }}" {{ ($key === $selected) ? 'selected' : '' }}>{{ $each }}</option>
    @endforeach
</select>
