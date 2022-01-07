<?php

namespace MicroEcology\Multi\Form\Field;

use MicroEcology\Multi\Multi;

class CheckboxCard extends CheckboxButton
{
    protected function addStyle()
    {
        $style = <<<'STYLE'
.card-group label {
    cursor: pointer;
    margin-right: 8px;
    font-weight: 400;
}

.card-group .panel {
    margin-bottom: 0px;
}

.card-group .panel-body {
    padding: 10px 15px;
}

.card-group .active {
    border: 2px solid #367fa9;
}
STYLE;

        Multi::style($style);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addStyle();

        return parent::render();
    }
}
