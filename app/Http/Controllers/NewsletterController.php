<?php

namespace barrilete\Http\Controllers;

use Auth;
use barrilete\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use barrilete\Newsletter;
use Illuminate\View\View;
use Throwable;

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

    /**
     * @param array $message
     * @return JsonResponse
     * @throws Throwable
     */
    public function getAllSubscriptions(array $message = []) : JsonResponse
    {
        $subscriptions = Newsletter::query()->orderBy('created_at', 'DESC')
            ->paginate(20)
            ->setPath(route('getAllSubscriptions'));

        return response()->json([
            'view' => view('newsletter.subscriptions', compact('subscriptions', 'message'))->render()
        ])->header('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @return JsonResponse | void
     * @throws Throwable
     */
    public function adminCancel(Request $request)
    {
        if ($request->ajax()) {
            $email = $request->email;
            $newsletter = $this->getNewsletterByEmail($email);
            $message = [
                'type' => 'error',
                'value' => 'El email no existe.'
            ];

            if ($newsletter->first()) {
                $newsletter->update([
                    'status' => false
                ]);
                $message['type'] = 'success';
                $message['value'] = 'La suscripción se ha cancelado.';
            }

            return $this->getAllSubscriptions($message);
        }

        return abort(404);
    }

    /**
     * @param Request $request
     * @return JsonResponse | void
     * @throws Throwable
     */
    public function adminDelete(Request $request)
    {
        if ($request->ajax()) {
            $email = $request->email;
            $newsletter = $this->getNewsletterByEmail($email);
            $message = [
                'type' => 'error',
                'value' => 'El email ' . $email . ' no existe.'
            ];

            if ($newsletter->first()) {
                $newsletter->delete();
                $message['type'] = 'success';
                $message['value'] = 'La suscripción se ha borrado.';
            }

            return $this->getAllSubscriptions($message);
        }

        return abort(404);
    }

    /**
     * @param $email
     * @return Builder
     */
    protected function getNewsletterByEmail($email)
    {
        return Newsletter::query()->where('email', $email);
    }
}
