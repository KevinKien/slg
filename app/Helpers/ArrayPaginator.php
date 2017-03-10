<?php

namespace App\Helpers;

use JasonGrimes\Paginator;
use Request;

class ArrayPaginator extends Paginator
{
    private $paginator;
    private $result;

    /**
     * @param array $data
     * @param int $page
     * @param int $url_pattern
     * @param int $max_per_page
     */
    public function __construct(Array $data, $page, $url_pattern, $max_per_page = 10)
    {
        $total = count($data);
        $max_page = ceil($total / $max_per_page);

        $this->paginator = '';
        $this->result = $data;

        if ($max_page > 1) {
            $current_page = ($page > 0 && $page <= $max_page) ? $page : 1;

            $offset = ($current_page - 1) * $max_per_page;

            $this->result = array_slice($data, $offset, $max_per_page);

            $this->paginator = new Paginator($total, $max_per_page, $current_page, $url_pattern);
        }
    }

    /**
     * @return Paginator
     */
    public function render()
    {
        return $this->paginator;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}