<?php

namespace MicroEcology\Multi\Grid\Actions;

use MicroEcology\Multi\Actions\RowAction;

class Edit extends RowAction
{
    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('multi.edit');
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}/edit";
    }
}
