<?php

namespace App\Services\Specification;

use App\SpecificationSchema;
use Illuminate\Http\Request;

use PhpParser\Node\Scalar;


class SpecificationSchemaLogic{
    protected $specifiction;

    /**
     * SpecificationLogic Controller
     *
     * @param \App\Specification $specifiction
     * @return void
     */

    public function __construct(SpecificationSchema $specifiction)
    {
        $this->specifiction = $specifiction;
    }

    public static function getAll() {
        return SpecificationSchema::get();
    }



}

?>