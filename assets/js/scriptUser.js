$(document).ready(function () {
    $('.eyes').click(function () {
        let type = $('#password').attr('type') === 'password' ? 'text' : 'password';
            $('#password').attr('type', type);
    })
    $('.re-eyes').click(function () {
        let type = $('#password_confirm').attr('type') === 'password' ? 'text' : 'password';
        $('#password_confirm').attr('type', type);
    })
    $('.new-eyes').click(function () {
        let type = $('#new-pswd').attr('type') === 'password' ? 'text' : 'password';
        $('#new-pswd').attr('type', type);
    })
    $('#btn-pswd').click(function () {
        $('#form-pswd').toggleClass('show');
    })

    $('#model_search').click(function (){
        $('.modal-backdrop.fade.show').addClass('hidden-lg');
    })

    $('.src').click(function () {
        let src = $(this).attr('src');
        $('#src').attr('src', src);
    })

    $('input.input-qty').each(function() {
        let d;
        const $this = $(this),
            qty = $this.parent().find('.is-form'),
            min = Number($this.attr('min')),
            max = Number($this.attr('max'));
        if (min === 0) {
            d = 0;
        } else d = min
        $(qty).on('click', function() {
            if ($(this).hasClass('minus')) {
                if (d > min) d += -1
            } else if ($(this).hasClass('plus')) {
                const x = Number($this.val()) + 1;
                if (x <= max) d += 1
            }
            $this.attr('value', d).val(d)
        })
    })

    let size = '';
    let color = '';
    let name = $('#name').val();
    let category_id = $('#category_id').val();
    let price = $('#price').html();
    $('.sizes').click(function () {
        size = $(this).attr('value')
        if (color !== '') {
            $.ajax({
                url: 'index.php?controller=product&action=getPrice', //url gọi tới file PHP
                method: 'GET', //phương thức truyền dữ liệu lên PHP
                data: {  // obj chứa data sẽ gửi từ ajax lên PHP
                    name: name,
                    category_id: category_id,
                    size: size,
                    color: color
                },
                success: function (data) {
                    if (data === '') {
                        $('#price').html(price)
                    } else {
                        $('#price').html(data + "<sup>đ</sup>")
                    }
                }
            });
        }
    })
    $('.colors').click(function () {
        color = $(this).attr('value')
        if (size !== '') {
            $.ajax({
                url: 'index.php?controller=product&action=getPrice', //url gọi tới file PHP
                method: 'GET', //phương thức truyền dữ liệu lên PHP
                data: {  // obj chứa data sẽ gửi từ ajax lên PHP
                    name: name,
                    category_id: category_id,
                    size: size,
                    color: color
                },
                success: function (data) {
                    if (data === '') {
                        $('#price').html(price)
                    } else {
                        $('#price').html(data + "<sup>đ</sup>")
                    }
                }
            });
        }
    })

    $('#add-to-cart').click(function() {
        let name = $('#name').val();
        let category_id = $('#category_id').val();

        // Gọi ajax lên PHP để nhờ PHP thêm sp hiện tại vào giỏ hàng
        if (color === '' || size === '') {
            $('.ajax-error').html('Chọn size và màu phù hợp với bạn!')
            $('.ajax-error').addClass('ajax-error-active');

            //Set thời gian timeout để auto ẩn message trên sau 3 giây
            setTimeout(function() {
                $('.ajax-error').removeClass('ajax-error-active');
            }, 3000);
        } else {
            let quantity = Math.ceil($('#quantity').val())
            $.ajax({
                // Url MVC xử lý ajax
                url: 'index.php?controller=cart&action=add',
                // Phương thức truyền dữ liệu
                method: 'GET',
                // Dữ liệu gửi kèm từ ajax lên PHP
                data: {
                    name: name,
                    category_id: category_id,
                    size: size,
                    color: color,
                    quantity: quantity
                },
                // Nơi nhận dữ liệu trả về từ PHP
                success: function(data) {
                    if (data.search("đăng nhập") > 0) {
                        $('#btn-login').click();
                    } else if (data.search("kho")>0) {
                        $('.ajax-error').html(data)
                        $('.ajax-error').addClass('ajax-error-active');

                        //Set thời gian timeout để auto ẩn message trên sau 3 giây
                        setTimeout(function() {
                            $('.ajax-error').removeClass('ajax-error-active');
                        }, 3000);
                    } else {
                        $('.ajax-message').html('Thêm sản phẩm vào giỏ hàng thành công!')
                        $('.ajax-message').addClass('ajax-message-active');

                        //Set thời gian timeout để auto ẩn message trên sau 3 giây
                        setTimeout(function() {
                            $('.ajax-message').removeClass('ajax-message-active');
                        }, 3000);
                        // Xử lý update số lượng sp của icon giỏ hàng
                        let amount = Number($('.cart-quantity p').text());
                        amount += quantity
                        $('.cart-quantity p').text(amount);
                    }
                }
            });
        }

    })

    $('.content-product-a').click(function (e) {
        let product_id = $(this).attr('data-id')
        let quantity = $(this).attr('data-quantity')
        $.ajax({
            // Url MVC xử lý ajax
            url: 'index.php?controller=cart&action=delete',
            // Phương thức truyền dữ liệu
            method: 'POST',
            // Dữ liệu gửi kèm từ ajax lên PHP
            data: {
                product_id: product_id,
                quantity: quantity
            },
            // Nơi nhận dữ liệu trả về từ PHP
            success: function(data) {
                let amount = Number($('.cart-quantity p').text());
                amount -= Number(quantity);
                $('.cart-quantity p').text(amount);
            }
        });
    })

    $('.btn-update').click(function () {
        let id = $(this).attr('data-id');
        let quantity = $('#quantity_'+id).val();
        console.log(quantity)
        $.ajax({
            // Url MVC xử lý ajax
            url: 'index.php?controller=cart&action=update',
            // Phương thức truyền dữ liệu
            method: 'POST',
            // Dữ liệu gửi kèm từ ajax lên PHP
            data: {
                product_id: id,
                quantity: quantity
            },
            // Nơi nhận dữ liệu trả về từ PHP
            success: function(data) {
                if (data.search("kho")>0) {
                    $('.ajax-error').html(data)
                    $('.ajax-error').addClass('ajax-error-active');

                    //Set thời gian timeout để auto ẩn message trên sau 3 giây
                    setTimeout(function() {
                        $('.ajax-error').removeClass('ajax-error-active');
                    }, 3000);
                } else {
                    let current_qtt = Number(data);
                    let amount = Number($('.cart-quantity p').text());
                    amount = amount - current_qtt + Number(quantity);
                    $('.cart-quantity p').text(amount);
                    location.reload()
                }
            }
        });
    })
})