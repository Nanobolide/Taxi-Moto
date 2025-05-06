<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = ['passager_id', 'conducteur_id', 'note', 'commentaire'];

    // Relation avec l'utilisateur passager
    public function passager()
    {
        return $this->belongsTo(User::class, 'passager_id');
    }

    // Relation avec l'utilisateur conducteur
    public function conducteur()
    {
        return $this->belongsTo(User::class, 'conducteur_id');
    }
}
