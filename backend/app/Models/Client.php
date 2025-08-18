<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo',
        'age',
        'huggy_contact_id',
        'avatar_url',
        'birthdate'
    ];

    public function address() {
        return $this->hasOne(Address::class);
    }
}
