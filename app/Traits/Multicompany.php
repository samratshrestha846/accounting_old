<?php
namespace App\Traits;

use App\Models\UserCompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Multicompany {
        protected static function bootMulticompany(){

            $table = (new static )->getTable();

            if(auth()->check()){
                static::creating(function($model){
                    $user = Auth::user()->id;
                    $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
                    $model->company_id = $currentcomp->company_id;
                    $model->branch_id = $currentcomp->branch_id;
                });

                static::addGlobalScope('data', function (Builder $builder) use($table){

                    $user = Auth::user()->id;
                    $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();

                    $builder->where("{$table}.company_id", $currentcomp->company_id)
                    ->where("{$table}.branch_id", $currentcomp->branch_id);
                });
            }
        }
    }

?>
