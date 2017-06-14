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

class OrdersController extends Controller
{
  public function __construct()
    {
      $this->middleware('jwt.auth',['only'=>['store','update','destroy']]);
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
      "email" => "required",
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
    $order->firstName->input("firstName");
    $order->lastName->input("lastName");
    $order->email->input("email");
    $order->streetAddress->input("streetAddress");
    $oder->phoneNumber->input("phoneNumber");
    $order->save();

    return Response::json(["success"=>"You're order is complete."]);
  }


  public function update(Request $request)
  {
    $rules=[
      "categories" => "required",
      "plan" => "required",
      "firstName" => "required",
      "lastName" => "required",
      "email" => "required",
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
    $order->firstName->input("firstName");
    $order->lastName->input("lastName");
    $order->email->input("email");
    $order->streetAddress->input("streetAddress");
    $oder->phoneNumber->input("phoneNumber");
    $order->save();

    return Response::json(["success"=>"You're order is complete."]);
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
    $categories=Order::where('userID','=',$user->id)->select('id','categories')->first();
    $array = explode(',',$categories->categories);
    return Response::json($array);
  }
}
