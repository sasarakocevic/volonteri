<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akcija extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'akcije';

    const AKTIVNA = 'AKTIVNA';
    const NEAKTIVNA = 'NEAKTIVNA';
    const ZAVRSENA = 'ZAVRSENA';
    public static $statusi = [self::AKTIVNA, self::NEAKTIVNA, self::ZAVRSENA];
    public static $inizijalni_statusi = [self::AKTIVNA, self::NEAKTIVNA];

    protected $fillable = [
        'naslov',
        'opis',
        'vrijeme',
        'pozeljan_broj_volontera',
        'status',
        'izvjestaj'
    ];
}
