<li>
    <a href="javascript:void(0);" class="container-refresh">
        <i class="fa fa-refresh"></i>
    </a>
</li>
<script>
    $('.container-refresh').off('click').on('click', function() {
        $.multi.reload();
        $.multi.toastr.success('{{ __('multi.refresh_succeeded') }}', '', {positionClass:"toast-top-center"});
    });
</script>
