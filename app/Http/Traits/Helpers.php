<?php

namespace App\Http\Traits;

trait Helpers
{
    public function removeSpaceBetweenStringChar(String $string): string
    {
        return str_replace(' ', '', $string);
    }
}
