<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function add(Request $request, $product_id)
    {
        if(Auth::check())
        {
            $rating=$request->rating;
            $comment=$request->comment;
            $check=Review::where('user_id',Auth::user()->id)
            ->where('product_id',$product_id)
            ->count() == 0;
            if($check)
            {
                $item=Review::create([
                    'user_id' => Auth::user()->id,
                    'product_id'=>$product_id,
                    'rating'=>$rating,
                    'comment'=>$comment,
                ]);
                $item->save();
            }
            return back();
        }
        else
        {
            abort(401);
        }
    }

    public function edit(Request $request, $review_id)
    {
        if(Auth::check())
        {
            $rating=$request->rating;
            $comment=$request->comment;
            $check=Review::where('id',$review_id)->first()->user_id==Auth::user()->id;
            if($check)
            {
                Review::where('id',$review_id)->update([
                    'rating'=>$rating,
                    'comment'=>$comment,
                ]);
            }
            else
            {
                abort(401);
            }
            return back();
        }
        else
        {
            abort(401);
        }
    }

    public function delete($review_id)
    {
        if(Auth::check())
        {
            $review=Review::where('id',$review_id)->first();
            if($review->user_id==Auth::user()->id)
            {
                Review::where('id',$review_id)->delete();
            }
            return back();
        }
        else
        {
            abort(401);
        }
    }
}
