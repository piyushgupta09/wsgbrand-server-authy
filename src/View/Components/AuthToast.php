<?php

namespace Fpaipl\Authy\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthToast extends Component
{
      /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('authy::components.auth-toast');
    }
}
