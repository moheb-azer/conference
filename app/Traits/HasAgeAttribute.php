<?php

namespace App\Traits;
use Carbon\Carbon;

trait HasAgeAttribute
{
    /**
     * Calculate the age from the date of birth.
     *
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        if ($this->dob) {
            return Carbon::parse($this->dob)->age;
        }

        return null;
    }
}
