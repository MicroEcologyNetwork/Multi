@foreach($js as $j)
<script src="{{ multi_asset ("$j") }}"></script>
@endforeach