<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'cedula' => ['required','numeric','digits_between:1,8'],
            'telefono' => ['required','numeric','digits:11'],
            'apellido' => ['required','string','max:255'],
        ]);


        return User::create([
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'cedula' => $request['cedula'],
            'telefono' => $request['telefono'],
            'img_url' => 'user.png',
            'activo' => 1

        ]);
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

    public function profile() {
        return auth('api')->user();
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
        $user = User::findOrFail($id);
        $this->validate($request, [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'cedula' => ['required','numeric','digits_between:1,8'],
            'telefono' => ['required','numeric','digits:11'],
            'apellido' => ['required','string','max:255'],
            'img_url' => ['sometimes', 'regex:(jpeg|jpg|png)'],
        ]);
        if($request->img_url) {
            $name = time() . '.' . explode('/',explode(':',substr($request->img_url,0,strpos($request->img_url,";")))[1])[1];

        \Image::make($request->img_url)->save(public_path('img/').$name); 
        $request->merge(['img_url' => $name]);

        }

        $user->update($request->all());

    }

    public function updateProfile(Request $request) {
        $user = auth('api')->user();

        return $request->img_url;
        // $this->validate($request, [
        //     'nombre' => ['required', 'string', 'max:255'],
        //     'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        //     'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        //     'cedula' => ['required','numeric','digits_between:1,8'],
        //     'telefono' => ['required','numeric','digits:11'],
        //     'apellido' => ['required','string','max:255'],
        //     'img_url' => ['sometimes', 'regex:(jpeg|jpg|png)'],
        // ]);

        // if($request->img_url) {
        //     $name = time() . '.' . explode('/',explode(':',substr($request->img_url,0,strpos($request->img_url,";")))[1])[1];

        // \Image::make($request->img_url)->save(public_path('img/').$name); 
        // $request->merge(['img_url' => $name]);

        // }

        // $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->activo = 0;
        $user->save();
        return ['message' => 'Hello'];
    }
}
