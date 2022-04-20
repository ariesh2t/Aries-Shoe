$(document).ready(function () {
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imgSrc').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgUpload").change(function() {
        readURL(this);
    });

    CKEDITOR.replace('description' , {
    // //đường dẫn đến file ckfinder.html của ckfinder
    // filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
    // //đường dẫn đến file connector.php của ckfinder
    // filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
    });

    $('.sidebar-toggle').click(function () {
        $('.pull-left.text-white').toggleClass('hide');
        $('.user-panel.row').toggleClass('mgl-2');
    })

    $('#btn-pswd').click(function () {
        $('#form-pswd').toggleClass('show');
    })

    $('li.dropdown').click(function () {
        const aria_expanded = $('a.dropdown-toggle').attr('aria-expanded');
        if (aria_expanded == null || aria_expanded === 'false') {
            $('a.dropdown-toggle').attr('aria-expanded', 'true');
        } else {
            $('a.dropdown-toggle').attr('aria-expanded', 'false');
        }
        $('li.dropdown').toggleClass('show');
        $('ul.dropdown-menu').toggleClass('show');
    })

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
});