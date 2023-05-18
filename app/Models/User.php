<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'activated'
    ];

    /**
     *@var string
     */
    public $namePtBr = 'Usuário';

    /**
     * @var array|string[]
     */
    public array $rules = [
        'name' => 'required|min:2|max:180|unique:users,name',
        'password' => 'required|min:6|max:50',
        'activated' => 'boolean',
    ];

    /**
     * @var array|string[]
     */
    public array $putMethodRules = [
        'name' => 'required|min:2|max:180|unique:users,name',
        'password' => 'min:6|max:50',
        'activated' => 'boolean',
    ];

    /**
     * @var array|string[]
     */
    public array $patchMethodRules = [
        'name' => 'min:2|max:180|unique:users,name',
        'password' => 'min:6|max:50',
        'activated' => 'boolean',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    #start auth
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    #end auth
}
