<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Troubleshoot extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_masalah',
        'kategori',
        'gejala',
        'solusi',
        'catatan',
        'foto'
    ];
}