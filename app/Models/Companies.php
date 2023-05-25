<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = "companyId";
    protected $fillable = [
        'companyName',
        'companyRegistrationNumber',
        'companyFoundationDate',
        'country',
        'zipCode',
        'city',
        'streetAddress',
        'latitude',
        'longitude',
        'companyOwner',
        'employes',
        'activity',
        'active',
        'email',
        'password'
    ];
}
