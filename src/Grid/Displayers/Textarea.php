<?php

namespace MicroEcology\Multi\Grid\Displayers;

use MicroEcology\Multi\Multi;

class Textarea extends AbstractDisplayer
{
    public function display($rows = 5)
    {
        return Multi::component('multi::grid.inline-edit.textarea', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'rows'     => $rows,
        ]);
    }
}
