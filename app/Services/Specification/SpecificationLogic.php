<?php

namespace App\Services\Specification;

use App\Specification;
use Illuminate\Http\Request;

use PhpParser\Node\Scalar;


class SpecificationLogic {
    protected $specifiction;

    /**
     * SpecificationLogic Controller
     *
     * @param \App\Specification $specifiction
     * @return void
     */

    public function __construct(Specification $specifiction)
    {
        $this->specifiction = $specifiction;
    }

    /**
     * Get all specifications
     *
     * @return App/Specification
     */

    public static function getAll() {
        return Specification::get();
    }

    /**
     * Create a specification
     *
     * @param Request $request
     * @return SpecificationLogic
     */
    public static function create(Request $request) {

        $spec = new Specification();
            $spec->name = $request->spec_name;
            $spec->type = $request->content_type;
            $spec->default = $request->default_value;

            $spec->save();

        return new SpecificationLogic($spec);
    }


}

?>