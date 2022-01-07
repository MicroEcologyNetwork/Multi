<?php

namespace MicroEcology\Multi\Widgets\Navbar;

use MicroEcology\Multi\Multi;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class FullScreen.
 *
 * @see  https://javascript.ruanyifeng.com/htmlapi/fullscreen.html
 */
class Fullscreen implements Renderable
{
    public function render()
    {
        return Multi::component('multi::components.fullscreen');
    }
}
