<?php

namespace App\Services\Specification;

use App\Http\Requests\SpecificationSchemaRequest;
use App\Specification;
use App\SpecificationSchema;
use Illuminate\Http\Request;

use PhpParser\Node\Scalar;

use App\Services\Specification\SpecificationEntryLogic;

use JsonSchema\Validator as JSONValidate;
use JsonSchema\Constraints\Constraint as Constraint;

class SpecificationSchemaLogic{
    protected $specifiction;

    /**
     * SpecificationSchemaLogic Controller
     *
     * @param \App\Specification $specifiction
     * @return void
     */

    public function __construct(SpecificationSchema $specifiction)
    {
        $this->specifiction = $specifiction;
    }

    /**
     * Get all secification schemas
     *
     * @return SpecificationSchema
     */
    public static function getAll() {
        return SpecificationSchema::with('specs')->get();
    }

    public static function create($name) {
        $schema = new SpecificationSchema();
        $schema->name = $name;
        $schema->save();

        return new SpecificationSchemaLogic($schema);
    }

    public static function checkEntries($jsonData) {
        $json = json_decode($jsonData);

        foreach($json as $spec) {
            $id = $spec->id;
            $spec = Specification::find($id);
            if($spec === null) {
                return false;
            }
        }
        return true;

    }

    public function createEntries($jsonData) {
        $json = json_decode($jsonData);
        foreach($json as $spec) {
            SpecificationEntryLogic::create($this->specifiction, $spec);
        }
    }

    public function returnID() {
        return $this->specifiction->id;
    }

    /**
     * Get all secification schemas
     *
     * @param SpecificationSchemaRequest $request
     * @return boolean
     */
    public static function verify($jsonData) {
        $validator = new JSONValidate;
        $json = json_decode($jsonData);


        foreach($json as $obj) {
            $validator->validate(
                $obj,
                (object)[
                    "type"=>"object",
                    "properties"=>(object)[
                        "id"=>(object)[
                            "type"=>"string",
                            "required"=>true
                        ],
                        "value"=>(object)[
                            "type"=>"string",
                            "required"=>true
                        ],
                    ]
                ],
                Constraint::CHECK_MODE_NORMAL
            ); //validates, and sets defaults for missing properties
        }

        if ($validator->isValid()) {
            return true;
        } else {
            foreach ($validator->getErrors() as $error) {
                \Log::info($error);
            }
        }

        return false;


    }



}

?>