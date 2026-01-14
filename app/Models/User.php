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
        'employee_no',
        'user_name',
        'email',
        'password',
        'status',
        'is_auto_update_statutory_leave_remaining_days',
        'role_id',
        'base_id',
        'profile_image_file_name',
        'must_change_password',
        'last_login_at',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('user_no', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($user_no)
    {
        return self::where('user_no', $user_no);
    }
    // rolesテーブルとのリレーション
    public function role()
    {
        return $this->belongsTo(Role::Class, 'role_id', 'role_id');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::Class, 'base_id', 'base_id');
    }
    // paid_leavesテーブルとのリレーション
    public function paid_leave()
    {
        return $this->hasOne(PaidLeave::Class, 'user_no', 'user_no');
    }
    // statutory_leavesテーブルとのリレーション
    public function statutory_leave()
    {
        return $this->hasOne(StatutoryLeave::Class, 'user_no', 'user_no');
    }
    // 「status」に基づいて、有効 or 無効を返すアクセサ
    public function getStatusTextAttribute(): string
    {
        return $this->status ? '有効' : '無効';
    }
    // 「is_auto_update_statutory_leave_remaining_days」に基づいて、有効 or 無効を返すアクセサ
    public function getIsAutoUpdateStatutoryLeaveRemainingDaysTextAttribute(): string
    {
        return $this->is_auto_update_statutory_leave_remaining_days ? '有効' : '無効';
    }
    // パスワードリセットの通知をカスタマイズ
    public function sendPasswordResetNotification($token)
    {
        $url = url("reset-password/{$token}");
        $this->notify(new UserResetPasswordNotification($url));
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            'ステータス',
            '省略営業所名',
            '従業員番号',
            '氏名',
            'ユーザーID',
            'パスワード',
            '保有日数',
            '残日数',
            '取得日数',
            '1日あたりの時間数',
            '半日あたりの時間数',
            '義務残日数自動更新',
            '義務期限日',
            '義務日数',
            '義務残日数',
            '最終更新日時',
        ];
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