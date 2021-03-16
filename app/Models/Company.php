<?php


namespace App\Models;


use App\Models\Traits\Company\CompanyRelations;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use CompanyRelations;

    protected $table = 'companies';

    protected $fillable = [
        'title', 'description', 'phone'
    ];

    protected $guarded = ['id'];

}
