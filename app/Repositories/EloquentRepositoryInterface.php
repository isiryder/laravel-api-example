<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    public function all();

    public function get(int $id);

    public function create(array $params);

    public function save(Model $model);

    public function update(Model $model);

    public function delete(Model $model);
}