<?php

namespace App\Utils;
use App\Models\User;
use Illuminate\Support\Str;

class UniqueCodeGenerator
{
    public static function generateUniqueCode($length = 6)
    {
        $code = Str::random($length);

        // Ensure uniqueness by checking if the generated code already exists
        while (self::codeExists($code)) {
            $code = Str::random($length);
        }

        return $code;
    }

    private static function codeExists($code)
    {
        // Perform a check to see if the generated code already exists in the database
        // You need to replace this logic with your actual database check
        // Example: return Model::where('code', $code)->exists();
        // For demonstration purposes, we're returning false here
        return User::where('unique_code', $code)->exists();
    }
}
