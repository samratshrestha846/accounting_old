<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function app\NepaliCalender\datenep;

class ChildAccount extends Model
{
    use HasFactory, Multicompany;
    use SoftDeletes;

    protected $fillable = ['company_id', 'branch_id', 'sub_account_id', 'title', 'slug', 'opening_balance'];

    protected $dates = [ 'deleted_at' ];

    public function subAccount()
    {
        return $this->belongsTo(SubAccount::class);
    }
    public function journal_extras(){
        return $this->hasMany(JournalExtra::class, 'child_account_id');
    }
    public function this_year_opening_balance(){
        $today = date("Y-m-d");
        $nepalitoday = datenep($today);

        $explode = explode('-', $nepalitoday);
        $year = $explode[0];
        $month = $explode[1];

        if($month < 4){
            $fiscalyear = ($year - 1).'/'.$year;

        }else{
            $fiscalyear = $year.'/'.($year + 1);
        }

        $fiscal_year_id = FiscalYear::where('fiscal_year', $fiscalyear)->first()->id;
        return $this->hasOne(OpeningBalance::class, 'child_account_id')->where('fiscal_year_id', $fiscal_year_id);
    }

    public function custom_year($fiscal_year_id, $child_account_id){
        $opening_balance = OpeningBalance::where('child_account_id', $child_account_id)->where('fiscal_year_id', $fiscal_year_id)->first();
        return $opening_balance;
    }

    public function openingbalance(){
        return $this->hasOne(OpeningBalance::class,'child_account_id','id');
    }


}
