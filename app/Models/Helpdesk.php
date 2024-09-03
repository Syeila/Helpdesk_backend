<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helpdesk extends Model
{
    use HasFactory;

    protected $table = 'tbl_helpdesk';

    protected $fillable = [
        'id_user',
        'keluhan',
        'tgl_keluhan',
        'deskripsi',
        'foto',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
