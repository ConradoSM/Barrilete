<?php

namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use barrilete\Http\Requests\userRequest;
use barrilete\User;
use Image;
use File;
use Throwable;

class UsersController extends Controller
{
    /**
     * OPCIONES DE USUARIO
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function options(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'view' => view('auth.users.options')->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * CUENTA DE USUARIO
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function account(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::find($id);
            if($user) {
                if($user->id == Auth::user()->id) {
                    return response()->json([
                        'view' => view('auth.users.show', compact('user'))->render()
                    ])->header('Content-Type', 'application/json');
                }
                return response()->json(['error' => 'El perfil al que estás tratando de acceder no es el tuyo']);
            }
            return response()->json(['error' => 'El usuario no existe']);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * LISTA DE USUARIOS
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function users(Request $request)
    {
        if($request->ajax()) {
            if(Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $users = User::all();
                return response()->json([
                    'view' => view('auth.users.users', compact('users'))->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'No eres administrador del sistema'], 401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * PERFIL USUARIO
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $user = User::find($id);
                return $user
                    ? response()->json([
                        'view' => view('auth.users.show', compact('user'))->render()
                    ])->header('Content-Type', 'application/json')
                    : response()->json(['error' => 'El usuario no existe'],404);
            }
            return response()->json(['error' => 'No eres administrador del sistema'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * EDITAR USUARIO
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function edit(Request $request, $id)
    {
        if($request->ajax()) {
            $user = User::find($id);
            return $user
                ? response()->json([
                    'view' => view('auth.users.edit', compact('user'))->render()
                ])->header('Content-Type', 'application/json')
                : response()->json(['error' => 'El usuario no existe'],404);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * ACTUALIZAR USUARIO
     * @param userRequest $request
     * @return Factory|JsonResponse|View
     */
    public function update(userRequest $request)
    {
        $user = User::find($request->id);
        if($user) {
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->photo = $request->file('photo') ? $this->uploadImage($request->file('photo'), $user) : $user->photo;
            $user->birthday = $request['birthday'];
            $user->phone = $request['phone'];
            $user->address = $request['address'];
            $user->city = $request['city'];
            $user->state = $request['state'];
            $user->country = $request['country'];
            $user->description = $request['description'];
            $user->save();
            return view('auth.users.show', compact('user'))
            ->with(['success' => 'El usuario se ha actualizado correctamente.']);
        }
        return response()->json(['error' => 'El usuario no existe']);
    }

    /**
     * SUBIR FOTO
     * @param $file
     * @param $user
     * @return string
     */
    protected function uploadImage($file, $user)
    {
        $image_path = public_path('img/users/'.$user->photo);
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $newFile = date('h-i-s').'-'.str_slug($file->getClientOriginalName(),'-').'.'.$file->getClientOriginalExtension();
        $upload = public_path('img/users/' . $newFile);
        Image::make($file->getRealPath())->resize(75, 75)->save($upload);
        return $newFile;
    }

    /**
     * BORRAR USUARIO
     * @param Request $request
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function delete(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $user = User::find($id);
                if($user) {
                    $image_path = public_path('img/users/'.$user->photo);
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                    $user->delete();
                    if($user->id == Auth::user()->id) {
                        Auth::logout();
                        return redirect()->route('login')->with('success','Tu cuenta ha sido eliminada.');
                    }
                    return redirect()->route('users')->with('success','El usuario ha sido eliminado.');
                }
                return redirect()->route('users')->with('error','El usuario no existe.');
            }
            return response()->json(['error' => 'No eres administrador del sistema.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * HACER ADMINISTRADOR
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     * @deprecated Change Functionality
     */
    public function makeAdmin(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->is_admin) {
                $user = User::find($id);
                if($user) {
                    if(!$user->is_admin) {
                        $user->is_admin = true;
                        $user->save();
                        return redirect()->route('users')->with('success','El usuario ahora es administrador del sitio.');
                    }
                    return redirect()->route('users')->with('error','El usuario ya es administrador.');
                }
                return redirect()->route('users')->with('error','El usuario no existe.');
            }
            return response()->json(['error' => 'No eres administrador del sistema.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * BORRAR ADMINISTRADOR
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     * @deprecated Change functionality
     */
    public function deleteAdmin(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $user = User::find($id);
                if($user) {
                    if($user->is_admin) {
                        $user->is_admin = false;
                        $user->save();
                        return redirect()->route('users')->with('success','Se quitaron todos los privilegios de administración al usuario.');
                    }
                    return redirect()->route('users')->with('error','El usuario no es administrador.');
                }
                return redirect()->route('users')->with('error','El usuario no existe.');
            }
            return response()->json(['error' => 'No eres administrador del sistema.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function menu(Request $request)
    {
        if($request->ajax()) {
            return response()->json([view('auth.users.menu')->render()]);
        }
        return abort(404);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function inbox(Request $request)
    {
        if($request->ajax()) {
            return response()->json([view('auth.users.inbox')->render()]);
        }
        return abort(404);
    }

    /**
     * Get User Unread Notifications
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function getUnreadNotifications(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $userUnreadNotifications = Auth::user()->notifications()->orderBy('created_at')->limit(10)->get();
                return response()->json([view('auth.users.notifications', compact('userUnreadNotifications'))->render()]);
            }
            return response()->json([view('auth.users.notifications')->render()]);
        }
        return abort(404);
    }
}
