<?php

namespace MicroEcology\Multi\Grid\Concerns;

use MicroEcology\Multi\Multi;

trait CanFixHeader
{
    public function fixHeader()
    {
        Multi::style(
            <<<'STYLE'
.wrapper, .grid-box .box-body {
    overflow: visible;
}

.grid-table {
    position: relative;
    border-collapse: separate;
}

.grid-table thead tr:first-child th {
    background: white;
    position: sticky;
    top: 0;
    z-index: 1;
}
STYLE
        );
    }
}
