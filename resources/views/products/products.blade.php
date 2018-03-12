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
            <input type="hidden" name="id" value="{{ $product->id}}" >
            <div class="productTitle">{{ $product->title }}</div>
            <div class="productDescription">{{ $product->description }}</div>
            <div class="productPrice">$ {{ $product->price }}</div>
            {{--<div><img src="/image?image={{ $product->image }}" /></div>--}}
            <a href="/product/{{ $product->id }}" class="edit">{{ __('messages.edit') }}</a>
            <a href="/products/delete/{{ $product->id }}" class="delete">{{ __('messages.delete') }}</a>
        </div>
    </div>
@endforeach


<div class="linkToCart">
    <a href="{{ route('product') }}">{{ __('messages.add') }}</a>
    <a href="{{ route('logout') }}" class="logout">{{ __('messages.logout') }}</a>
</div>

</body>
</html>