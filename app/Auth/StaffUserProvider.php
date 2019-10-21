<?php

namespace App\Auth;


use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Str;

class StaffUserProvider extends EloquentUserProvider
{

    protected function newModelQuery($model = null)
    {
        return is_null($model)
            ? $this->createModel()->newQuery()->isStaff()
            : $model->newQuery()->isStaff();
    }
}
