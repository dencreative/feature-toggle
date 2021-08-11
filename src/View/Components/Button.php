<?php

namespace DenCreative\FeatureToggle\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $resourceId;

    public function __construct($type, $id)
    {
        $this->type = $type;
        $this->resourceId = $id;
    }

    public function render()
    {
        return view("feature-toggle::components.buttons.{$this->type}");
    }
}
