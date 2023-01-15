<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class credit extends Model
{
    use HasFactory;
    protected $fillable = ['region', 'surname', 'name', 'patronymic', 'passport', 'period', 'sum', 'insurance'];
}
