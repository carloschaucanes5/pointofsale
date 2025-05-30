<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;


class UserController extends Controller
{
   public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $users = DB::table("users")
                ->where('name', 'like', '%' . $query . '%')
                ->where('status',1)
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('segurity.user.index', ['users' => $users, 'searchText' => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("segurity.user.create");
    }

    public function store(Request $request){
        $user = new User();
        $user->name = $request->get("name");
        $user->email = $request->get("email");
        $user->password = bcrypt($request->get("password"));
        $user->save();
        return Redirect::to("segurity/user");
    }

    public function edit($id){
        $user = User::FindOrFail($id);
        return view("segurity.user.edit",['user'=>$user]);
    }

    public function update(Request $request, $id){
        $user = User::FindOrFail($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->update();
        return Redirect::to("segurity/user");

    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->status = 0;
        $user->update();
        return Redirect::to("segurity/user")->with("success","Usuario ha cambiado a estado inactivo");
    }
}
