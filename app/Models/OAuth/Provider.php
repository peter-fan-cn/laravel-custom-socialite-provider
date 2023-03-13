<?php

namespace App\Models\OAuth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'oauth_providers';

    protected $fillable = [
        'provider',
        'sub',
        'name',
        'user_id',
        'email',
        'phone',
        'avatar',
        'scope',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function findOrCreateUser()
    {
        if ($this->user_id) {
            $this->load('user');
            return $this->user;
        } else {
            $user = User::where('email', $this->email)->firstOrNew();
            $user->fill([
                'name'     => $this->name,
                'email'    => $this->email,
                'avatar'   => $this->avatar,
                'password' => Str::random('16'),
            ]);
            $user->save();
            $this->user_id = $user->id;
            $this->save();
            return $user;
        }
    }
}
