<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository {

    protected $model;
    private $relations;

    public function __construct(Model $model, array $relations = []) {
        $this->model = $model;
        $this->relations = $relations;
    }

    public function all() {
        return $this->model->get();
    }

    public function get(int $id) {
        return $this->mode->find($id);
    }
}