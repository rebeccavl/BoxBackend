<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Hash;
use JWTAuth;
use App\Role;
use App\User;
use Response;
use File;
use Auth;

class RolesController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.auth',['only'=>['index','store','update','destroy']]);
  }

  public function index()
  {
    $roles = Role::all();
    return Response::json($roles);
  }


  public function store(Request $request)
  {
    $rules=[
      "role" => 'required',
    ];

  $validator = Validator::make(Purifier::clean($request->all()), $rules);

  if($validator->fails())
  {
    return Response::json(["error"=>"please fill out all of the fields"]);
  }

  $user = Auth::user();
  if($user->roleID != 1)
  {
    return Response::json(["error" => "You can't enter here."]);
  }

  $roles = new Role;

  $roles->name = $request->input('role');
  $roles->save();

  return Response::json(["succes" => "Role successfully added."]);

}


  public function update($id, Request $request)
  {
    $rules=[
      "role" => 'required',
    ];

  $validator = Validator::make(Purifier::clean($request->all()), $rules);

  if($validator->fails())
  {
    return Response::json(["error"=>"please fill out all of the fields"]);
  }

  $user = Auth::user();
  if($user->roleID != 1)
  {
    return Response::json(["error" => "You can't enter here."]);
  }

    $role = Role::find($id);
    $role->name = $request->input('role');
    $role->save();
    return Response::json(["success"=>"Role Updated."]);


    $validator = Validator::make(Purifier::clean($request->all()), $rules);

    if($validator->fails())
    {
      return Response::json(["error"=>"Please fill out all fields."]);
    }
  }


  public function show($id)
  {
    $role = Role::find($id);
    return Response::json($role);
  }


  public function destroy($id)
  {
    $user = Auth::user();
    if($user->roleID != 1)
    {
      return Response::json(["error" => "You can't enter here."]);
    }

    $role = Role::find($id);
    $role->delete();

    return Response::json(['success' => 'Role deleted.']);
  }

}
