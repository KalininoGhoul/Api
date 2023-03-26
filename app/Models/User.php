<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Group;
use App\Models\Status;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'login',
        'password',
        'surname',
        'patronymic',
        'status_id',
        'group_id',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'surname',
        'patronymic',
        'password',
        'remember_token',
        'api_token',
        'created_at',
        'updated_at',
    ];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
