<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserEmp extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'users';
}
