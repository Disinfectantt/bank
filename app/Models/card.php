<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class card extends Model
{
    use HasFactory;
    protected $fillable = ['surname', 'name', 'patronymic', 'passport', 'card', 'tel', 'card_limit', 'user_id'];
}
