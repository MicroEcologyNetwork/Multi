<?php

namespace MicroEcology\Multi\Grid\Concerns;

use MicroEcology\Multi\Multi;

trait CanDoubleClick
{
    /**
     * Double-click grid row to jump to the edit page.
     *
     * @return $this
     */
    public function enableDblClick()
    {
        $script = <<<SCRIPT
$('body').on('dblclick', 'table#{$this->tableID}>tbody>tr', function(e) {
    var url = "{$this->resource()}/"+$(this).data('key')+"/edit";
    $.multi.redirect(url);
});
SCRIPT;
        Multi::script($script);

        return $this;
    }
}
