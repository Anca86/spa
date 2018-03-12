<html>
<head>

    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom JS script -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content');
            }
        });

        $(document).ready(function () {

            function renderList(products, type) {
                html = [
                    '<h1 id="title"><?= __('Products') ?></h1>'
                ].join('');

                $.each(products, function (key, product) {
                    var buttons;
                    switch (window.location.hash) {
                        case '#cart':
                            buttons = [
                                '<a href="#" class="remove" data-id="' + product.id + '"><?= __("Remove") ?></a>'
                            ].join('');
                            break;
                        case '#products':
                            buttons = [
                                '<a href="#" class="edit" data-id="' + product.id + '"><?= __("Edit") ?></a>',
                                '<a href="#" class="delete" data-id="' + product.id + '"><?= __("Delete") ?></a>'
                            ].join('');
                            break;
                        default:
                            buttons = [
                                '<a href="#" class="add" data-id="' + product.id + '"><?= __("Add") ?></a>'
                            ].join('');
                            break;
                    }
                    html += [
                        '<div class="product">',
                            '<div class="productDetails">',
                                '<div class="productTitle">' + product.title + '</div>',
                                '<div class="productDescription">' + product.description + '</div>',
                                '<div class="productPrice">' + product.price + '</div>',
                                buttons,
                            '</div>',
                        '</div>'
                    ].join('');
                });

                return html;
            }

            function renderProduct(product) {
                $('.id').val(product.id);
                $('.title').val(product.title);
                $('.description').val(product.description);
                $('.price').val(product.price);
            }

            $('.addProduct').click(function () {
                $('.id').val("");
                $('.title').val("");
                $('.description').val("");
                $('.price').val("");
            });

//            function isSession() {
//                $.ajax('/token', {
//                    success: function (response) {
//                        $.ajax('/spa', {
//                        method: 'post',
//                        dataType: 'json',
//                        data: response,
//                        success: function (response) {
//                            if(response) {
//                                return true;
//                            }
//                        },
//                        error: function(response) {
//                                return false;
//                        }
//                    })
//                    }
//                });
//            }
//
//            var i = isSession();


            window.isAdmin = false;
            $('.login .submit').click(function (e) {
                e.preventDefault();
                        $.ajax('/login', {
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                username: $('.login .username').val(),
                                password: $('.login .password').val()
                            },
                            success: function (response) {
                                if (response.success) {
                                    window.location.hash = '#products';
                                    window.isAdmin = true;
                                }
                            }
                        })
            });


            $('.products .logout').click(function (e) {
                e.preventDefault();
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/logout', {
                            method: 'get',
                            dataType: 'json',
                            data: response,
                            success: function (response) {
                                if (response.logout) {
                                    window.location.hash = '#login';
                                } else {
                                    alert('Error');
                                }
                            }
                        })
                    }
                });
            });

            $('.cart .checkout').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/token',
                    success: function (response) {
                        $.ajax({
                            url: '/cart',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                _token: response,
                                name: $('.cart .name').val(),
                                contactDetails: $('.cart .contactDetails').val(),
                                comments: $('.cart .comments').val()
                            },
                            success: function (response) {
                                if (response) {
                                   alert('Message sent');
                                } else (
                                    alert(response.message)
                                )
                            }
                        })
                    }
                });
            });

            $('.container').on("click", ".add",  function (e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/index', {
                            method: 'get',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: id
                            },
                            success: function (response) {
                                if (response) {
                                    $('.index .container').html(renderList(response));
                                } else {
                                    alert('Error');
                                }
                            }
                        })
                    }
                });
            });

            $('.cart').on("click", ".remove",  function (e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/cart', {
                            method: 'get',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: id
                            },
                            success: function (response) {
                                if (response) {
                                    $('.cart .container').html(renderList(response));
                                } else {
                                    alert('Error');
                                }
                            }
                        })
                    }
                });
            });

            $('.products').on("click", ".delete",  function (e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/products/delete/' + id, {
                            method: 'get',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: id
                            },
                            success: function (response) {
                                if (response) {
                                    $('.cart .container').html(renderList(response));
                                } else {
                                    alert('Error');
                                }
                            }
                        })
                    }
                });
            });

            $('.page').on("click", ".edit",  function (e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                window.location.hash = '#product';
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/product/' + id, {
                            method: 'get',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: id
                            },
                            success: function (response) {
                                if (response) {
                                    $('.page .product').html(renderProduct(response));
                                } else {
                                    alert('Error');
                                }
                            }
                        })
                    }
                });
            });

            $("form[name='upload']").submit(function(e) {
                var form = this;
                e.preventDefault();
                $.ajax({
                    url: "/token",
                    success: function (response) {
                        var data = new FormData(form);
                        data.append("_token", response);
                        $.ajax({
                            url: "/product",
                            data: data,
                            dataType: 'json',
                            upload: 'multipart/form-data',
                            contentType: false,
                            processData: false,
                            method: "POST",
                            success: function () {
                                alert("Data submited!");
                                $('.product .error').html('');
                            },
                            error: function (response) {
                                    $('.product .error').html('');
                                    $.each(response.responseJSON.errors, function (key, value) {
                                        $('.product .error.' + key).html(value.join(' '));
                                    });
                            },
                            cache: false
                        });
                    }
                });
            });

            window.onhashchange = function () {
                $('.page').hide();

                switch(window.location.hash) {
                    case '#cart':
                        $('.cart').show();
                        $.ajax('/cart', {
                            dataType: 'json',
                            success: function (response) {
                                $('.cart .container').html(renderList(response));
                            }
                        });
                        break;
                    case '#login':
                        $('.login').show();
                        break;
                    case '#logout':
                        $('.login').show();
                        break;
                    case '#products':
                        if(isAdmin !== true) {
                            window.location.hash = '#login';
                        } else {
                            $('.products').show();
                            $.ajax('/products', {
                                dataType: 'json',
                                success: function (response) {
                                    $('.products .container').html(renderList(response));
                                }
                            });
                        }
                        break;
                    case '#product':
                        if(isAdmin !== true) {
                            window.location.hash = '#login';
                        } else {
                            $('.product').show();
                            $.ajax('/product', {
                                dataType: 'json',
                                success: function (response) {
                                    $('.product .container').html(renderProduct(response));
                                }
                            });
                        }
                        break;
                    default:
                        $('.index').show();
                        $.ajax('/', {
                            dataType: 'json',
                            success: function (response) {
                                $('.index .container').html(renderList(response));
                            }
                        });
                        break;
                }
            }

            window.onhashchange();
        });
    </script>
