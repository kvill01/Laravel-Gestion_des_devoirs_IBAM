<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextInput extends Component
{
    public $id;
    public $name;
    public $type;
    public $class;
    public $autocomplete;

    public function __construct($id, $name, $type = 'text', $class = 'mt-1 block w-full', $autocomplete = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->class = $class;
        $this->autocomplete = $autocomplete;
    }

    public function render()
    {
        return view('components.text-input');
    }
}
