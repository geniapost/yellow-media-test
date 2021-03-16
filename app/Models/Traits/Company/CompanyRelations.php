<?php


namespace App\Models\Traits\Company;


use App\Models\User;

trait CompanyRelations
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
