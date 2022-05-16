<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Supports;
use Core\Factories\NotificationFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'full_name' => ['required', 'string'],
                'email' => ['required', 'email', 'string'],
                'phone' => ['required', 'numeric'],
                'content' => ['required', 'string'],
            ]);

            if (Support::where('email', $request->email)->where('created_at', '>', now()->subSeconds(5))->exists()) {
                return redirect('/login')->with(['success_verified' => 'Successful send support request!']);
            }

            $support = new Support();
            $data = $request->only($support->getFillable());
            $model = $support->fill($data)->save();
            if (!$model) {
                abort(500);
            }

            $notify = new NotificationFactory();
            $notify->produceNotification(config('constants.telegram.support'), [
                '{USER}' => $data['email'],
                '{CONTENT}' => $data['content']
            ]);

            Auth::logout();
            return redirect('/login')->with(['success_verified' => 'Successful send support request!']);
        }

        return view('auth.support');
    }
}
