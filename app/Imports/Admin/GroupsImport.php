<?php

namespace App\Imports\Admin;

use Illuminate\Support\Collection;
use App\Models\Groups;
use Maatwebsite\Excel\Concerns\ToModel;

class GroupsImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new Groups([
           'name'     => $row[0],

        ]);
    }
}
