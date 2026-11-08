<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
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
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        // (bool): atribut bisa null pada user yang baru dibuat (sebelum diambil ulang dari DB)
        return (bool) $this->is_admin;
    }

    /**
     * Get the orders for the user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the cart items for the user
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get formatted total spent amount
     */
    public function getFormattedTotalSpentAttribute()
    {
        $total = $this->orders()->sum('total_amount');
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    /**
     * Get average order value
     */
    public function getAverageOrderValueAttribute()
    {
        $avg = $this->orders()->avg('total_amount');
        return $avg ? 'Rp ' . number_format($avg, 0, ',', '.') : 'Rp 0';
    }
}
