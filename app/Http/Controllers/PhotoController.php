<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Folder;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    $events = Event::orderBy('name', 'asc')
		    ->get();
	    $events_cities = array();
	    foreach($events as $event){
	    	$events_cities[$event->id] = "{$event->name} en {$event->city->name}";
	    }

	    $folders = Folder::orderBy('name', 'asc')
		    ->pluck('name', 'id')
		    ->all();

        return view('admin.photos.create', compact('events_cities', 'folders'));
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
		    'event_id' => 'bail|required|numeric|exists:events,id',
		    'folder_id' => 'bail|required|numeric|exists:folders,id',
		    'photo' => 'required',
	    ]);
	    $folder = Folder::findOrFail($request->folder_id)->path();

	    if($file = $request->file('photo')){
		    $path = Storage::putFile($folder, $file);
		    $path = '/' . $path;
		    $photo = Photo::create(['event_id'=>$request->event_id, 'folder_id' => $request->folder_id, 'path' => $path]);
		    return redirect("/admin/eventTicketSeller/{$request->event_id}");
	    }else{
	    	return redirect()->back(400);
	    }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$photo = Photo::findOrFail($id);

	    $events = Event::orderBy('name', 'asc')
		    ->get();
	    $events_cities = array();
	    foreach($events as $event){
		    $events_cities[$event->id] = "{$event->name} en {$event->city->name}";
	    }

	    $folders = Folder::orderBy('name', 'asc')
		    ->pluck('name', 'id')
		    ->all();

        return view('admin.photos.edit', compact('photo', 'events_cities', 'folders'));
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
		    'event_id' => 'bail|required|numeric|exists:events,id',
		    'folder_id' => 'bail|required|numeric|exists:folders,id',
	    ]);
	    $photo = Photo::findOrFail($id);
	    $photo->update($request->all());

	    if($file = $request->file('photo')){
            $old_path = substr($photo->path, 1);
            $folder = $photo->folder->path();
            $path = Storage::putFile($folder, $file);
            $photo->path = '/' . $path;
            $photo->save();
            Storage::delete($old_path);
	    }


	    return redirect('/admin/photos');
    }

}
