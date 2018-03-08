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
                            '<a href="/' +  product.id  + '" class="add"><?= __("Add") ?></a>',
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
                                comments: $('.cart .comnents').val()
                            },
                            success: function (response) {
                                if (response.success) {
                                    window.location.hash = '#cart';
                                    alert('The email was sent');
                                } else {
                                    alert('Error');
                                }
                            }
                        })
                    }
                });
            });

            $('.container').on("click", ".add",  function (e) {
                e.preventDefault();
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/index', {
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: $(".id").val()
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

            $('.cart').on("click", ".remove",  function (e) {
                e.preventDefault();
                $.ajax('/token', {
                    success: function (response) {
                        $.ajax('/cart', {
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                _token: response,
                                id: $(".id").val()
                            },
                            success: function (response) {
                                if (response) {
                                    $('.cart .container').html(renderCart(response));
                                    alert('Succes');
//                                    $('.add').removeClass('add').addClass('remove').text('Remove');
                                } else {
                                    alert('Error');
                                }
                            }
                        })
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
    <input type="text" class="name" placeholder="{{ __('messages.name') }}"><span></span><br />
    <input type="text" class="contactDetails" placeholder="{{ __('messages.contact') }}"><span></span><br />
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
</body>
</html>