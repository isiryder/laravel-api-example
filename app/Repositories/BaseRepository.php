<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class BaseRepository {

    protected $model;
    protected $queryBuilder;

    public function __construct(Model $model, array $relations = []) {
        $this->model = $model;
        $this->queryBuilder = $this->model->with($relations);
    }

    public function all() {
        return $this->queryBuilder->get();
    }

    public function get(int $id) {
        return $this->queryBuilder->find($id);
    }

    public function create(array $params) {
        return $this->queryBuilder->create($params);
    }

    public function save(Model $model) {
        $model->save();
        return $model;
    }

    public function update(Model $model) {
        $model->update();
        return $model;
    }

    public function delete(Model $model) {
        return $model->delete();
    }
}