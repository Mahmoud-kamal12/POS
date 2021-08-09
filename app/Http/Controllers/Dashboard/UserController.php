<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class UserController extends Controller
{


    public function __construct()
    {

        $this->middleware(['permission:users_create'])->only('create');
        $this->middleware(['permission:users_update'])->only('edit');
        $this->middleware(['permission:users_delete'])->only('destroy');
        $this->middleware(['permission:users_read'])->only('show');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = User::whereRoleIs('admin')->where(function ($query) use ($request){
            return $query->when($request->search , function ($q) use ($request) {
                return $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        })->latest()->paginate(8);

        return  view('dashboard.users.index')->with('users' , $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return  view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(StoreUserRequest $request)
    {

        $request_data = $request->except(['password' , 'password_confirmation' , 'permissions' , 'image']);
        $request_data['password'] = bcrypt($request->password);
        $hashnakeimage = '';
        if ($request->image){
            $hashnameimage = $request->image->hashName();
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
                ->save(public_path('uploads/users_images/') . $hashnameimage  , '75' , 'png' );
            $request_data['image'] = $hashnameimage;
        }else{

            $hash = md5(strtolower(trim($request->email)));
            $request_data['image'] =  "defualt_users.jpg";
        }
        $user = User::create($request_data);
        $user->attachRole('admin');
        if ($request->permissions !== null ) {
            $user->syncPermissions($request->permissions);
        }


        session()->flash('success' , __('site.added_successfuly'));
        return  redirect()->route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function edit(User $user)
    {

        return view('dashboard.users.edit')->with('user' , $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $request_data = $request->except(['permissions' , 'image']);
        $user = User::find($user)->first();
        if ($request->image){
            if($user->image != 'defualt_user.jpg')
                Storage::disk('public_uploads')->delete('/users_images/'. $user->image);
            $hashnameimage = $request->image->hashName();
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
                ->save(public_path('uploads/users_images/') . $hashnameimage  , '75' , 'png' );
            $request_data['image'] = $hashnameimage;
        }else{


            $request_data['image'] =  "defualt_user.jpg";
        }
        $user->update($request_data);
        if ($request->permissions !== null ) {
            $user->syncPermissions($request->permissions);
        }
        session()->flash('success' , __('site.updated_successfuly'));
        return  redirect()->route('dashboard.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user = User::find($user)->first();
        Storage::disk('public_uploads')->delete('users_images/'.$user->image);
        $user->delete();
        return redirect()->route('dashboard.users.index');
    }
}
