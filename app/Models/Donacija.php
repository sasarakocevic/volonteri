<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donacija extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'donacije';

    const AKTIVNA = 'AKTIVNA';
    const ZAVRSENA = 'ZAVRSENA';
    public static $statusi = [self::AKTIVNA, self::ZAVRSENA];

    protected $hidden = [
      'donator'
    ];
    protected $fillable = [
        'donator',
        'naslov',
        'lokacija',
        'opis',
        'status',
    ];
}
