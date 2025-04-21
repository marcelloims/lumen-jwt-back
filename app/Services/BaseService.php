<?php

namespace App\Services;

use Illuminate\Http\Request;

class BaseService
{
    public function audiTableInsert($userEmail)
    {
        return [
            'created_by'        => $userEmail,
            'updated_by'        => $userEmail,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];
    }

    public function audiTableUpdate($userEmail)
    {
        return [
            'updated_by'        => $userEmail,
            'updated_at'        => date('Y-m-d H:i:s'),
        ];
    }

    public function audiTableDelete($userEmail)
    {
        return [
            'deleted_by'        => $userEmail
        ];
    }
}
