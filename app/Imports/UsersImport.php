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
        // If all columns are empty
        if (empty(array_filter($row))) {
            $this->messages[] = "Skipped: Data is null";
            return null;
        }

        // Check email specifically
        if (empty($row['email'])) {
            $this->messages[] = "Skipped: Email is empty";
            return null;
        }

        validator($row, ['email' => 'required|email|max:255', 'name' => 'nullable|string|max:255'])->validate();

        $user = User::where('email', $row['email'])->first();

        if ($user) {
            $this->messages[] = "Data already exists: {$row['email']}";
        } else {
            User::create([
                'name'     => $row['name'] ?? $row['email'],
                'group_id' => 4,
                'status' => 'active',
                'email'    => $row['email'],
                'password' => bcrypt(\Illuminate\Support\Str::random(64)),
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
