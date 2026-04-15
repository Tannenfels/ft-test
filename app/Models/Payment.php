<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const STATUS_NEW = 'new';
    //...

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
