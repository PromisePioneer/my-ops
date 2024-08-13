<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{

    public $type;
    public $primaryColor;
    public $dangerColor;
    public $infoColor;
    public $class;
    public $text;
    public $icon;

    /**
     * Create a new component instance.
     */

    public function __construct($text, $type, $class)
    {
        $this->text = $text;
        $this->type = $type;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.button');
    }
}
