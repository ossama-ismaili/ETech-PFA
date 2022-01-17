<?php

namespace App\Http\Controllers;

use App\Models\Command;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function index()
    {
        if(Auth::check())
        {
            $commands=Command::where('user_id',Auth::user()->id)
            ->where('status',false)
            ->paginate(4);
            $total=CommandController::totalPrice();
            return view('pages.payment')->with('commands',$commands)->with('total',$total);
        }
        else
        {
            abort(401);
        }
    }

    public function makePayment(Request $request)
    {
        if(Auth::check())
        {
            $check_commands=Command::where('user_id',Auth::user()->id)->where('status',false)->count() > 0;
            if($check_commands)
            {
                try
                {
                    $total_price=CommandController::totalPrice();
                    CommandController::buy();

                    Stripe::setApiKey(env('STRIPE_SECRET'));

                    $customer = Customer::create(array(
                        'email' => $request->stripeEmail,
                        'source'  => $request->stripeToken
                    ));

                    Charge::create([
                        'customer' => $customer->id,
                        'amount' => 100 * $total_price,
                        'currency' => 'MAD'
                    ]);

                    Session::flash('success', 'Payment successfully made!');
                    return back();
                }
                catch (Exception $ex)
                {
                    Session::flash('error', $ex->getMessage());
                    return back();
                }
            }
        }
        else
        {
            abort(401);
        }
    }

    public function history(){
        if(Auth::check())
        {
            $commands=Command::where('user_id',Auth::user()->id)->paginate(4);
            return view('pages.history')->with('commands',$commands);
        }
        else{
            abort(401);
        }
    }
}
