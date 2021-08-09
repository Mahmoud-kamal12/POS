<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClient;
use App\Http\Requests\Client\UpdateClient;
use App\Models\Category;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Client::when($request->search, function ($q) use ($request) {

            return $q->where('name', 'like', '%' . $request->search . '%')
                ->orwhere('phone' , 'like' , '%'. $request->search . '%')
                ->orwhere('address' , 'like' , '%'. $request->search . '%');

        })->latest()->paginate(8);

        return view('dashboard.clients.index' , compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function store(StoreClient $request)
    {
        $req_data = $request->all();
        $req_data['phone'] = array_filter($request->phone);
        Client::create($req_data);
        session()->flash('success' , __('site.added_successfuly'));
        return  redirect()->route('dashboard.clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Client $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Client $client
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('dashboard.clients.edit')->with('client' , $client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function update(UpdateClient $request, Client $client)
    {
        $req_data = $request->all();
        $req_data['phone'] = array_filter($request->phone);
        $client->update($req_data);
        session()->flash('success' , __('site.updated_successfuly'));
        return  redirect()->route('dashboard.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return RedirectResponse
     */
    public function destroy(Client $client)
    {
        $client->delete();
        session()->flash('success' , __('site.deleted_successfuly'));
        return  redirect()->route('dashboard.clients.index');
    }
}
