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
    ->join("products","orders.productID","=","products.id")
    ->select("orders.id","orders.amount","orders.totalPrice","user.name","products.name")
    ->orderBy("orders.id","desc")
    -get();

    return Response::json($order);
  }


  public function store(Request $request)
  {
    $rules=[
      "productsID" => "required",
      "quantity" => "required",
      "totalPrice" => "required",
      "availability" => "required"
    ];

    $validator = Validator::make(Purifier::clean($request->all()),$rules);
    if($validator->fails())
    {
      return Response::json(["error" => "Please fill out all fields"]);
    }


    $products = Product::find($request->input('productsID'));

    if(empty($products))
    {
      return Response::json(["error" => "Product not found."]);
    }

    if($products->availability==0)
    {
      return Response::json(["error"=>"Product is temporarily unavailable"]);
    }


    $order = new Order;
    $order->userID = Auth::user()->id;
    $order->productsID = $request->input("productsID");
    $order->quantity = $request->input("quantity");
    $order->totalPrice=$request->input("amount")*$products->price;
    $order->comments=$request->input("comments");
    $order->save();

    return Response::json(["success"=>"You're order is complete."]);
  }


  public function update(Request $request)
  {
    $rules=[
      "productsID" => "required",
      "quantity" => "required",
      "totalPrice" => "required",
      "availability" => "required"
    ];

    $validator = Validator::make(Purifier::clean($request->all()),$rules);
    if($validator->fails())
    {
      return Response::json(["error" => "Please fill out all fields"]);
    }


    $products = Product::find($request->input('productsID'));

    if(empty($products))
    {
      return Response::json(["error" => "Product not found."]);
    }

    if($products->availability==0)
    {
      return Response::json(["error"=>"Product is temporarily unavailable"]);
    }


    $order = Order::find($id);
    $order->userID = Auth::user()->id;
    $order->productsID = $request->input("productsID");
    $order->quantity = $request->input("quantity");
    $order->totalPrice=$request->input("amount")*$products->price;
    $order->comments=$request->input("comments");
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
}
