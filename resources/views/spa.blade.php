<html>
<head>

    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- Custom JS script -->
    <script type="text/javascript">
        $(document).ready(function () {

            function renderList(products) {
                html = [
                    '<h1 id="title"><?= __('Products') ?></h1>'
                ].join('');

                $.each(products, function (key, product) {
                    html += [
                        '<div class="product">',
                            '<div class="productDetails">',
                                '<div class="productTitle">' + product.title + '</div>',
                                '<div class="productDescription">' + product.description + '</div>',
                                '<div class="productPrice">' + product.price + '</div>',
                            '</div>',
                            '<input type="hidden" class="id" value="' + product.id + '">',
                            '<a href="/' +  product.id  + '" class="add" value="' + product.id + '"><?= __("Add") ?></a>',
                        '</div>'
                    ].join('');
                });

                return html;
            }

            function renderCart(products) {
                html = [
                    '<h1 id="title"><?= __('Cart Products') ?></h1>'
                ].join('');

                $.each(products, function (key, product) {
                    html += [
                        '<div class="product">',
                        '<div class="productDetails">',
                        '<div class="productTitle">' + product.title + '</div>',
                        '<div class="productDescription">' + product.description + '</div>',
                        '<div class="productPrice">' + product.price + '</div>',
                        '</div>',
                        '<input type="hidden" class="id" value="' + product.id + '">',
                        '<a href="/' +  product.id  + '" class="remove"><?= __("remove") ?></a>',
                        '</div>'
                    ].join('');
                });

                return html;
            }

            function renderProducts(products) {
                html = [
                    '<h1 id="title"><?= __('Products List') ?></h1>'
                ].join('');

                $.each(products, function (key, product) {
                    html += [
                        '<div class="product">',
                        '<div class="productDetails">',
                        '<div class="productTitle">' + product.title + '</div>',
                        '<div class="productDescription">' + product.description + '</div>',
                        '<div class="productPrice">' + product.price + '</div>',
                        '</div>',
                        '<input type="hidden" class="id" value="' + product.id + '">',
                        '<a href="/' +  product.id  + '" class="edit"><?= __("Edit") ?></a>',
                        '<a href="/' +  product.id  + '" class="delete"><?= __("Delete") ?></a>',
                        '</div>'
                    ].join('');
                });

                return html;
            }

            function renderProduct(product) {
                return $product;
            }



            $('.login .submit').click(function (e) {
                e.preventDefault();
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/login', {
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                _token: response,
                                username: $('.login .username').val(),
                                password: $('.login .password').val()
                            },
                            success: function (response) {
                                if (response.success) {
                                    window.location.hash = '#products';
                                } else {
                                    alert(response.message);
                                }
                            }
                        })
                    }
                });
            });

            $('.cart .checkout').click(function (e) {
                e.preventDefault();
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/cart', {
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                _token: response,
                                name: $('.cart .name').val(),
                                contactDetails: $('.cart .contactDetails').val(),
                                comments: $('.cart .comments').val()
                            },
                            success: function (response) {
                                if (response.nameErr) {
                                    alert("Message");
                                }
                            },

                            error: function (response) {
                                    alert('Name not valid');
                            }
                        })
                    }
                });
            });
            // test

            $('.container').on("click", ".add",  function (e) {
                e.preventDefault();
                var inputId =  $(this).closest('.product').find('.id').val();
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/index', {
                            method: 'get',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: inputId
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
                var inputId =  $(this).closest('.product').find('.id').val();
//                alert(inputId);
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/cart', {
                            method: 'get',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: inputId
                            },
                            success: function (response) {
                                if (response) {
                                    $('.cart .container').html(renderCart(response));
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
                var inputId =  $(this).closest('.product').find('.id').val();
//                alert(inputId);
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/products/delete/' + inputId, {
                            method: 'get',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: inputId
                            },
                            success: function (response) {
                                if (response) {
                                    $('.cart .container').html(renderProduct(response));
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
                var inputId =  $(this).closest('.product').find('.id').val();
                window.location.hash = '#product';
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/product/' + inputId, {
                            method: 'post',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: inputId
                            },
                            success: function (response) {
                                if (response) {
//                                    $('.cart .container').html(renderProducts(response));
                                    window.location.hash = '#product';
                                } else {
                                    alert('Error');
                                }
                            }
                        })
                    }
                });
            });

