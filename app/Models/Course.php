<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'passager_id',
        'conducteur_id',
        'depart',
        'destination',
        'statut',
    ];

    // Relation avec le passager
    public function passager()
    {
        return $this->belongsTo(User::class, 'passager_id');
    }

    // Relation avec le conducteur
    public function conducteur()
    {
        return $this->belongsTo(User::class, 'conducteur_id');
    }
}
