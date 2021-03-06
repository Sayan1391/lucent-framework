function SysAjaxSave(url, redirect) {
    var form = $("form");
    var formData = form.serialize();

    $.ajax({
        type: 'post',
        url: url,
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            $(window).block({
                message : '<div class="progress"><div class="progress-bar progress-bar-striped active" ' +
                'role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">' +
                '<span class="sr-only"></span></div></div><span>Loading</span>',
                css: {
                    'border' : 'none',
                    'padding' : '10px'
                }
            });
        },
        success: function(result) {
            if ('err' == result.status) {
                $.notify(result.text, "error");
            }

            if ('ok' == result.status) {
                $.notify(result.text, "success");
                setTimeout(function() {
                    window.location.href = redirect;
                }, 1000);
            }
        },
        complete: function() {
            $(window).unblock();
        }
    });
}

function SysAjaxDelete(url, redirect, modelName, id) {

    $.ajax({
        type: 'post',
        url: url,
        data: {modelName: modelName, id: id},
        dataType: 'json',
        beforeSend: function() {
            $(window).block({
                message : '<div class="progress"><div class="progress-bar progress-bar-striped active" ' +
                'role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">' +
                '<span class="sr-only"></span></div></div><span>Loading</span>',
                css: {
                    'border' : 'none',
                    'padding' : '10px'
                }
            });
        },
        success: function(result) {
            if ('err' == result.status) {
                $.notify(result.text, "error");
            }

            if ('ok' == result.status) {
                $.notify(result.text, "success");
                setTimeout(function() {
                    window.location.href = redirect;
                }, 1000);
            }
        },
        complete: function() {
            $(window).unblock();
        }
    });
}