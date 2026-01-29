<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// 通知
use App\Notifications\UserResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // 主キーカラムを変更
    protected $primaryKey = 'user_no';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'password',
        'status',
        'role_id',
        'chatwork_id',
        'profile_image_file_name',
        'must_change_password',
        'last_login_at',
    ];
    // rolesテーブルとのリレーション
    public function role()
    {
        return $this->belongsTo(Role::Class, 'role_id', 'role_id');
    }
    // vehiclesテーブルとのリレーション
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'user_no', 'user_no');
    }
    // 氏名を返すアクセサ
    public function getFullNameAttribute()
    {
        return $this->last_name . ' '. $this->first_name;
    }

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
}