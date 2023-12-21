<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Home extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
