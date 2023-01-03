<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    public function getAll()
    {
        return $this->all();
    }
}
