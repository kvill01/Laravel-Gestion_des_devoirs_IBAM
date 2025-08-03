<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputError extends Component
{
    public $messages;
    public $class;

    public function __construct($messages, $class = 'mt-2')
    {
        $this->messages = $messages;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.input-error');
    }
}
