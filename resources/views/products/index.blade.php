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
            {{--<div><img src="/image?image={{ $product->image }}" /></div>--}}
            <a href="?id={{ $product->id }}" class="add">{{ __('messages.add') }}</a>
        </div>
    </div>
@endforeach


<div class="linkToCart">
    <a href="{{ route('cart') }}">{{ __('messages.goToCart') }}</a>
</div>

</body>
</html>