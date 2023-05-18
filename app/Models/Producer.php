<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Property;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model {
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cpf'
    ];

    /**
     *@var string
     */
    public $namePtBr = 'Produtor';

    /**
     * @var array|string[]
     */
    public array $rules = [
        'producer_name' => 'required|min:2|max:180',
        'property_name' => 'required|min:2|max:180',
        'cpf' => 'required|digits:11|cpf|unique:producers,cpf',
        'rural_cadastre' => 'required|min:2|max:2000|unique:properties,rural_cadastre',
    ];

    /**
     * @var array|string[]
     */
    public array $putMethodRules = [
        'producer_name' => 'required|min:2|max:180',
        'property_name' => 'required|min:2|max:180',
        'cpf' => 'required|digits:11|cpf|unique:producers,cpf',
        'rural_cadastre' => 'required|min:2|max:2000|unique:properties,rural_cadastre',
    ];

    /**
     * @var array|string[]
     */
    public array $patchMethodRules = [
        'producer_name' => 'min:2|max:180',
        'property_name' => 'min:2|max:180',
        'cpf' => 'digits:11|cpf|unique:producers,cpf',
        'rural_cadastre' => 'min:2|max:2000|unique:properties,rural_cadastre',
    ];

    public function property()
    {
        return $this->hasOne(Property::class);
    }
}