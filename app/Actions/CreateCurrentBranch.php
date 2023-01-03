<?php

namespace App\Actions;

use App\Models\UserCompany;

class CreateCurrentBranch
{
    public $userId;
    public $company;
    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->company = $this->getCurrentCompany();
    }

    public function exceute()
    {
        UserCompany::create([
            'user_id' => $this->userId,
            'company_id' => $this->company->company_id,
            'branch_id' => $this->company->branch_id,
            'is_selected' => 1,
        ]);
    }

    private function getCurrentCompany()
    {
        return  UserCompany::where('user_id', auth()->id())->where('is_selected', 1)->first();
    }
}
