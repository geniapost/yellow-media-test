<?php


namespace App\Models\Traits\User;


use App\Models\Company;

trait UserRelations
{
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
