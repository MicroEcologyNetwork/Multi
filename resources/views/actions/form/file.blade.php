<div class="form-group">
    <label>{{ $label }}</label>
    <input type="file" class="{{$class}}" name="{{$name}}" {!! $attributes !!} />
    @include('multi::actions.form.help-block')
</div>
