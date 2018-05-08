<?php

namespace App\Services\Specification;

use App\SpecificationEntry;
use App\SpecificationSchema;
use App\Specification;

class SpecificationEntryLogic{
    protected $specifiction;

    /**
     * SpecificationEntryLogic Controller
     *
     * @param \App\SpecificationEntry $specifiction
     * @return void
     */

    public function __construct(SpecificationEntry $specifiction)
    {
        $this->specifiction = $specifiction;
    }

    public static function create(SpecificationSchema $schema, $data) {
        $id = $data->id;
        $value = $data->value;

        $spec = new SpecificationEntry();
            $spec->specification_id = $id;
            $spec->schema_id = $schema->id;
            $spec->value = $value;

        $spec->save();

        return new SpecificationEntryLogic($spec);

    }




}

?>