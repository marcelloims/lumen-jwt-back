<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BaseRepositories
{

    public function getAll($table)
    {
        return DB::table($table)->get();
    }

    public function store($table, $data)
    {
        return DB::table($table)->insert($data);
    }

    public function getById($tabel, $where)
    {
        return DB::table($tabel)->where($where)->first();
    }

    public function update($table, $data, $id)
    {
        return DB::table($table)->where('id', $id)->update($data);
    }

    public function destroy($table, $id)
    {
        return DB::table($table)->where('id', $id)->delete();
    }
}
