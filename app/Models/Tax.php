<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'percent', 'is_default'];

    public function getAll()
    {
        return $this->get();
    }
}
