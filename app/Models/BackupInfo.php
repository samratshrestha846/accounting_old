<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupInfo extends Model
{
    use HasFactory;

    protected $fillable = ['backedup_by', 'status'];

    public function backup(){
        return $this->hasOne(User::class,'id', 'backedup_by');
    }
}
