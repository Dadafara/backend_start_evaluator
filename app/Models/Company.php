<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'company';
    protected $primaryKey = 'id';
    protected $fillable = [
        'url',
        'companyName',
        'lastName',
        'firstName',
        'email',
        'country',
        'contact',
        'category',
        'password',
    ];

      public function reviews()
      {
          return $this->hasMany(Reviews::class, 'company_id', 'id');
      }
}
