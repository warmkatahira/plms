<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function messages()
    {
        return [
            'required'                          => ":attributeは必須です。",
            'required_if'                       => ":attributeは必須です。",
            'required_with'                     => ":attributeは必須です。",
            'string'                            => ":attributeは文字列で入力して下さい。",
            'unique'                            => ":attributeは既に使用されています。",
            'image'                             => ":attributeは画像ファイルでなければなりません。",
            'mimes'                             => ":attributeは:values形式のみ許可されています。",
            'boolean'                           => ":attributeが正しくありません。",
            'exists'                            => ":attributeがシステムに存在しません。",
            'integer'                           => ":attributeは数値(整数)で入力して下さい。",
            'email'                             => "有効なメールアドレスを入力して下さい。",
            'unique'                            => ":attributeは既に使用されています。",
            'confirmed'                         => ":attributeが確認用と一致しません。",
            'date'                              => ":attributeが日付ではありません。",
            'max'                               => ":attributeは:max文字以下で入力して下さい。",
            'min'                               => ":attributeは:min文字以上で入力して下さい。",
            'sort_order.min'                    => ":attributeは:min以上で入力して下さい。",
            'sort_order.max'                    => ":attributeは:max以下で入力して下さい。",
            'in'                                => ":attributeが正しくありません。",
            'numeric'                           => ":attributeは数値で入力して下さい。",
            'distinct'                          => ":attributeに同じ値があります。",
        ];
    }

    public function attributes()
    {
        return [
            // 共通
            'sort_order'            => '並び順',
            // 車両
            'vehicle_id'            => '車両',
            'vehicle_type_id'       => '車両区分',
            'vehicle_category_id'   => '車両種別',
            'vehicle_name'          => '車両名',
            'vehicle_color'         => '車両色',
            'vehicle_number'        => '車両ナンバー',
            'vehicle_capacity'      => '送迎可能人数',
            'vehicle_memo'          => '車両メモ',
            'owner'                 => '所有者',
            // 乗降場所
            'boarding_location_id'  => '乗降場所',
            'location_name'         => '場所名',
            'location_memo'         => '場所メモ',
            // ルート
            'route_type_id'         => 'ルート区分',
            'route_name'            => 'ルート名',
            'stop_order'            => '停車順番',
            'departure_time'        => '出発時刻',
            // ユーザー情報
            'user_no'               => 'ユーザー',
            'user_id'               => 'ユーザーID',
            'last_name'             => '姓',
            'first_name'            => '名',
            'password'              => 'パスワード',
            'status'                => 'ステータス',
        ];
    }
}