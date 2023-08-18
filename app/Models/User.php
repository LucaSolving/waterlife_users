<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';

    protected $fillable = [
        'firts_name',
        'last_name',
        'mother_last_name',
        'type_doc',
        'num_doc',
        'email',
        'email_verified_at',
        'send_email_sponsor',
        'password',
        'birth_date',
        'gender',
        'phone',
        'phone_operator',
        'department',
        'province',
        'district',
        'address',
        'address_reference',
        'bank',
        'type_account',
        'nro_account',
        'cci_account',
        'method_payment',
        'username',
        'id_sponsor',
        'reg_token',
        'confirm_membership',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:d-m-Y H:i:s',
        'affiliation_date' => 'datetime:d-m-Y H:i:s',
    ];

    public function setPasswordAttribute(string $value)
    {
        $this->attributes['password'] = bcrypt($value);
    }


    public static function profileSaveEdit($request, $id) {
            $users                      = User::find($id);

            $users->firts_name          = $request['firts_name'];
            $users->last_name           = $request['last_name'];
            $users->mother_last_name    = $request['mother_last_name'];
            $users->birth_date          = $request['birth_date'];
            $users->gender              = $request['gender'];
            $users->email               = $request['email'];
            $users->phone               = $request['phone'];
            $users->phone_operator      = $request['phone_operator'];
            $users->department          = $request['department'];
            $users->province            = $request['province'];
            $users->district            = $request['district'];
            $users->address             = $request['address'];
            $users->address_reference   = $request['address_reference'];

            if ($users->save()) {
                return $users;
            }
    }
    public static function profileEditBank($request, $id) {
            $users                      = User::find($id);

            $users->bank                    = $request['bank'];
            $users->type_account            = $request['type_account'];
            $users->nro_account             = $request['nro_account'];
            $users->cci_account             = $request['cci_account'];

            if ($users->save()) {
                return $users;
            }
    }
    public static function createPhoto($request, $id) {
            $news                 = User::find($id);

            if($request->hasFile('image')){
                $file          = $request->file('image');
                $image     = 'news-'.date('YmdHis.').$file->getClientOriginalExtension();
                $file->move(\public_path().'/images/perfil/', $image);
                $news->image     = $image;
            }

            if ($news->save()) {
                return $news;
            }
    }

}
