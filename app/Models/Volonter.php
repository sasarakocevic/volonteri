<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volonter extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'volonteri';

    protected $hidden = [
//        'email',
        'akcija_id',
    ];

    protected $fillable = [
        'email',
        'ime',
        'akcija_id',
    ];

    public function akcija()
    {
        return $this->belongsTo(Akcija::class, "akcija_id");
    }
}
