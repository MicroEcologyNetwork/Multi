<?php

namespace Micro\Multi\Grid\Displayers;

use Micro\Multi\Multi;

class Upload extends AbstractDisplayer
{
    public function display($multiple = false)
    {
        return Multi::component('multi::grid.inline-edit.upload', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'value'    => $this->getValue(),
            'target'   => "inline-upload-{$this->getKey()}",
            'resource' => $this->getResource(),
            'multiple' => $multiple,
        ]);
    }
}
