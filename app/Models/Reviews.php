<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\UserSimple;

class Reviews extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $fillable = [
        'note',
        'avis',
        'user_id',
        'dateTime',
        'company_id',
        
    ];

    public function user()
    {
        return $this->belongsTo(UserSimple::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
