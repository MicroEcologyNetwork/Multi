<?php

namespace Micro\Multi\Form\Field;

use DateTimeZone;

class Timezone extends Select
{
    protected $view = 'multi::form.select';

    public function render()
    {
        $this->options = collect(DateTimeZone::listIdentifiers(DateTimeZone::ALL))->mapWithKeys(function ($timezone) {
            return [$timezone => $timezone];
        })->toArray();

        return parent::render();
    }
}
