<?php
namespace App\Actions;

use App\FormDatas\PositionFormData;
use App\Models\Position;
use Illuminate\Support\Str;

class CreatePositionAction {

    public function execute(PositionFormData $data): Position
    {
        return Position::create([
            'name' => $data->name,
            'slug' => Str::slug($data->name),
            'status' => $data->status
        ]);
    }
}
