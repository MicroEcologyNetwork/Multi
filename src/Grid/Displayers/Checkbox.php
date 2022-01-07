<?php

namespace MicroEcology\Multi\Grid\Displayers;

use MicroEcology\Multi\Multi;
use Illuminate\Support\Arr;

class Checkbox extends AbstractDisplayer
{
    public function display($options = [])
    {
        return Multi::component('multi::grid.inline-edit.checkbox', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'value'    => json_encode($this->getValue()),
            'display'  => implode(';', Arr::only($options, $this->getValue())),
            'options'  => $options,
        ]);
    }
}
