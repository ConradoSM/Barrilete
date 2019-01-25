<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Http\Requests\userRequest;
use barrilete\User;
use Illuminate\Support\Facades\Auth;
use Image;
use File;

class UsersController extends Controller
{
    // USER OPTIONS
    public function options(Request $request){
        
        if ($request->ajax()) {
            
            return view('auth.users.options');
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);       
    }
    
    // USER ACCOUNT
    public function account(Request $request, $id){
        
        if ($request->ajax()) {
            
            $user = User::find($id);
            
            if($user) {
                
                if($user->id == Auth::user()->id) {
                    
                    return view('auth.users.show', compact('user'));
                    
                } else return response()->json(['Error' => 'El perfil al que estás tratando de acceder no es el tuyo']);
                    
            } else return response()->json(['Error' => 'El usuario no existe']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);       
    }
    
    //USERS LIST
    public function users(Request $request){
        
        if(Auth::user()->is_admin) {
        
            if($request->ajax()) {

                $users = User::all();
                return view('auth.users.users', compact('users'));
                
            } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
            
        } else return response()->json(['Error' => 'No eres administrador del sistema']);
    }
    
    //VIEW USER
    public function show(Request $request, $id) {
        
        if(Auth::user()->is_admin) {
        
            if($request->ajax()) {

                $user = User::find($id);
                
                if($user) {
                    
                    return view('auth.users.show', compact('user'));
                    
                } else return response()->json(['Error' => 'El usuario no existe']);
                
            } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
            
        } else return response()->json(['Error' => 'No eres administrador del sistema']);     
    }

    //EDIT USER
    public function edit(Request $request, $id) {
        
        if($request->ajax()) {

            $user = User::find($id);

            if($user) {

                return view('auth.users.edit', compact('user'));

            } else return response()->json(['Error' => 'El usuario no existe']);

        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);   
    }
    
    //UPDATE USER
    public function update(userRequest $request) {

        $user = User::find($request->id);

        if($user) {

            if (empty($request->file('photo'))) {

                $user -> name = $request['name'];
                $user -> email = $request['email'];
                $user -> birthday = $request['birthday'];
                $user -> phone = $request['phone'];
                $user -> address = $request['address'];
                $user -> city = $request['city'];
                $user -> state = $request['state'];
                $user -> country = $request['country'];
                $user -> description = $request['description'];
                $user -> save(); 

                return view('auth.users.show', compact('user'))
                ->with(['Exito' => 'El usuario se ha actualizado correctamente.']);

            } else

                $image_path = public_path('img/users/'.$user->photo);

                if (File::exists($image_path)) {

                    File::delete($image_path);

                    $newFile = time().'-'.$request->file('photo')->getClientOriginalName();
                    $upload = public_path('img/users/'.$newFile);

                    Image::make($request->file('photo')->getRealPath())->resize(200, 200)->save($upload);
                    
                    $user -> name = $request['name'];
                    $user -> email = $request['email'];
                    $user -> photo = $newFile;
                    $user -> birthday = $request['birthday'];
                    $user -> phone = $request['phone'];
                    $user -> address = $request['address'];
                    $user -> city = $request['city'];
                    $user -> state = $request['state'];
                    $user -> country = $request['country'];
                    $user -> description = $request['description'];
                    $user -> save(); 

                    return view('auth.users.show', compact('user'))
                    ->with(['Exito' => 'El usuario se ha actualizado correctamente.']);
                    
                } else
                    
                    $newFile = time().'-'.$request->file('photo')->getClientOriginalName();
                    $upload = public_path('img/users/'.$newFile);

                    Image::make($request->file('photo')->getRealPath())->resize(200, 200)->save($upload);
                    
                    $user -> name = $request['name'];
                    $user -> email = $request['email'];
                    $user -> photo = $newFile;
                    $user -> birthday = $request['birthday'];
                    $user -> phone = $request['phone'];
                    $user -> address = $request['address'];
                    $user -> city = $request['city'];
                    $user -> state = $request['state'];
                    $user -> country = $request['country'];
                    $user -> description = $request['description'];
                    $user -> save(); 

                    return view('auth.users.show', compact('user'))
                    ->with(['Exito' => 'El usuario se ha actualizado correctamente.']);

        } else return response()->json(['Error' => 'El usuario no existe']);
    }    
}
