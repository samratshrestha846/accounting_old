<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Category([
            'category_name'=>$row['name'],
            'category_code'=>$row['category_code'],
            'company_id'=>1,
            'branch_id'=>1,
            'category_image'=>'noimage.jpg',
            'in_order'=>'1',
        ]);
    }
}
