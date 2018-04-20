<?php

namespace App\Services\Category;

use App\Categories;



class CategoryLogic {
    protected $cat;

    /**
     * CategoryLogic constructor
     *
     * @param  \App\Categories $cat
     * @return void
     */
    public function __construct(Categories $cat)
    {
        $this->cat = $cat;
    }

    /**
     * Get all Categories
     *
     * @return \App\Categories[] $cat
     */
    public static function getAll() {
        $cat = Categories::get();
        return $cat;
    }

    /**
     * Create a Category
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Services\Category\CategoryLogic
     */
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