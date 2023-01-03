<?php
namespace App\Repositories;

class BaseRepository {

    protected array $filters = [];

    public function filters(array $filters)
    {
        $this->filters = $filters;
        return $this;
    }
}
