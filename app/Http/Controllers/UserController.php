<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Http\Response;

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
        $user->role = $request->get("role");
        $user->password = bcrypt($request->get("password"));
        $user->save();
        return Redirect::to("segurity/user");
    }

    public function edit($id){
        $user = User::FindOrFail($id);
        if(isset($user->person_id)){
            $person = Person::FindOrFail($user->person_id);
        }
        else
        {
            $person = new Person();
            $person->person_type = "user";
            $person->name = "";
            $person->document_type = "";
            $person->document_number = "";
            $person->address = "";
            $person->phone = "";
            $person->email = "";
            $person->status = 1;
            $person->id = -1;
        }
        

        return view("segurity.user.edit",['user'=>$user,'person'=>$person]);
    }

    public function update(Request $request, $id, $person_id = null){

        if(isset($person_id) && $person_id!=-1){
            $person = Person::FindorFail($person_id);
            $person->person_type = "user";
            $person->name = $request->get('p_name');
            $person->document_type = $request->get('document_type');
            $person->document_number = $request->get('document_number');
            $person->address = $request->get('address');
            $person->phone = $request->get('phone');
            $person->email = $request->get('p_email');
            $person->update();
                return response()->json([
                "success"=>true,
                "message"=>"Información adicional actualizada con éxito"
            ],200);

        }elseif(isset($person_id) && $person_id==-1){
                $person = new Person();
                $person->person_type = 'user';
                $person->name = $request->get('p_name');
                $person->document_type = $request->get('document_type');
                $person->document_number = $request->get('document_number');
                $person->address = $request->get('address');
                $person->phone = $request->get('phone');
                $person->email = $request->get('p_email');
                $person->status = 1;
                $person->save();
                $user = User::FindOrFail($id);
                $user->person_id = $person->id;
                $user->save();
                return response()->json([
                    "success"=>true,
                    "message"=>"Información adicional actualizada con éxito"
                ],200);

        }else{
            $existEmail = User::where("email","=",$request->get('email'))
                         ->where("id","!=",$id)
                         ->count();

            $existName = User::where("name","=",$request->get('name'))
                         ->where("id","!=",$id)
                         ->count();
            if($existEmail > 0){
                    return response()->json([
                    'success' => false,
                    'message' => 'Este correo ya está registrado.',
                ], 422);
            }elseif($existName > 0){
                return response()->json([
                    "success"=>false,
                    "message"=>"Este Nombre de usuario ya existe"
                ],422);
            }else{
                $user = User::FindOrFail($id);
                $user->name = $request->get('name');
                $user->email = $request->get('email');
                $user->password = bcrypt($request->get('password'));
                $user->role = $request->get('role');
                $user->update();
                return response()->json([
                    "success"=>true,
                    "message"=>"Usuario actualizado con exito"
                ],200);
            }
        }
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->status = 0;
        $user->update();
        return Redirect::to("segurity/user")->with("success","Usuario ha cambiado a estado inactivo");
    }
}
