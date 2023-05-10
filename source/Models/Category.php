<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Category
 * @package Source\Models
 */
class Category extends Model
{
    /**
     * Category constructor
     */
    public function __construct()
    {
        parent::__construct('categories', ['id'], ['title', 'id']);    
    }

    /**
     * Find By Uri Method
     *
     * @param string $uri
     * @param string $columns
     * @return null|Category
     */
    public function findByUri(string $uri, string $columns = "*"): ?Category
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }

}