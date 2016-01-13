// Увеличеник картинки при клике
$(document).on('click', '.zoomable', function(){
	var $this = $(this);
	if ($this.attr('width')) {
		$this
			.attr('_width', $this.attr('width'))
			//.css('position', 'absolute')
			.removeAttr('width');
		$this.stop().animate({
			'width': '100%'
		});

	} else {
		$this.stop().animate({
			'width': $this.attr('_width')
		});
		$this
			.attr('width', $this.attr('_width'))
			//.css('position', 'static')
			.removeAttr('_width');
	}
	
	$('#book-modal').modal({'show': false});
});

// форма поиска в таблице (pjax)
$(document).on('submit', '#searchForm', function (event) {
	$.pjax.submit(event, '#w0', {
		"push":true,
		"replace":false,
		"timeout":1000,
		"scrollTo":false
	});
	
	return false;
});

// Просмотр содержимого книги
$('#w0').on('click', 'a[aria-label="View"]', function(){
	$.ajax({
		url: $(this).attr('href'),
		type: 'get',
		success: function(data) {
			$('#book-modal-content').html(data);
			$('#book-modal').modal();
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('#book-modal-content').html(textStatus);
			$('#book-modal').modal();
		}
	});
	return false;
});


var popupwindow = function(url, title, w, h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
};

// Форма редактирования книги
$('#w0').on('click', 'a[aria-label="Update"]', function(){
	var new_win = popupwindow($(this).attr('href'), 'Редактирование', 600, 600);
	new_win.onunload = function(){
		$('#searchForm').trigger('submit');
	};
	return false;
});

// Форма добавления книги
$('a[aria-label="Create"]').on('click', function(){
	var new_win = popupwindow($(this).attr('ajax_href'), 'Добавление', 600, 600);
	new_win.onunload = function(){
		$('#searchForm').trigger('submit');
	};
	return false;
});

// ajax-удаление записи
$('#w0').on('click', 'a[aria-label="Delete"]', function(){
	if ( ! window.confirm('Хотите удалить?'))
		return false;
	$.ajax({
		url: $(this).attr('href'),
		type: 'post',
		success: function(data) {
			$('#searchForm').trigger('submit');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('#book-modal-content').html(textStatus);
			$('#book-modal').modal();
		}
	});
	
	return false;
});


// Поиск при изменении любого из параметров формы
$(document).on('change', '.form-control', function(e){
	$(this).parents('form').trigger('submit');
});