<?php

namespace barrilete\Http\Controllers;

use Auth;
use barrilete\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use barrilete\Newsletter;
use Illuminate\View\View;

class NewsletterController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $email = $request->email;
            $userId = Auth::check() ? Auth::id() : null;

            if ($userId) {
                $email = Auth::user()->email;
                $userSubscriptionExist = Newsletter::query()->where('user_id', $userId)->orWhere('email', $email);
                if ($userSubscriptionExist->first()) {
                    $userSubscriptionExist->update([
                        'status' => $request->status,
                        'user_id' => $userId
                    ]);
                    $message = $request->status ? 'Te has suscripto con éxito.' : 'Tu suscripción se ha cancelado.';
                    return response()->json([
                        'type' => 'success',
                        'message' => $message
                    ]);
                }
            }

            $userExist = User::query()->where('email', $email);
            $existEmail = Newsletter::query()->where('email', $email);
            if (!$userId && $userExist->first() || $existEmail->first()) {
                return response()->json([
                    'type' => 'warning',
                    'message' => 'El e-mail ingresado ya existe, por favor introduzca uno distinto.'
                ]);
            }

            $newsletter = new Newsletter();
            $newsletter->email = $email;
            $newsletter->user_id = $userId;
            $newsletter->status = true;
            $newsletter->additional_information = null;
            $newsletter->save();

            /** TODO: SEND MAIL AFTER SUBSCRIPTION  **/

            return response()->json([
                'type' => 'success',
                'message' => 'Gracias por suscribirte a nuestro boletín informativo.'
            ]);
        }

        return abort(404);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function delete(Request $request)
    {
        $email = $request->email;
        $newsletter = Newsletter::query()->where('email', $email);
        $response = [
            'type' => 'error',
            'message' => 'La dirección de e-mail no existe en nuestros sistemas.'
        ];
        if ($newsletter->first()) {
            $newsletter->delete();
            $response = [
                'type' => 'success',
                'message' => 'La suscripción ha sido eliminada de nuestros sistemas.'
            ];
        }

        return view('newsletter.delete', compact('response'));
    }
}
