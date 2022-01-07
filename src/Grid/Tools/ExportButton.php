<?php

namespace MicroEcology\Multi\Grid\Tools;

use MicroEcology\Multi\Multi;
use MicroEcology\Multi\Grid;

class ExportButton extends AbstractTool
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Create a new Export button instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Set up script for export button.
     */
    protected function setUpScripts()
    {
        $script = <<<SCRIPT

$('.{$this->grid->getExportSelectedName()}').click(function (e) {
    e.preventDefault();
    
    var rows = $.multi.grid.selected().join();

    if (!rows) {
        return false;
    }
    
    var href = $(this).attr('href').replace('__rows__', rows);
    location.href = href;
});

SCRIPT;

        Multi::script($script);
    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->showExportBtn()) {
            return '';
        }

        $this->setUpScripts();

        $trans = [
            'export'        => trans('multi.export'),
            'all'           => trans('multi.all'),
            'current_page'  => trans('multi.current_page'),
            'selected_rows' => trans('multi.selected_rows'),
        ];

        $page = request('page', 1);

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{$this->grid->getExportUrl('all')}" target="_blank" class="btn btn-sm btn-twitter" title="{$trans['export']}"><i class="fa fa-download"></i><span class="hidden-xs"> {$trans['export']}</span></a>
    <button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{$this->grid->getExportUrl('all')}" target="_blank">{$trans['all']}</a></li>
        <li><a href="{$this->grid->getExportUrl('page', $page)}" target="_blank">{$trans['current_page']}</a></li>
        <li><a href="{$this->grid->getExportUrl('selected', '__rows__')}" target="_blank" class='{$this->grid->getExportSelectedName()}'>{$trans['selected_rows']}</a></li>
    </ul>
</div>
EOT;
    }
}
