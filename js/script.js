$(document).ready(function() {		
	$('.js-add-task').on('click', function(){
        form = $(this).parent('form');
        data = form.serializeArray();
        action = '/main/addTask';
        $.ajax({
            type: 'POST',
            url: action,
            dataType: 'json',
            data: data,
            success: function(r) 
            {
                if (r.status == 'error') {
                    alert(r.message);
                    $.each(r.fields, function(i, e) {
                        $('#field_'+e).addClass('is-error');
                    });
                }
                if (r.status == 'ok') {
                    alert(r.message);
                    form.trigger("reset");
                    location.reload();
                }
            }
        })
        return false;
    })
})

$(document).ready(function() {		
	$('.js-order option').on('click', function(){
        order = $(this).val();
        sort = $('.js-sort').attr('data-sort');
        page = $('.js-pagination li.active a').attr('data-page');
        tbody = $('.js-table tbody');
        $.ajax({
            type: 'POST',
            url: '/main/sortTask',
            dataType: 'json',
            data: {
                order: order,
                sort: sort,
                page: page,
            },
            success: function(r) 
            {
                if (r.status == 'ok') {
                    tbody.html(r.content)
                }
            }
        })        
        return false;
    })
})

$(document).ready(function() {		
	$('.js-sort').on('click', function(){
        if ($(this).hasClass('up')) {
            $(this).removeClass('up')
            $(this).addClass('down')
            $(this).attr('data-sort', 'desc')
        } else {
            $(this).removeClass('down')
            $(this).addClass('up')
            $(this).attr('data-sort', 'asc')
        }
        order = $('.js-order').val();
        sort = $(this).attr('data-sort');
        page = $('.js-pagination li.active a').attr('data-page');
        tbody = $('.js-table tbody');

        $.ajax({
            type: 'POST',
            url: '/main/sortTask',
            dataType: 'json',
            data: {
                order: order,
                sort: sort,
                page: page,
            },
            success: function(r) 
            {
                if (r.status == 'ok') {
                    tbody.html(r.content)
                }
            }
        })        
        return false;
    })
})

$(document).ready(function() {		
	$('.js-pagination li a').on('click', function(){
        order = $('.js-order').val();
        sort = $('.js-sort').attr('data-sort');
        page = $(this).attr('data-page');
        tbody = $('.js-table tbody');
        $('.js-pagination li').removeClass('active');
        $(this).parent('li').addClass('active');
        
        $.ajax({
            type: 'POST',
            url: '/main/sortTask',
            dataType: 'json',
            data: {
                order: order,
                sort: sort,
                page: page,
            },
            success: function(r) 
            {
                if (r.status == 'ok') {
                    tbody.html(r.content)
                }
            }
        })        
        return false;
    })
})

$(document).ready(function() {		
	$('.js-try-login').on('click', function(){
        form = $(this).parent('form');
        data = form.serializeArray();
        action = '/admin/tryLogin';
        $.ajax({
            type: 'POST',
            url: action,
            dataType: 'json',
            data: data,
            success: function(r) 
            {
                if (r.status == 'error') {
                    alert(r.message);
                    $.each(r.fields, function(i, e) {
                        $('#field_'+e).addClass('is-error');
                    });
                }
                if (r.status == 'ok') {
                    location.href = '/';
                }
            }
        })
        return false;
    })
})
	
$(document).on('click', '.js-task-done', function(){
    $this = $(this);
    id = $(this).parents('tr').attr('data-task');
    action = '/main/taskDone';
    $.ajax({
        type: 'POST',
        url: action,
        dataType: 'json',
        data: {
            id: id,
        },
        success: function(r) 
        {
            if (r.status == 'errorAuth') {
                location.href = '/admin';
            }
            if (r.status == 'ok') {
                alert('Задача отмечена выполненной!');
                location.href = '/';
            }
        }
    })
    return false;
})
	
$(document).on('click', '.js-task-edit', function(){
    $this = $(this);
    id = $(this).parents('tr').attr('data-task');
    name = $(this).parents('tr').find('input[name=name]').val();
    email = $(this).parents('tr').find('input[name=email]').val();
    task = $(this).parents('tr').find('textarea[name=task]').val();
    
    action = '/main/taskEdit';
    $.ajax({
        type: 'POST',
        url: action,
        dataType: 'json',
        data: {
            id: id,
            name: name,
            email: email,
            task: task,
        },
        success: function(r) 
        {
            if (r.status == 'error') {
                alert(r.message);
                $.each(r.fields, function(i, e) {
                    $('#field_'+e).addClass('is-error');
                });
            }
            if (r.status == 'errorAuth') {
                location.href = '/admin';
            }
            if (r.status == 'ok') {
                alert('Задача отредактирована!');
                location.href = '/';
            }
        }
    })
    return false;
})