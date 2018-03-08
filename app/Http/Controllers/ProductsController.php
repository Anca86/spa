<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Product;
use Config;
use Session;

class ProductsController extends Controller
{
    public function image(Request $request)
    {
        if (($image = $request->input('image')) && Storage::disk('local')->has('public/images/' . $image)) {
            return Storage::disk('local')->get('public/images/' . $image);
        }
    }

    public function token(Request $request)
    {
        return csrf_token();
    }

    public function spa(Request $request)
    {
        return view('spa');
    }

    public function index(Request $request)
    {
        if (($id = $request->input('id')) && !in_array($id, session('cart', []))) {
            $request->session()->push('cart', $id);
        }

        if(count(session('cart', []))) {
            $products = Product::whereNotIn('id', session('cart'))->get();
        } else {
            $products = Product::all();
        }

        if ($request->ajax()) {
            return $products;
        } else {
            return view('products.index', compact("products"));
        }
    }

    public function cart(Request $request)
    {
        if (($id = $request->input('id')) && in_array($id, session('cart', []))) {
//            dd($id);
            $cart = session('cart');
            unset($cart[array_search($id, $cart)]);
            session(['cart' => $cart]);
        }

        if (count(session('cart', []))) {
            $products = Product::whereIn('id', session('cart'))->get();
        } else {
            $products = [];
        }

        if ($request->ajax()) {
            return $products;
        } else {
            return view('products.cart', compact("products"));
        }
    }

    public function create()
    {
        return view('products.product');
    }

    public function show(Product $product)
    {
        $products = Product::all();
        return view('products.products', compact('products'));
    }

    public function edit(Request $request, $id)
    {
        $product = Product::find($id);
        return view('products.product', compact('product'));
    }

    public function save(Request $request)
    {
        $id = $request->input('id');
        $title = request('title');
        $description = request('description');
        $price = request('price');
        $image = $request->file('file');
        $title = Helpers::clean_user_input($title);
        $description = Helpers::clean_user_input($description);
        $price = Helpers::clean_user_input($price);

        if ($id) {
            $product = Product::find($id);
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
            ]);

            if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
                if (getimagesize($_FILES["file"]["tmp_name"])) {
                    $product->image = request()->file('file')->hashName();
                    Storage::disk('local')->put('public/images', $image);
                }
            }
        } else {
            $product = new Product();
            $this->validate(request(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'file' => 'required'
            ]);

            Product::create([
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'image' => request()->file('file')->hashName()
            ]);


            Storage::disk('local')->put('public/images', $image);
            return view('products.product', compact('product'));
        }

        $product->save();

        return view('products.product', compact('product'));
    }


    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return view('products.products', compact('products'));
    }

    public function sendOrder(Request $request)
    {
        $nameErr = $contactDetailsErr = $succes = $sendErr = "";
        $protocol = "http";
        $products = Product::whereIn('id', session('cart'))->get();

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $protocol = "https";
        }

        if(isset($_POST["checkout"])) {
            $this->validate($request, [
                'name' => 'required|string',
                'contactDetails' => 'required|email',
                'comments' => 'required'
            ]);
            $order = "";
            $totalsum = 0;
            $cartProducts = [];
            $cartProducts = Product::whereIn('id', session('cart'))->get();
            foreach ($cartProducts as $key => $value) {
                $order .= "<tr>";
                $order .= "<td>" . $value->title . "</td>";
                $order .= "<td>" . $value->description . "</td>";
                $order .= "<td>" . $value->price . "</td>";
                $order .= "<td><img src=\"" . $protocol . "://".$_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0,
                        strrpos($_SERVER['SCRIPT_NAME'], "/")+1) ."public/images/" . $value->image . "\" /><td>";
                $order .= "</tr>";
                $totalsum += $value->price;
            }
            $name = request('name');
            $contactDetails = request('contactDetails');
            $comments = request('comments');

            $name = Helpers::clean_user_input($name);
            $contactDetails = Helpers::clean_user_input($contactDetails);
            $comments = Helpers::clean_user_input($comments);

            $subject =  __('messages.subject');

            $headers = "From: " . env('_EMAIL') . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $message = "<html><body>";
            $message .= '<table border="1">';
            $message .= $order;
            $message .= "<tr><td colspan=\"2\">" . __('messages.total') . "</td><td colspan=\"2\">" . $totalsum. "</td></tr>";
            $message .= "</table>";
            $message .= "</html></body>";

            if (!mb_strlen($name)) {
                $nameErr = __('messages.nameErr');
            }

            if (!mb_strlen($contactDetails)) {
                $contactDetailsErr = __('messages.contactDetailsErr');
            }

            if (!$nameErr && !$contactDetailsErr) {
                if (mail(Config::get('constants.email'), $subject, $message, $headers)) {
                    $succes = __('messages.succes');
                } else {
                    $sendErr = __('messages.sendErr');
                }
            }
        }

        if ($request->ajax()) {
            return $products;
        } else {
            return view('products.cart', compact('products', 'nameErr', 'contactDetailsErr', 'succes', 'sendErr'));
        }

    }

}

