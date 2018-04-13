<?php

namespace App\Services\Category;

use App\Categories;



class CategoryLogic {
    protected $cat;

    public function __construct(Categories $cat)
    {
        $this->cat = $cat;
    }

    public static function getAll() {
        $cat = Categories::get();
        return $cat;
    }

    public static function create($request) {
        try {
            $cat = new Categories();
            $cat->name = $request->name;
            $cat->slug = str_slug($request->name, '-');
            $cat->save();

            return new CategoryLogic($cat);

        } catch(\Exception $e) {
            return false;
        }
    }

}

?>