//            $('.product .save').click(function (e) {
//                e.preventDefault();
//                var title = $('.product .title').val();
//                var description = $('.product .description').val();
//                var price = $('.product .price').val();
//                var image = new FormData($('this')[0]);
////                var formdata = new FormData($('.file')[0]);
//                $.ajax('/token', {
//                    success: function (response) {
//                        $.ajax('/product', {
//                            method: 'POST',
//                            dataType: 'json',
//                            data: {
//                                _token: response,
//                                title:title,
//                                description: description,
//                                price: price,
//                                image: image
//                            },
//                            success: function (response) {
//                                if (response.success) {
//                                    window.location.hash = '#product';
//                                } else {
//                                    alert(response.message);
//                                }
//                            }
//                        })
//                    }
//                });
//            });

            $("form[name='upload']").submit(function(e) {
                var formData = new FormData();
//                var title = $('.product .title').val();
//                var description = $('.product .description').val();
//                var price = $('.product .price').val();
//                var image = $('.product .file');
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/product', {
                            method: "POST",
                            datatype: 'json',
                            data: {
                                _token: response,
                                formData: formData
                            },
                            async: false,
                            success: function () {
                                alert("Data submited!")
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });

                        e.preventDefault();
                    }
                });
            });

            /**
             * URL hash change handler
             */
            window.onhashchange = function () {
                // First hide all the pages
                $('.page').hide();

                switch(window.location.hash) {
                    case '#cart':
                        // Show the cart page
                        $('.cart').show();
//                        $('.add').removeClass('add').addClass('remove').text('Remove');
                        // Load the cart products from the server
                        $.ajax('/cart', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the cart list
                                $('.cart .container').html(renderCart(response));
                            }
                        });
                        break;
                    case '#login':
                        // Show the cart page
                        $('.login').show();
                        break;
                    case '#products':
                        // Show the cart page
                        $('.products').show();
                        // Load the cart products from the server
                            $.ajax('/products', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the cart list
                                $('.products .container').html(renderProducts(response));
                            }
                        });
                        break;
                    case '#product':
                        // Show the cart page
                        $('.product').show();
//                         Load the cart products from the server
                        $.ajax('/product', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the cart list
                                $('.product .container').html(renderProduct(response));
                            }
                        });
                        break;
                    default:
                        // If all else fails, always default to index
                        // Show the index page
                        $('.index').show();
                        // Load the index products from the server
                        $.ajax('/', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the index list
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
<!-- The index page -->
<div class="page index">
    <!-- The index element where the products list is rendered -->
    <div class="container"></div>
    <!-- A link to go to the cart by changing the hash -->
    <a href="#cart" class="button">Go to cart</a>
</div>

<!-- The cart page -->
<div class="page cart">
    <!-- The cart element where the products list is rendered -->
    <div class="container"></div>
    <input type="text" class="name" placeholder="{{ __('messages.name') }}" required="required"><span></span><br />
    <input type="text" class="contactDetails" placeholder="{{ __('messages.contact') }}" required="required"><span></span><br />
    <input type="text" class="comments" placeholder="{{ __('messages.comments') }}"><br /><br />
    <input type="submit" class="checkout" value="Checkout">
    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
</div>

<div class="page login">
    <input type="text" class="username" autocomplete="off"><br />
    <input type="password" class="password" autocomplete="off"><br />
    <input type="submit" class="submit" value="{{ __('Login')}}">
</div>

<div class="page products">
    <div class="container"></div>
    <!-- A link to go to the index by changing the hash -->
    <a href="#product" class="button">Add a product</a>
    <a href="#logout" class="button">Logout</a>
</div>

<div class="page product">
    <form name="upload">
        <input type="hidden" class="id" value="<?= $product->id ?? '' ?>" >
        <input type="text" class="title" placeholder="{{ __('messages.title') }}" ><br />
        <input type="text" class="description" placeholder="{{ __('messages.description') }}"><br />
        <input type="number" class="price" placeholder="{{ __('messages.price') }}" ><br />
        <input type="file" class="file" id="file"><br /><br />
        <input type="submit" class="save" value="{{ isset($product->id) ? __('messages.update'): __('messages.save') }}">
        <!-- A link to go to the index by changing the hash -->
        <a href="#products" class="button">Products</a>
    </form>
</div>
</body>
</html>