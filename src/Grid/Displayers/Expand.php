<?php

namespace MicroEcology\Multi\Grid\Displayers;

use MicroEcology\Multi\Multi;
use MicroEcology\Multi\Grid\Simple;
use Illuminate\Contracts\Support\Renderable;

class Expand extends AbstractDisplayer
{
    protected $renderable;

    public function display($callback = null, $isExpand = false)
    {
        $html = '';
        $async = false;
        $loadGrid = false;

        if (is_subclass_of($callback, Renderable::class)) {
            $this->renderable = $callback;
            $async = true;
            $loadGrid = is_subclass_of($callback, Simple::class);
        } else {
            $html = call_user_func_array($callback->bindTo($this->row), [$this->row]);
        }

        return Multi::component('multi::components.column-expand', [
            'key'           => $this->getKey(),
            'url'           => $this->getLoadUrl(),
            'name'          => str_replace('.', '-', $this->getName()).'-'.$this->getKey(),
            'html'          => $html,
            'value'         => $this->value,
            'async'         => $async,
            'expand'        => $isExpand,
            'loadGrid'      => $loadGrid,
            'elementClass'  => "grid-expand-{$this->grid->getGridRowName()}",
        ]);
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $renderable = str_replace('\\', '_', $this->renderable);

        return route('multi.handle-renderable', compact('renderable'));
    }
}
