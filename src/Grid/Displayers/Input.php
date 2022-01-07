<?php

namespace MicroEcology\Multi\Grid\Displayers;

use MicroEcology\Multi\Multi;

class Input extends AbstractDisplayer
{
    public function display($mask = '')
    {
        return Multi::component('multi::grid.inline-edit.input', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'mask'     => $mask,
        ]);
    }
}