</head>
<body>

<div class="page index">
    <div class="container"></div>
    <a href="#cart" class="button">Go to cart</a>
</div>

<div class="page cart">
    <div class="container"></div>
    <input type="text" name="name" class="name" placeholder="{{ __('messages.name') }}" required="required"><span class="error name"></span><br />
    <input type="text" name="contactDetails" class="contactDetails" placeholder="{{ __('messages.contact') }}" required="required"><span class="error contactDetails"></span><br />
    <input type="text" name="comments" class="comments" placeholder="{{ __('messages.comments') }}"><span class="error comments"></span><br /><br />
    <input type="submit" class="checkout" value="Checkout">
    <a href="#" class="button">{{ __('Got to index') }}</a>
</div>

<div class="page login">
    {{--<input type="hidden" name="_token" type="hidden" value="{{csrf_token()}}">--}}
    <input type="text" name="username" class="username" autocomplete="off"><br />
    <input type="password" name="password" class="password" autocomplete="off"><br />
    <input type="submit" class="submit" value="{{ __('Login')}}">
</div>

<div class="page products">
    <div class="container"></div>
    <a href="#product" class="button addProduct">{{ __('Add a product') }}</a>
    <a href="#logout" class="button logout">{{ __('Logout') }}</a>
</div>


<div class="page product">
    <form name="upload" class="upload"  enctype="multipart/form-data">
        <input type="hidden" class="id" name="id">
        <input type="text" name="title" class="title" placeholder="{{ __('messages.title') }}" value=""><span class="error title"></span><br />
        <input type="text" name="description" class="description" placeholder="{{ __('messages.description') }}" value=""><span class="error description"></span><br />
        <input type="number" name="price" class="price" placeholder="{{ __('messages.price') }}" value=""><span class="error price"></span><br />
        <input type="file" name="file" class="file" id="file"><br /><br />
        <input type="submit" class="save" value="{{ __('Save')}}">
        <a href="#products" class="button">{{ __('Products') }}</a>
    </form>
</div>

</body>
</html>