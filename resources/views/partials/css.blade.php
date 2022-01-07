@foreach($css as $c)
    <link rel="stylesheet" href="{{ multi_asset("$c") }}">
@endforeach