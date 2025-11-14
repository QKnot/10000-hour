<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;
    public bool $dismissible;
    public bool $autoClose;
    public int $duration;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = 'info',
        bool $dismissible = true,
        bool $autoClose = true,
        int $duration = 5000
    ) {
        $this->type = $type;
        $this->dismissible = $dismissible;
        $this->autoClose = $autoClose;
        $this->duration = $duration;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert', [
            'type' => $this->type,
            'dismissible' => $this->dismissible,
            'autoClose' => $this->autoClose,
            'duration' => $this->duration,
        ]);
    }
}
