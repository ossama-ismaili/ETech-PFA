<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if(Auth::check())
        {
            $cart_items=Auth::user()->cart->cart_items()->orderBy('created_at','desc')->paginate(4);
            $total=Auth::user()->cart->total;

            return view('pages.cart')
            ->with('cart_items', $cart_items)
            ->with('total', $total);
        }
        else{
            abort(401);
        }
    }

    public function add(Request $request, $product_id)
    {
        if(Auth::check())
        {
            $product_quantity=Product::where('id',$product_id)->value('quantity');
            $quantity=$request->input('quantity');
            if($product_quantity>=$quantity)
            {
                $check=Auth::user()->cart->cart_items()->where('product_id',$product_id)->count() > 0;
                if($check && $quantity > 0)
                {
                    $current_quantity=Auth::user()->cart->cart_items()
                    ->where('product_id',$product_id)->value('quantity');
                    if($current_quantity+$quantity<=$product_quantity)
                    {
                        Auth::user()->cart->cart_items()->where('product_id',$product_id)
                        ->update(['quantity'=>$current_quantity+$quantity]);
                    }
                    else
                    {
                        Auth::user()->cart->cart_items()->where('product_id',$product_id)
                        ->update(['quantity'=>$product_quantity]);
                    }
                }
                else if($quantity > 0)
                {
                    $cart_id=Auth::user()->cart->id;
                    $item=CartItem::create([
                        'cart_id' => $cart_id,
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                    ]);
                    $item->save();
                }
                else
                {
                    abort(400);
                }
                CartController::updateCart();
                return redirect('/cart');
            }
            else
            {
                abort(400);
            }
        }
        else
        {
            abort(401);
        }
    }

    public function update(Request $request,$cart_item_id)
    {
        if(Auth::check())
        {
            $cart_item=Auth::user()->cart->cart_items()->where('id',$cart_item_id)->first();
            $product_quantity=Product::where('id',$cart_item->product_id)->value('quantity');

            if(isset($request->increment))
            {
                $quantity=Auth::user()->cart->cart_items()
                ->where('id',$cart_item_id)->value('quantity');
                if($product_quantity>$quantity)
                {
                    Auth::user()->cart->cart_items()
                    ->where('id',$cart_item_id)->update(['quantity'=>$quantity+1]);

                    CartController::updateCart();
                    $total=Auth::user()->cart->total;
                    return response()->json([
                        'success' => true,
                        'msg' => Auth::user()->cart->cart_items()
                                ->where('id',$cart_item_id)->value('quantity'),
                        'total'=> $total,
                    ]);
                }
                else
                {
                    $total=Auth::user()->cart->total;
                    return response()->json([
                        'success' => true,
                        'msg' => Auth::user()->cart->cart_items()
                                ->where('id',$cart_item_id)->value('quantity'),
                        'total'=> $total,
                    ]);
                }
            }
            else if(isset($request->decrement))
            {
                $quantity=Auth::user()->cart->cart_items()->where('id',$cart_item_id)->value('quantity');
                if($quantity>1)
                {
                    Auth::user()->cart->cart_items()->where('id',$cart_item_id)->update(['quantity'=>$quantity-1]);
                    CartController::updateCart();
                    $total=Auth::user()->cart->total;
                    return response()->json([
                        'success' => true,
                        'msg' => Auth::user()->cart->cart_items()
                                ->where('id',$cart_item_id)->value('quantity'),
                        'total' =>$total,
                    ]);
                }
                else
                {
                    $total=Auth::user()->cart->total;
                    return response()->json([
                        'success' => true,
                        'msg' => Auth::user()->cart->cart_items()
                                ->where('id',$cart_item_id)->value('quantity'),
                        'total'=> $total,
                    ]);
                }
            }
            else if(isset($request->quantity)){
                $quantity=$request->quantity;
                if($quantity>0 && $product_quantity > $quantity)
                {
                    Auth::user()->cart->cart_items()->where('id',$cart_item_id)->update(['quantity'=>$quantity]);
                    CartController::updateCart();
                    $total=Auth::user()->cart->total;
                    return response()->json([
                        'success' => true,
                        'msg' => $quantity,
                        'total' =>$total,
                    ]);
                }
                else
                {
                    $total=Auth::user()->cart->total;
                    $qte=Auth::user()->cart->cart_items()
                    ->where('id',$cart_item_id)->value('quantity');

                    return response()->json([
                        'success' => true,
                        'msg' => $qte,
                        'total'=> $total,
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'error' => true,
                ]);
            }
        }
        else
        {
            abort(401);
        }
    }

    public function delete($cart_item_id)
    {
        if(Auth::check())
        {
            Auth::user()->cart->cart_items()->where('id',$cart_item_id)->delete();
            CartController::updateCart();
            return back();
        }
        else
        {
            abort(401);
        }
    }

    public function deleteAll()
    {
        if(Auth::check())
        {
            Auth::user()->cart->cart_items()->delete();
            CartController::updateCart();
            return back();
        }
        else
        {
            abort(401);
        }
    }

    public static function updateCart()
    {
        if(Auth::check())
        {
            $items=Auth::user()->cart->cart_items;
            $count=Auth::user()->cart->cart_items()->count();
            $total=0;
            foreach($items as $item)
            {
                $total = $total + $item->product->price * $item->quantity;
            }
            Auth::user()->cart->update(['total'=>$total]);
            Auth::user()->cart->update(['items_count'=>$count]);
        }
    }
}
