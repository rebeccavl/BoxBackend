<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Hash;
use JWTAuth;
use App\Order;
use App\Product;
use Response;
use File;
use Auth;
use App\User;

class OrdersController extends Controller
{
  public function __construct()
    {
      $this->middleware('jwt.auth',['only'=>['update','destroy','getCategories']]);
    }

  public function index()
  {
    $order = Order::all();
    return Response::json($order);

    $orders = Order::where("userID","=",$userID)
    ->join("users","orders.userID","=","user.id")
    ->select("orders.id","orders.amount","orders.totalPrice","user.name","orders.plan")
    ->orderBy("orders.id","desc")
    -get();

    return Response::json($order);
  }


  public function store(Request $request)
  {
    $rules=[
      "categories" => "required",
      "plan" => "required",
      "firstName" => "required",
      "lastName" => "required",
      "accountEmail" => "required",
      "streetAddress" => "required",
      "phoneNumber" => "required",
    ];

    $validator = Validator::make(Purifier::clean($request->all()),$rules);
    if($validator->fails())
    {
      return Response::json(["error" => "Please fill out all fields"]);
    }

    $user = User::where("email","=",$request->input('accountEmail'))->first();
    if(empty($user))
    {
      if(strlen($request->input("accountEmail"))>32)
      {
        return Response::json(["error"=>"Your email is too long. "]);
      }

      if(strlen($request->input("passwordSignUp"))<8)
      {
        return Response::json(["error"=>"Your password must be at least 8 characters"]);
      }

      $user = new User;
      $user->name = $request->input('firstName');
      $user->email = $request->input('accountEmail');
      $user->password = Hash::make($request->input('passwordSignUp'));
      $user->roleID = 2;
      $user->save();
    }

    $order = Order::where('userID','=',$user->id)->first();
    if(empty($order))
    {
      $order = new Order;
      $order->userID=$user->id;
      $order->categories=$request->input("categories");
      $order->plan=$request->input("plan");
      $order->firstName=$request->input("firstName");
      $order->lastName=$request->input("lastName");
      $order->email=$request->input("accountEmail");
      $order->streetAddress=$request->input("streetAddress");
      $order->phoneNumber=$request->input("phoneNumber");
      $order->save();

      return Response::json(["success"=>"You're order is complete."]);
    }
    else {
      return Response::json(["error"=>"You already have an account"]);
    }
  }


  public function update(Request $request,$id)
  {
    $rules=[
      "categories" => "required",
      "plan" => "required",
      "firstName" => "required",
      "lastName" => "required",
      "accountEmail" => "required",
      "streetAddress" => "required",
      "phoneNumber" => "required",
    ];

    $validator = Validator::make(Purifier::clean($request->all()),$rules);
    if($validator->fails())
    {
      return Response::json(["error" => "Please fill out all fields"]);
    }

    $order = Order::find($id);
    $order->categories=$request->input("categories");
    $order->plan=$request->input("plan");
    $order->firstName=$request->input("firstName");
    $order->lastName=$request->input("lastName");
    $order->email=$request->input("accountEmail");
    $order->streetAddress-$request->input("streetAddress");
    $order->phoneNumber=$request->input("phoneNumber");
    $order->save();

    return Response::json(["success"=>"You complete me."]);
  }


  public function show($id)
  {
    $order = Order::find($id);

    return Response::json($order);
  }



  public function destroy($id)
  {
    $order = Order::find($id);

    $user = Auth::user();

    if($user->roleID !=1 || $user-id != $order->$userID)

    return Response::json(["error"=>"You are not authorized to complete this action."]);

    $order->delete();

    return Response::json(['success' => 'Order Cancelled.']);
  }

  public function getCategories()
  {
    $user = Auth::user();
    $order=Order::where('userID','=',$user->id)->first();
    $categories = explode(',',$order->categories);
    $plans = explode(',',$order->plan);
    return Response::json(['categories'=>$categories,'plans'=>$plans,'user'=>$order]);
  }
}
