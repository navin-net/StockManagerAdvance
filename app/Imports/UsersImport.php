<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{SkipsEmptyRows, ToModel, WithHeadingRow, WithUpserts};

class UsersImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithUpserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $messages = []; // Store alert messages

    public function model(array $row)
    {
        if (empty($row['email'])) {
            $this->messages[] = "Skipped: Empty row";
            return null;
        }

        $user = User::where('email', $row['email'])->first();

        if ($user) {
            $this->messages[] = "Data already exists: {$row['email']}";
        } else {
            User::create([
                'name'     => $row['name'] ?? null,
                'email'    => $row['email'],
                'password' => bcrypt('password'),
            ]);

            $this->messages[] = "Data inserted successfully: {$row['email']}";
        }

        return null;
    }

    /**
     * Column used to detect existing records
     */
    public function uniqueBy()
    {
        return 'email';
    }

}
