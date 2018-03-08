<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.products') }}</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

@foreach($products as $product)
    <div class="product">
        <div class="productDetails">
            <div class="productTitle">{{ $product->title }}</div>
            <div class="productDescription">{{ $product->description }}</div>
            <div class="productPrice">$ {{ $product->price }}</div>
            {{--<div><img src="{{ \Storage::url("images/{$product->image}") }}" /></div>--}}
            <a href="?id={{ $product->id }}" class="remove">{{ __('messages.remove') }}</a>
        </div>
    </div>
@endforeach


<div class="linkToCart">
    <a href="{{ route('index') }}">{{ __('messages.goToIndex') }}</a>
</div>

<form method="post">
    @csrf
    <input type="text" name="name" placeholder="{{ __('messages.name') }}"><span></span><br />
    <input type="text" name="contactDetails" placeholder="{{ __('messages.contact') }}"><span></span><br />
    <input type="text" name="comments" placeholder="{{ __('messages.comments') }}"><br /><br />
    <input type="submit" name="checkout" value="Checkout">
    {{--<span>{{ $succes }}</span>--}}
    {{--<span>{{ $sendErr }}</span>--}}
</form>

@foreach ($errors->all() as $error)
    {{ $error }}<br />
@endforeach

</body>
</html>