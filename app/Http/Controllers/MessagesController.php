<?php

namespace barrilete\Http\Controllers;

use barrilete\Notifications\UsersMessages;
use barrilete\User;
use Illuminate\Support\Facades\Auth;
use barrilete\Messages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class MessagesController extends Controller
{
    /**
     * Write new message
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function writeMessage(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['view' => view('auth.myaccount.messages.send')->render()]);
        }

        return abort(404);
    }

    /**
     * Save Message
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function save(Request $request)
    {
        if ($request->ajax() AND Auth::check()) {
            $request->validate([
                'user_id' => 'required|integer',
                'body' => 'required'
            ]);
            $conversationId = $request->parent_id ? $request->parent_id : $this->getConversationId($request->user_id);
            /** Save message */
            $newMessage = new Messages();
            $newMessage->from = Auth::id();
            $newMessage->to = $request->user_id;
            $newMessage->parent_id = $conversationId;
            $newMessage->body = $request->body;
            $newMessage->status = false;
            $newMessage->save();
            /** Get parent id */
            $parentId = $conversationId ? $conversationId : $newMessage->id;
            /** Sen Notification */
            $this->sendNotification($parentId, $newMessage->to, $newMessage->body);

            return $this->getConversationById($request, $parentId, 'success', 'Mensaje enviado.');
        }

        return abort(404);
    }

    /**
     * SEnd Notification
     * @param $conversationId
     * @param $userId
     * @param $message
     */
    protected function sendNotification($conversationId, $userId, $message)
    {
        $user = User::query()->findOrFail($userId);
        $message = strlen($message) > 35 ? substr($message, 0, 35).'...' : $message;
        $notificationDetail= [
            'from' => Auth::user()->name,
            'to' => $user->id,
            'conversation_id' => $conversationId,
            'message' => $message
        ];
        $user->notify(new UsersMessages($notificationDetail));
    }

    /**
     * Get Conversation By Id
     * @param Request $request
     * @param $id
     * @param null $status
     * @param null $statusMessage
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function getConversationById(Request $request, $id, $status = null, $statusMessage = null)
    {
        if ($request->ajax()) {
            $result = Messages::query()->find($id);
            if ($result) {
                $replies = $result->replies();

                return response()->json([
                    'view' => view('auth.myaccount.messages.view', compact('result'))->render(),
                    'status' => $status,
                    'message' => $statusMessage,
                    'replies' => view('auth.myaccount.messages.replies', compact('replies'))->render(),
                    'next_page' => $replies->nextPageUrl()
                ]);
            } else {
                $statusMessage = 'La conversación ha sido borrada.';
                return $this->myMessages($request, 'warning', $statusMessage, 'inbox');
            }
        }

        return abort(404);
    }

    /**
     * My Messages
     * @param Request $request
     * @param $status
     * @param null $statusMessage
     * @param null $box
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function myMessages(Request $request, $status = null, $statusMessage = null, $box = null)
    {
        if ($request->ajax()) {
            $box = $request->box ? $request->box : $box;
            $result = $box == 'outbox' ? Auth::user()->outboxMessages() : Auth::user()->inboxMessages();

            return response()->json([
                'view' => view('auth.myaccount.messages.inbox', compact('result'))->with('box', $box)->render(),
                'status' => $status,
                'message' => $statusMessage
            ]);
        }

        return abort(404);
    }

    /**
     * Get Conversation Id
     * @param $userId
     * @return mixed|null
     */
    protected function getConversationId($userId)
    {
        $messageFromMe = Messages::query()
            ->where('parent_id', null)
            ->where('from', Auth::id())
            ->where('to', $userId)
            ->first();

        $messageToMe = Messages::query()
            ->where('parent_id', null)
            ->where('from', $userId)
            ->where('to', Auth::id())
            ->first();

        return $messageFromMe ? $messageFromMe->id : ($messageToMe ? $messageToMe->id : null);
    }

    /**
     * Delete Message
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $message = Messages::query()->find($id);
            $replies = Messages::query()->where('parent_id', $id)->get();
            if ($message) {
                foreach ($replies as $reply) {
                    $reply->delete();
                }
                $message->delete();
            }

            return $this->myMessages($request, 'success', 'Se borró el mensaje.', 'inbox');
        }

        return abort(404);
    }
}
