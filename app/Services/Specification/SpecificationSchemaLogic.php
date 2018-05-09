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

    /**
     * Get all secification schemas
     *
     * @return SpecificationSchema
     */
    public static function getAllName() {
        return SpecificationSchema::get();
    }

    public static function getSpecData($id) {
        return SpecificationSchema::with('specs.spec')->where('id', $id)->first();
    }

    /**
     * Create a new schema
     *
     * @param String $name
     * @return SpecificationSchemaLogic
     */
    public static function create($name) {
        $schema = new SpecificationSchema();
        $schema->name = $name;
        $schema->save();

        return new SpecificationSchemaLogic($schema);
    }

    /**
     * Create Check a list of entries
     *
     * @param string $jsonData
     * @return boolean
     */
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

    /**
     * Create entries
     *
     * @param String $jsonData
     * @return void
     */
    public function createEntries($jsonData) {
        $json = json_decode($jsonData);
        foreach($json as $spec) {
            SpecificationEntryLogic::create($this->specifiction, $spec);
        }
    }

    /**
     * Return the schema id
     *
     * @return String
     */
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