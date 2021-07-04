<?php

namespace barrilete\Http\Controllers;

use barrilete\Newsletter;
use Crypt;
use Exception;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
     * User Options
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
     * Get Dashboard
     * @return Application|Factory|View
     */
    public function dashboard()
    {
        return view('auth.users');
    }

    /**
     * User Account
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function account(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::query()->find($id);
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
     * Users List
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
     * User Profile
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $user = User::query()->find($id);

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
     * User Edit
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function edit(Request $request, $id)
    {
        if($request->ajax()) {
            $user = User::query()->find($id);
            $view = isset($request->home) ? 'auth.myaccount.edit' : 'auth.users.edit';

            return $user
                ? response()->json([
                    'view' => view($view, compact('user'))->render()
                ])->header('Content-Type', 'application/json')
                : response()->json(['error' => 'El usuario no existe'],404);
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * User Update
     * @param userRequest $request
     * @return Factory|JsonResponse|View
     */
    public function update(userRequest $request)
    {
        $user = $this->updateUserData($request);
        if($user) {
            return view('auth.users.show', compact('user'))
            ->with(['success' => 'El usuario se ha actualizado correctamente.']);
        }

        return response()->json(['error' => 'El usuario no existe']);
    }

    /**
     * My Account Update
     * @param userRequest $request
     * @return Application|Factory|JsonResponse|View
     */
    public function myAccountUpdate(userRequest $request)
    {
        $user = $this->updateUserData($request);
        if($user) {
            return view('auth.myaccount.edit', compact('user'))
                ->with(['success' => 'Tu cuenta se ha actualizado.']);
        }

        return response()->json(['error' => 'El usuario no existe']);
    }

    /**
     * Update User Data
     * @param $request
     * @return Builder|Builder[]|Collection|Model|null
     */
    protected function updateUserData($request)
    {
        $user = User::query()->findOrFail($request->id);
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

        return $user;
    }

    /**
     * Photo Upload
     * @param $file
     * @param $user
     * @return string
     */
    protected function uploadImage($file, $user) : string
    {
        $image_path = public_path('img/users/images/'.$user->photo);
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $newFile = date('h-i-s').'-'.str_slug($file->getClientOriginalName(),'-').'.'.$file->getClientOriginalExtension();
        $upload = public_path('img/users/images/' . $newFile);
        Image::make($file->getRealPath())->resize(100, 100)->save($upload);

        return $newFile;
    }

    /**
     * User Delete
     * @param Request $request
     * @param $id
     * @return RedirectResponse|JsonResponse
     * @throws Exception
     */
    public function delete(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $user = User::query()->find($id);
                if($user) {
                    $image_path = public_path('img/users/images/'.$user->photo);
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
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     */
    public function deleteOwnAccount(Request $request)
    {
        if ($request->ajax()) {
            $user = User::query()->find(Auth::id());
            $user->delete();
            Auth::logout();
            $request->session()->flash('success', 'Tu cuenta ha sido eliminada.');

            return response()->json([route('login')]);
        }

        return abort(404);
    }

    /**
     * Make Admin
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     * @deprecated Change Functionality
     */
    public function makeAdmin(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->is_admin) {
                $user = User::query()->find($id);
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
     * Admin Delete
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     * @deprecated Change functionality
     */
    public function deleteAdmin(Request $request, $id)
    {
        if($request->ajax()) {
            if(Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $user = User::query()->find($id);
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
     * User Menu
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function menu(Request $request)
    {
        if($request->ajax()) {
            $loginRedirect = Crypt::encrypt($request->login_redirect);
            return response()->json([view('auth.users.menu', compact('loginRedirect'))->render()]);
        }

        return abort(404);
    }

    /**
     * Notify Messages
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function notifyMessages(Request $request)
    {
        if($request->ajax()) {
            return response()->json([view('auth.users.notifications.messages')->render()]);
        }

        return abort(404);
    }

    /**
     * Get User Notifications
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function notifyReactions(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([view('auth.users.notifications.reactions')->render()]);
        }

        return abort(404);
    }

    /**
     * Get All User Notifications
     * @return MorphMany
     */
    protected function getAllUserNotifications() : MorphMany
    {
        return Auth::user()->notifications()->orderBy('created_at')->limit(10);
    }

    /**
     * Update User Password
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function updatePassword(Request $request)
    {
        if ($request->ajax()) {
            if (Hash::check($request->current_password, auth()->user()->password)) {
                User::query()->find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);

                return $this->editMyPassword('success', 'La contraseña se ha actualizado correctamente.');
            }

            return $this->editMyPassword('error','La contraseña actual es incorrecta.');
        }

        return abort(404);
    }

    /**
     * Edit My Privacy
     * @return JsonResponse
     * @throws Throwable
     */
    public function myAccountConfig() : JsonResponse
    {
        return response()->json([
            'view' => view('auth.myaccount.privacy')->render()
        ]);
    }

    /**
     * @param null $status
     * @param null $message
     * @return JsonResponse
     * @throws Throwable
     */
    public function editMyPassword($status = null, $message = null) : JsonResponse
    {
        return response()->json([
            'view' => view('auth.myaccount.change-password')->render(),
            'status' =>  $status,
            'message' => $message
        ]);
    }

    /**
     * Get Users
     * @param Request $request
     * @return void|JsonResponse
     * @throws Throwable
     */
    public function getUsers(Request $request)
    {
        if($request->ajax()) {
            $query = $request->get('query');
            $result = User::query()
                ->where('name','LIKE', '%'.$query.'%')
                ->where('id','!=', Auth::id())
                ->get();

            return response()->json([view('auth.myaccount.messages.autocomplete', compact('result'))->render()]);
        }

        return abort(404);
    }
}
