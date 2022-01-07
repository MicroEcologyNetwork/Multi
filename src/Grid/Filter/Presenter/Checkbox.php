<?php

namespace Micro\Multi\Grid\Filter\Presenter;

use Micro\Multi\Facades\Multi;

class Checkbox extends Radio
{
    protected function prepare()
    {
        $script = "$('.{$this->filter->getId()}').iCheck({checkboxClass:'icheckbox_minimal-blue'});";

        Multi::script($script);
    }
}
