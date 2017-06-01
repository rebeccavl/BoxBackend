<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Hash;
use JWTAuth;
use App\Subscriber;
use App\User;
use Response;
use File;
use Auth;

class SubscribersController extends Controller
{
    public function __construct()
    {
      $this->middleware('jwt.auth',['only'=>['index','destroy']]);
    }

    public function index()
    {
      $user = Auth::user();
      if($user->roleID !=1)
      {
        return Response::json(["error" => "You can't enter here."]);
      }

      $subscribers = Subscriber::all();
      return Response::json($subscribers);
    }

    public function store(Request $request)
    {
      $rules=[
        "firstName" => 'required',
        "lastName" => 'required',
        "email" => 'required',
      ];

      $validator = Validator::make(Purifier::clean($request->all()), $rules);

      if($validator->fails())
      {
        return Response::json(["error"=>"please fill out all of the fields"]);
      }


      $subscribers = new Subscriber;

      $subscribers->firstName = $request->input('firstName');
      $subscribers->lastName = $request->input('lastName');
      $subscribers->email = $request->input('email');
      $subscribers->unsubscribe = 1;
      $subscribers->save();

      return Response::json(["succes" => "Thanks for joining our email force!"]);
    }

    public function destroy($id)
    {
      $user = Auth::user();
      if($user->roleID !=1)
      {
        return Response::json(["error" => "You can't enter here."]);
      }

      $subscribers = Subscriber::find($id);
      $subscribers->unsubscribe = 0;
      $subscribers->save();

      return Response::json(['success' => 'Unsubscribed']);
    }
}
