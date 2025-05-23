<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }




        public function isPassager(): bool
    {
        return $this->role === 'passager';
    }

    public function isConducteur(): bool
    {
        return $this->role === 'conducteur';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

      // Relation avec les critiques en tant que passager
      public function reviewsAsPassager()
      {
          return $this->hasMany(Review::class, 'passager_id');
      }
  
      // Relation avec les critiques en tant que conducteur
      public function reviewsAsConducteur()
      {
          return $this->hasMany(Review::class, 'conducteur_id');
      }
    }
