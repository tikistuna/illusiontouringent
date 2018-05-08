<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;


class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$folders = Folder::orderBy('id', 'asc')->get();
        return view('admin.folders.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.folders.create');
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
	    ]);
    	Folder::create($request->all());
    	return redirect('/admin/folders');
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
        $folder = Folder::findOrFail($id);
        return view('admin.folders.edit', compact('folder'));
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
	    ]);
        Folder::findOrFail($id)->update($request->all());
        return redirect('/admin/folders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Folder::findOrFail($id)->delete();
        return redirect('/admin/folders');
    }

    public function api_index(){
        return Folder::orderBy('name')->get()->toJson();
    }

    public function api_last_created(){
        $date = Folder::orderBy('created_at', 'desc')->pluck('created_at')->first();
        return json_encode(['lastCreated' => $date->diffForHumans()]);
    }
}
