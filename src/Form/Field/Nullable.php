<?php

namespace MicroEcology\Multi\Form\Field;

use MicroEcology\Multi\Form\Field;

class Nullable extends Field
{
    public function __construct()
    {
    }

    public function __call($method, $parameters)
    {
        return $this;
    }
}
