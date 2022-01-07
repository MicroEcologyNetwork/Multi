<?php

namespace MicroEcology\Multi\Grid\Tools;

use MicroEcology\Multi\Multi;

class FilterButton extends AbstractTool
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $label = '';
        $filter = $this->grid->getFilter();

        if ($scope = $filter->getCurrentScope()) {
            $label = "&nbsp;{$scope->getLabel()}&nbsp;";
        }

        return Multi::component('multi::filter.button', [
            'scopes'    => $filter->getScopes(),
            'label'     => $label,
            'cancel'    => $filter->urlWithoutScopes(),
            'btn_class' => uniqid().'-filter-btn',
            'expand'    => $filter->expand,
            'filter_id' => $filter->getFilterID(),
        ]);
    }
}
