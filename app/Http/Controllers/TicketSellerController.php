<?php

namespace App\Http\Controllers;

use App\Models\TicketSeller;
use Illuminate\Http\Request;

class TicketSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticket_sellers = TicketSeller::orderBy('name', 'asc')->get();
        return view('admin.ticket_sellers.index', compact('ticket_sellers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ticket_sellers.create');
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
		    'phone' => 'nullable|string',
		    'address' => 'required_without:website|nullable|string',
		    'hours' => 'nullable|string',
		    'website' => 'required_without_all:phone,address|nullable|string',
	    ]);

	    TicketSeller::create($request->all());
	    return redirect('/admin/ticket_sellers');
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
        $ticket_seller = TicketSeller::findOrFail($id);
        return view('admin.ticket_sellers.edit', compact('ticket_seller'));
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
		    'phone' => 'nullable|string',
		    'address' => 'required_without:website|nullable|string',
		    'hours' => 'nullable|string',
		    'website' => 'required_without_all:phone,address|nullable|string',
	    ]);

	    TicketSeller::findOrFail($id)->update($request->all());
	    return redirect('/admin/ticket_sellers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TicketSeller::findOrFail($id)->delete();
        return redirect('/admin/ticket_sellers');
    }

	public function api_index(){
		return TicketSeller::orderBy('name')->get()->toJson();
	}

    public function api_last_created(){
        $date = TicketSeller::orderBy('created_at', 'desc')->pluck('created_at')->first();
        return json_encode(['lastCreated' => $date->diffForHumans()]);
    }
}
