<?php

namespace App\Users;

use App\Estimates\Estimate;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'github_id', 'github_details', 'company',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function registerOrRetrieve($userObject)
    {
        $gitHubDetails = $userObject->user;
        $gitHubDetails['nickname'] = $userObject->nickname;
        $detailsObject = [
            'github_id'      => $userObject->id,
            'name'           => $userObject->name ?: 'Unnamed',
            'email'          => $userObject->email ?: 'not_set',
            'avatar'         => $userObject->avatar ?: '',
            'company'        => $userObject->user['company'] ?: '',
            'github_details' => json_encode($gitHubDetails),
        ];

        $user = self::where('github_id', $userObject->id)->first();
        if ($user) {
            unset($detailsObject['name']);
            unset($detailsObject['email']);
            unset($detailsObject['company']);
            $user->update($detailsObject);

            return $user;
        }

        $user = self::create($detailsObject);

        return $user;
    }

    /**
     * A custom retrieval method for the name attribute.
     * We don't want "Unnamed" to be visible to users when editing their profile.
     * So we'll hide that if that's the case in the DB.
     *
     * @param $attribute
     * @return string
     */
    public function getNameAttribute($attribute)
    {
        if ($attribute == 'Unnamed') {
            return '';
        }

        return $attribute;
    }

    public function getLocaleStringAttribute()
    {
        switch ($this->locale) {
            case 'GBP':
                return 'en_GB';
                break;
            case 'USD':
                return 'en_US';
                break;
            case 'EUR':
                return 'de_DE';
                break;
        }
    }

    /**
     * Force the default rate to a float with fixed decimals.
     *
     * @param $attribute
     * @return mixed
     */
    public function getDefaultRateAttribute($attribute)
    {
        return number_format(floatval($attribute), 2, '.', '');
    }

    /**
     * A custom retrieval method for the email attribute.
     * We don't want "not_set" to be visible to users when editing their profile.
     * So we'll hide that if that's the case in the DB.
     *
     * @param $attribute
     * @return string
     */
    public function getEmailAttribute($attribute)
    {
        if ($attribute == 'not_set') {
            return '';
        }

        return $attribute;
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class, 'user_id');
    }

    public function hasEstimates()
    {
        return $this->estimates()->count() > 0;
    }

    public function getCurrencySymbol()
    {
        return '£';
    }
}
