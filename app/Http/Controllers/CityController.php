<?php

namespace App\Http\Controllers;


use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cities.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $request->validate([
		    'name' => 'required|string',
		    'state' => 'required|string',
		    'full_state' => 'required|string',
	    ]);

        City::create($request->all());
        return redirect('/admin/cities');
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
    	$city = City::findOrFail($id);
        return view('admin.cities.edit', compact('city'));
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
	    $request->validate([
		    'name' => 'required|string',
		    'state' => 'required|string',
		    'full_state' => 'required|string',
	    ]);

        City::findOrFail($id)->update($request->all());
        return redirect('/admin/cities');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		City::findOrFail($id)->delete();
		return redirect('/admin/cities');
    }

    public function api_index(){
    	return City::orderBy('name')->get()->toJson();
    }

    public function api_last_created(){
        $date = City::orderBy('created_at', 'desc')->pluck('created_at')->first();
        return json_encode(['lastCreated' => $date->diffForHumans()]);
    }
}
