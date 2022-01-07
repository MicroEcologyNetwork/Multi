<?php

namespace Micro\Multi\Grid\Filter\Presenter;

use Micro\Multi\Facades\Multi;

class MultipleSelect extends Select
{
    /**
     * Load options for other select when change.
     *
     * @param string $target
     * @param string $resourceUrl
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function loadMore($target, $resourceUrl, $idField = 'id', $textField = 'text'): self
    {
        $column = $this->filter->getColumn();

        $script = <<<EOT

$(document).on('change', ".{$this->getClass($column)}", function () {
    var target = $(this).closest('form').find(".{$this->getClass($target)}");
     var ids = $(this).find("option:selected").map(function(index,elem) {
            return $(elem).val();
        }).get().join(',');
    $.get("$resourceUrl?q="+ids, function (data) {
        target.find("option").remove();
        $.each(data, function (i, item) {
            $(target).append($('<option>', {
                value: item.$idField,
                text : item.$textField
            }));
        });
        
        $(target).trigger('change');
    });
});
EOT;

        Multi::script($script);

        return $this;
    }
}
