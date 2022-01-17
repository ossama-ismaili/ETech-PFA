<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Command;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products=Product::orderBy('created_at','desc')->paginate(6);
        $categories=Category::all();
        return view('pages.products')->with('products',$products)->with('categories',$categories);
    }

    public function show($id)
    {
        $product=Product::find($id);
        if(isset($product))
        {
            if(Auth::check())
            {
                $command_count=Command::where('user_id',Auth::user()->id)
                ->where('product_id', $product->id)
                ->where('status', true)
                ->count();
                $review_count=Review::where('user_id',Auth::user()->id)
                ->where('product_id',$product->id)
                ->count();
                return view('pages.product')
                ->with('product',$product)
                ->with('command_count',$command_count)
                ->with('review_count',$review_count);
            }
            else
            {
                return view('pages.product')->with('product',$product);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function category($id)
    {
        $products=Product::where("category_id",$id)->orderBy('created_at','desc')->paginate(6);
        $categories=Category::all();
        if(count($products) > 0)
        {
            return view('pages.products')->with('products',$products)->with('categories',$categories);
        }
        else{
            abort(404);
        }
    }

    public function search($keyword)
    {
        $products=Product::where('title', 'like', '%'.$keyword.'%')->paginate(6);
        $categories=Category::all();
        return view('pages.products')->with('products',$products)->with('categories',$categories);
    }
}


