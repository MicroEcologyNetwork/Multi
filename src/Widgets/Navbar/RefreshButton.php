<?php

namespace Micro\Multi\Widgets\Navbar;

use Micro\Multi\Multi;
use Illuminate\Contracts\Support\Renderable;

class RefreshButton implements Renderable
{
    public function render()
    {
        return Multi::component('multi::components.refresh-btn');
    }
}
