<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ConfigIdScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // semua model yang menerapkan trait ConfigId dipastikan memiliki kolom config_id
        return $builder->where($model->getTable() . '.config_id', identitas('id'));
    }

    /**
     * Extend the query builder with the needed functions.
     */
    public function extend(Builder $builder): void
    {
        $builder->macro('withConfigId', static function (Builder $builder, $alias = null) {
            if ($alias) {
                return $builder->where("{$alias}.config_id", identitas('id'));
            }

            return $builder->where('config_id', identitas('id'));
        });
    }
}