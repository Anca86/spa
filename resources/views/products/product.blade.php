<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.products') }}</title>
    {{--<link rel="stylesheet" type="text/css" href="css/style.css">--}}
</head>
<body>

<h1>Add product</h1>

<form method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="id" value="<?= $product->id ?? '' ?>" >

    <input type="text" name="title" placeholder="{{ __('messages.title') }}" value="<?= $product->title ?? '' ?>" ><br />

    <input type="text" name="description" placeholder="{{ __('messages.description') }}" value="<?= $product->description ?? '' ?>" ><br />

    <input type="number" name="price" placeholder="{{ __('messages.price') }}" value="<?= $product->price ?? '' ?>"><br />

    <input type="file" name="file" id="file"><br /><br />

    <input type="submit" name="save" value="{{ isset($product->id) ? __('messages.update'): __('messages.save') }}">
</form>

@foreach ($errors->all() as $error)
    {{ $error }}<br />
@endforeach

<a href="{{ route('products') }}">{{ __('messages.products') }}</a>
</body>
</html>