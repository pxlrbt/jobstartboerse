<?php

namespace App\Models;

use Database\Factories\DegreeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Degree extends Model
{
    /**
     * @use HasFactory<DegreeFactory>
     */
    use HasFactory;

    use SoftDeletes;
}
