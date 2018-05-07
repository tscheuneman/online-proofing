<?php

namespace App\Http\Controllers;

use App\Specification;
use Illuminate\Http\Request;

use App\Http\Requests\SpecificationRequest;

use App\Services\Specification\SpecificationLogic;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $specs = SpecificationLogic::getAll();
        return view('admin.specifications.index', [
            'specs' => $specs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.specifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecificationRequest $request)
    {
        try {
            SpecificationLogic::create($request);

            \Session::flash('flash_created','Specification has been created');
            return redirect('/admin/specifications');

        } catch(\Exception $e) {
            \Session::flash('flash_deleted','Failed to create specification');
            return redirect('/admin/specifications');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function show(Specification $specification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function edit(Specification $specification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specification $specification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specification $specification)
    {
        //
    }
}
