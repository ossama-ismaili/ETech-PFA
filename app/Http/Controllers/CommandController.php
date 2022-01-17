<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Command;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandController extends Controller
{
    public function add($cart_item_id)
    {
        if(Auth::check())
        {
            $product_id=CartItem::find($cart_item_id)->value('product_id');
            $quantity=CartItem::find($cart_item_id)->value('quantity');
            $check_command=Command::where('user_id', Auth::user()->id)
            ->where('product_id',$product_id)
            ->where('status',false)
            ->count() == 0;
            $check_quantity=CartItem::find($cart_item_id)->value('quantity') <= CartItem::find($cart_item_id)->product->quantity;
            if($check_quantity && $check_command)
            {
                $item=Command::create([
                    'user_id' => Auth::user()->id,
                    'product_id'=>$product_id,
                    'quantity'=>$quantity,
                    'status'=>false,
                ]);
                $item->save();
                CartItem::find($cart_item_id)->delete();
                CartController::updateCart();
            }
            else if($check_quantity)
            {
                $command_quantity=Command::where('user_id', Auth::user()->id)
                ->where('product_id',$product_id)
                ->where('status',false)
                ->value('quantity');

                Command::where('user_id', Auth::user()->id)
                ->where('product_id',$product_id)
                ->where('status',false)
                ->update(['quantity'=>$command_quantity+$quantity]);
            }
            else if($check_command)
            {
                if(CartItem::find($cart_item_id)->product->quantity > 0)
                {
                    $item=Command::create([
                        'user_id' => Auth::user()->id,
                        'product_id'=>$product_id,
                        'quantity'=> CartItem::find($cart_item_id)->product->quantity,
                        'status'=>false,
                    ]);
                    $item->save();
                    CartItem::find($cart_item_id)->delete();
                    CartController::updateCart();
                }
                else
                {
                    CartItem::find($cart_item_id)->delete();
                    CartController::updateCart();
                }
            }
            else
            {
                $command_quantity=Command::where('user_id', Auth::user()->id)
                ->where('product_id',$product_id)
                ->where('status',false)
                ->value('quantity');

                Command::where('user_id', Auth::user()->id)
                ->where('product_id',$product_id)
                ->where('status',false)
                ->update(['quantity'=>$command_quantity+CartItem::find($cart_item_id)->product->quantity]);

                CartItem::find($cart_item_id)->delete();
                CartController::updateCart();
            }

            return redirect('/payment');
        }
        else{
            abort(401);
        }
    }

    public function add_all()
    {
        if(Auth::check())
        {
            $cart_id=Cart::where('user_id',Auth::user()->id)->value('id');
            $cart_items=Cart::find($cart_id)->cart_items;
            foreach ($cart_items as $item) {
                $check_command=Command::where('user_id', Auth::user()->id)
                ->where('product_id',$item->product_id)
                ->where('status',false)
                ->count()==0;
                $check_quantity=$item->quantity <= $item->product->quantity;
                if($check_quantity && $check_command)
                {
                    $new_command=Command::create([
                        'user_id' => Auth::user()->id,
                        'product_id'=>$item->product_id,
                        'quantity'=>$item->quantity,
                        'status'=>false,
                    ]);
                    $new_command->save();
                }
                else if($check_quantity)
                {
                    $command_quantity=Command::where('user_id', Auth::user()->id)
                    ->where('product_id',$item->product_id)
                    ->where('status',false)
                    ->value('quantity');

                    Command::where('user_id', Auth::user()->id)
                    ->where('product_id',$item->product_id)
                    ->where('status',false)
                    ->update(['quantity'=>$command_quantity+$item->quantity]);
                }
                else if($check_command)
                {
                    if($item->product->quantity)
                    {
                        $item=Command::create([
                            'user_id' => Auth::user()->id,
                            'product_id'=>$item->product_id,
                            'quantity'=> $item->product->quantity,
                            'status'=>false,
                        ]);
                        $item->save();
                    }
                }
                else
                {
                    $command_quantity=Command::where('user_id', Auth::user()->id)
                    ->where('product_id',$item->product_id)
                    ->where('status',false)
                    ->value('quantity');

                    Command::where('user_id', Auth::user()->id)
                    ->where('product_id',$item->product_id)
                    ->where('status',false)
                    ->update(['quantity'=>$command_quantity+$item->product->quantity]);
                }
            }
            Cart::find($cart_id)->cart_items()->truncate();
            CartController::updateCart();
            return redirect('/payment');
        }
        else{
            abort(401);
        }
    }

    public function buy_now(Request $request, $product_id)
    {
        if(Auth::check())
        {
            $product_quantity=Product::where('id',$product_id)->value('quantity');
            $quantity=$request->input('quantity');
            if($product_quantity>=$quantity)
            {
                $item=Command::create([
                    'user_id' => Auth::user()->id,
                    'product_id'=>$product_id,
                    'quantity'=>$quantity,
                    'status'=>false,
                ]);
                $item->save();
                return redirect('/payment');
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

    public function delete($command_id)
    {
        if(Auth::check())
        {
            Command::where('id',$command_id)->delete();
            return back();
        }
        else{
            abort(401);
        }
    }

    public static function buy(){
        if(Auth::check())
        {
            $commands=Command::where('user_id',Auth::user()->id)
            ->where('status',false)->get();
            foreach ($commands as $command)
            {
                $product=Product::where('id',$command->product_id)->first();
                if($product->quantity >= $command->quantity){
                    Product::where('id',$command->product_id)
                    ->update(['quantity'=>$product->quantity-$command->quantity]);
                }
                else{
                    Command::where('user_id', Auth::user()->id)
                    ->where('product_id', $product->id)
                    ->where('status',false)
                    ->delete();
                    throw new Exception(__('payment.quantity_exception'));
                }
            }
            Command::where('user_id',Auth::user()->id)
            ->where('status',false)
            ->update([
                'status'=>true,
                'paid_at'=>Carbon::now("GMT+1")
            ]);
        }
        else
        {
            abort(401);
        }
    }

    public static function totalPrice()
    {
        if(Auth::check())
        {
            $items=Command::where('user_id',Auth::user()->id)
            ->where('status',false)
            ->get();
            $total=0;
            foreach($items as $item)
            {
                if(isset($item->product->promotion))
                {
                    $price=$item->product->price-($item->product->promotion->promo/100)*$item->product->price;
                    $total = $total + $price * $item->quantity;
                }
                else
                {
                    $total = $total + $item->product->price * $item->quantity;
                }
            }
            return $total;
        }
        else
        {
            abort(401);
            return 0;
        }
    }
}
