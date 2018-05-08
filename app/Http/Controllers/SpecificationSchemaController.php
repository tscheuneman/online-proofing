<?php

namespace App\Http\Controllers;

use App\Services\Specification\SpecificationEntryLogic;
use App\Services\Specification\SpecificationSchemaLogic;
use Illuminate\Http\Request;
use App\Http\Requests\SpecificationSchemaRequest;
use App\Services\Specification\SpecificationLogic;


class SpecificationSchemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specs = SpecificationLogic::getAll();
        return view('admin.specifications.schema.create', [
            'specs' => $specs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecificationSchemaRequest $request)
    {
        if(SpecificationSchemaLogic::verify($request->jsonSpecs)) {
            if(SpecificationSchemaLogic::checkEntries($request->jsonSpecs)) {
                $schema = SpecificationSchemaLogic::create($request->spec_name);
                $schema->createEntries($request->jsonSpecs);

                \Session::flash('flash_created','Schema has been created');
                return redirect('/admin/specifications');
            }

        }
        \Session::flash('flash_deleted','Failed to create schema');
        return redirect('/admin/specifications/schema/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
