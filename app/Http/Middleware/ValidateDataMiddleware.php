<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\DefaultResponseHelper;

/**
 * Class ValidateDataMiddleware
 * @package App\Http\Middleware
 */
class ValidateDataMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $validate = $this->validate($request);
        if ($validate->fails()) {
            $response = DefaultResponseHelper::error('Dados inválidos', 400, $validate->messages());
            return response()->json($response, 400);
        }
        return $next($request);
    }

    private function defineModel(string $namespace): ?Model
    {
        $model = null;

        if (class_exists($namespace)) {
            $model = new $namespace();
        }

        return $model;
    }

    private function validate(Request $request)
    {
        
        $alias = $request->route()[1]['as'];
        $model = $this->defineModel($alias);

        if (empty($model)) {
            throw new \InvalidArgumentException('A model ' . $alias . ' não existe');
        }
        $rules = $this->getRules($request, $model);
        return Validator::make(
            $request->toArray(),
            $rules
        );
    }

    private function getRules(Request $request, Model $model){
        $rules = [];
        if($request->isMethod('patch')) {
            $rules = $model->patchMethodRules;
        } else if($request->isMethod('put')){
            $rules = $model->putMethodRules;
        } else {
            $rules = $model->rules;
        }
        $modelName = $request->route()[1]['as'];
        if($modelName == 'App\Models\User' && ($request->isMethod('patch') || $request->isMethod('put')) && isset($request->route()[2]['id'])){
            if(isset($model->rules['name'])){
                $rules['name'] .= ','.$request->route()[2]['id'];
            }
        } else if($modelName == 'App\Models\Producer' && ($request->isMethod('patch') || $request->isMethod('put')) && isset($request->route()[2]['id'])){
            if(isset($model->rules['cpf'])){
                $rules['cpf'] .= ','.$request->route()[2]['id'];
            }
            if(isset($model->rules['rural_cadastre'])){
                $rules['rural_cadastre'] .= ','.$request->route()[2]['id'];
            }
        }

        return $rules;
    }
}