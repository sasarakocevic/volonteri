<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slika extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'slike';

    protected $hidden = [
        'donacija_id'
    ];

    protected $fillable = [
        'putanja',
        'donacija_id',
    ];

    public function donacija()
    {
        return $this->belongsTo(Donacija::class, "donacija_id");
    }

    public function getPutanjaAttribute(){
        return url(Storage::url($this->attributes['putanja']));
    }

    public function pravaPutanjaDoFajla(){
        return $this->attributes['putanja'];
    }
}
