<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model {

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rural_cadastre', 'producer_id'
    ];

    /**
     *@var string
     */
    public $namePtBr = 'Propriedade';

    /**
     * @var array|string[]
     */
    public array $rules = [
        'name' => 'required|min:2|max:180',
        'rural_cadastre' => 'required|min:2|max:2000',
        'producer_id' => 'required|numeric|exists:producers,id',
    ];


    /**
     * @var array|string[]
     */
    public array $putMethodRules = [
        'name' => 'required|min:2|max:180',
        'rural_cadastre' => 'required|min:2|max:2000',
    ];

    /**
     * @var array|string[]
     */
    public array $patchMethodRules = [
        'name' => 'min:2|max:180',
        'rural_cadastre' => 'min:2|max:2000',
    ];

    /**
     * Get the producer that owns the property.
     */
    public function producer()
    {
        return $this->belongsTo(Producer::class);
    }
}