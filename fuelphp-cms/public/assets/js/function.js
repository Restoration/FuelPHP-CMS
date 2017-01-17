$(document).ready(function(){
	var G = {};
	G.baseUrl = $('#base_url').val();

	/*==================================================
	Common
	==================================================*/
	jQuery.fn.center = function () {
		this.css("position","absolute");
		this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
		this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
		return this;
	};

	String.prototype.capitalize = function(){
	    return this.charAt(0).toUpperCase() + this.slice(1);
	}


    // Loadingイメージ表示関数
    function appendLoading(){
        if($("#loading").size() == 0){
            $("body").append("<div id='loading'></div>");
            $("body").append("<div id='loadingOverLay'></div>");
            $("#loading").center();
        }
    }
    // Loadingイメージ削除関数
    function removeLoading(){
        $("#loading").remove();
        $("#loadingOverLay").remove();
    }
    //タイトルのアコーディオンメニュー
	jQuery.fn.titleAcMenu = function () {
		$(this).on("click", function() {
			$(this).children().removeClass();
			if($(this).next().css('display') == 'block'){
				$(this).children().addClass('icon-chevron-down');
			} else {
				$(this).children().addClass('icon-chevron-up');
			}
			$(this).next().slideToggle();
		});
	};


	/*==================================================
	Image
	==================================================*/
	var initImage = function(){
		$(document).on('click','#addImage',function(){
			var inputFileLength = $('.inputFile').size();
			var inputFile = '<p><input type="file" name="add[image_'+inputFileLength+']" class="form-control inputFile" />';
			inputFile += '<span class="delateImageBtn btn btn-primary">削除</span></p>';
			if($('.inputFile').size() == 1){
				$('.inputFile:eq(0)').after('<span class="delateImageBtn btn btn-primary">削除</span>');
			}
			$(this).before(inputFile);
		});
		$(document).on('click','.delateImageBtn',function(){
			$(this).prev().remove();
			$(this).remove();
			if($('.delateImageBtn').size() == 1){
				$('.delateImageBtn').remove();
			}
		});

		$(document).on('click','#deleteImage',function(){
			var checkboxVal = '';
			var hiddenVal = '';
			$('.checkBoxImage').each(function(){
				if($(this).prop('checked')){
					checkBoxVal = $(this).attr('data-fileid');
					hiddenVal = $(this).next().val();
					var inputHidden = '<input type="hidden" name="delete[image_delflg][]" value="'+checkBoxVal+'" />';
					inputHidden += '<input type="hidden" name="delete[image_name][]" value="'+hiddenVal+'" />';
					$('#hiddenArea').append(inputHidden);
				}
			});
			// return false;
		});

		$('#drag-area').bind('drop', function(e){
			e.preventDefault();
			var files = e.originalEvent.dataTransfer.files;
			uploadFiles(files);
		}).bind('dragenter', function(){
			return false;
		}).bind('dragover', function(){
			$('#drag-area').css({
				'background': 'rgba(128, 128, 128, 0.8)',
			});
			return false;
		}).bind('dragleave', function(){
			$('#drag-area').css({
				'background': 'rgba(128, 128, 128, 0.6)',
			});
		});

		$(document).on('click','#changeInput',function(){
			if($(this).attr('data-flg') == 1){
				$("#input-drag").show();
				$("#input-file").hide();
				$(this).attr('data-flg',0);
				initDragAndDrop();
			} else {
				$("#input-file").show();
				$("#input-drag").hide();
				$(this).attr('data-flg',1);
			}
			return false;
		});

	}

	function uploadFiles(files) {
		var result;
		var data =  new Object;
		var fd = new FormData();
		var filesLength = files.length;
		for (var i = 0; i < filesLength; i++) {
			fd.append("files[]", files[i]);
		}
		data.fd = fd;
		data.dd_flg = 1;
		$.ajax({
			url: G.baseUrl+'image/add/',
			type: 'POST',
			data: fd,
			processData: false,
			contentType: false,
			success: function(res) {
				return_array = $.parseJSON(res);
				resultFlg = return_array.flg;
				result = return_array.data;

				if(resultFlg !==  1){
					//現在表示されているrow-fluid padd-bottomを削除
					$('div.image_area').unwrap();
					var resultString = 'success';
					var alertMessage = '画像の追加が完了しました。';
					var imageEl = '';
					for(i=0; i < result.length; i++){
						imageEl += '<div class="span3 image_area">';
						imageEl += '<a href="'+result[i]['file_saved_abs_to']+result[i]['file_saved_as']+'" data-lightbox="image-set" class="thumbnail">';
						imageEl += '<img src="'+result[i]['file_saved_abs_to']+result[i]['file_saved_as']+'" alt="" style="width: 260px; height: 180px;" />';
						imageEl += '</a>';
						imageEl += '<i class="icon-remove" data-file-id="'+result[i]['file_id']+'" data-file-saved-path="'+result[i]['file_saved_to']+result[i]['file_saved_as']+'"></i>';
						imageEl += '</div>';
						var imageCount = $('#imageCount span').text();
						imageCount = parseInt(imageCount,10) + i;
						$('#imageCount span').text(imageCount);
					}
					$('.image_area').eq(0).before(imageEl);
					//再びrow-fluid padd-bottomで囲む
					do {
						$("div#image_wrap").children("div.image_area:lt(4)").wrapAll('<div class="row-fluid padd-bottom"></div>')
					} while($("div#image_wrap").children("div.image_area").length);
				} else {
					var resultString = 'error';
					var alertMessage = '画像の追加に失敗しました。';
				}
				var alertEl = '';
				alertEl += '<div class="row-fluid">';
				alertEl += '<div class="alert alert-'+resultString+'">';
				alertEl += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				alertEl += '<h4>'+resultString.capitalize()+'</h4>'+'<p>'+alertMessage+'</p>';
				if(resultFlg ==  1){
					for(i=0; i < result.length; i++){
						alertEl += '<p>'+result[i]+'</p>';
					}
				}
				alertEl += '</div>';
				alertEl += '</div>';
				$('#message-alert').html('');
				$('#message-alert').append(alertEl);
			}
		});
	}
	function initDragAndDrop(){
		var descriptionHeight = $('#drag-area .description').height();
		var descriptionWidth = $('#drag-area .description').width();

		$('#drag-area .description').css({
			'marginLeft': '-'+(descriptionWidth / 2)+'px',
			'marginTop': '-'+(descriptionHeight / 2)+'px',
		});
	}
	//画像削除
	$(document).on('click','.image_area i.icon-remove',function(){
		//$(this).parents('.image_area').remove();
		var data = new Object();
		data.delete = 'image_delete';
		data.path = $(this).attr('data-file-saved-path');
		data.image_id = $(this).attr('data-file-id');
		console.log(data);
		$.ajax({
			url: G.baseUrl+'image/delete/',
			type: 'POST',
			data: data,
			success: function(res) {
				var result = $.parseJSON(res);
				console.log(result);
				location.reload();
/*
				console.log(res);
				result = $.parseJSON(res);
				console.log(result);
				if(result !==  1){
					var resultString = 'success';
					var alertMessage = '画像の削除が完了しました。';
					$('div.image_area').unwrap();
					do {
						$("div#image_wrap").children("div.image_area:lt(4)").wrapAll('<div class="row-fluid padd-bottom"></div>')
					} while($("div#image_wrap").children("div.image_area").length);
				} else {
					var resultString = 'error';
					var alertMessage = '画像の削除に失敗しました。';
				}
				var alertEl = '';
				alertEl += '<div class="row-fluid">';
				alertEl += '<div class="alert alert-'+resultString+'">';
				alertEl += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				alertEl += '<h4>'+resultString.capitalize()+'</h4>'+alertMessage;
				alertEl += '</div>';
				alertEl += '</div>';
				$('#message-alert').html('');
				$('#message-alert').append(alertEl);
*/
			}
		});
	});

	/*==================================================
	Post
	==================================================*/
	var initMain = function(){
		$("#clEditor").cleditor();
		$(".cleditorToolbar .cleditorButton").each(function(){
			var dataTitle = $(this).attr('title');
			if(dataTitle == 'Insert Image'){
				$(this).off('click');
				$(this).addClass('trigger').attr('data-modal',"imageWindow");
			}
		});
		$(document).modalWindow();
		$('.datepicker').datepicker({
		    format: 'yyyy/mm/dd',
		    language: 'ja'
		});
		//clEditorクリック時にDatePicker非表示
		$($(".cleditorMain iframe")[0].contentWindow.document).bind('click',function(){
			$('div.datepicker').remove();
		});
		//インクリメンタルサーチ
		$(document).on('keyup','#form_post_search',ajaxPostSearch);


		$('#category_wrap_title').titleAcMenu();
		$('#tag_wrap_title').titleAcMenu();

	}
	var imageArray = new Array();
    jQuery.fn.modalWindow = function(options){
        options = $.extend({
			"openTrigger": ".trigger",
			"closeTrigger": ".closeBtn",
			"modalContent": ".modalContent",
			"overLay" : "overLay",
			"width" : 500,
			"height": 500,
			"feadSpeed" : 500,
        },options);

		$(options.modalContent).hide();

		//リサイズ処理
		$(window).resize(function(){
			$(options.modalContent).css({
				top:$(window).height() /2 - currentModal.outerHeight() /2 + $(window).scrollTop(),
                left:($(window).width() /2 - currentModal.outerWidth() /2 + $(window).scrollLeft()),
			});
		});

		//data属性から現在のモーダルウィンドウを取得
		$(options.openTrigger).bind('click',function(){
			if($(options.openTrigger).length > 1){
				//複数のモーダルウィンドウの場合
				//data属性から現在のモーダルウィンドウを取得
				getModal = this.getAttribute('data-modal');
				currentModal = $('.' + getModal);
			} else {
				currentModal = $(options.modalContent);
			}
			openModal();
			$(window).resize();
		});

		//モーダルウィンドウの表示処理
		function openModal(){
			$('body').append('<div id="overLay"></div>');
			currentModal.fadeIn(options.fadeSpeed);
			$(options.modalContent).css({
				width: options.width,
				height: options.height,
			});
			var iframeHeight = $(options.modalContent).height();
			var src = G.baseUrl+'image/image_iframe.php';
			var iframe = '<iframe src="'+src+'" height="'+iframeHeight+'px"></iframe>';
			if($(options.modalContent + ' #imageWindowInner iframe').length == 0){
				$(options.modalContent + ' #imageWindowInner').append(iframe);
				showIframe();
			}
		}


		//iframe関連の処理
		//---------------------------------------------------
		function showIframe(){
			$('iframe').load(function(){
				var pagenation = $('iframe').contents().find('.pagenateArea').html();
				$('.pagenateArea').append(pagenation);
				highLight();
				pagenateAction();
			});
		}
		//ページャー処理
		function pagenateAction(){
			var j = 0
			$('.pagenateArea .pagination span').bind('click',function(){
				var link = $(this).children('a').attr('href');
				if(link === '#'){
					return false;
				}
				var content = link + ' div#imageView';
				$('iframe').contents().find("div#imageView").load(content,function(){
					$('.pagenateArea').html('');
					var pagenation = $('iframe').contents().find('.pagenateArea').html();
					$('.pagenateArea').append(pagenation);
					if(j === 0){
						highLight();
						j++;
					}
					pagenateAction();
				});
				return false;
			});
		}
		//ハイライト処理
		function highLight(){
			$('iframe').contents().find('#imageIframe .image_area img').bind('click',function(){
				if($(this).attr('data-insert') == 0){
					$(this).css({
						'border':'solid 3px red'
					}).attr('data-insert',1);
					var imageId = $(this).attr('data-image-id');
					imageArray[imageId] = $(this).attr('src');
				} else {
					$(this).css({
						'border':'0'
					}).attr('data-insert',0);
					var imageId = $(this).attr('data-image-id');
					imageArray[imageId] = '';
				}
			});
		}

		//挿入ボタン
		closeModal('#insertImageBtn');
		$(document).on('click','#insertImageBtn',function(){
			//ボタンを押下後代入
			for (var i in imageArray) {
				var imgEl = '<div><img src="'+imageArray[i]+'" alt="" style="max-width: 50%; max-width: 50%;"></div>';
				$("#clEditor").next().contents().find('body').append(imgEl);
				imageArray[i] = '';
			}
			$('iframe').contents().find('#imageIframe .image_area img').attr('data-image-id',0).css('border','none');
			return false;
		});

		//終了処理
		closeModal(options.closeTrigger);
		function closeModal(closeObj){
			$(closeObj).on('click',function(){
				$(options.modalContent).fadeOut(options.feadSpeed);
				$('div#' + options.overLay).fadeOut(options.fadeSpeed,function(){
					$(this).remove();
				});
			});
		}
	}
	/*==================================================
	PostList
	==================================================*/
	var initPostList = function(){
		//インクリメンタルサーチ
		$(document).on('keyup','#form_post_search',ajaxPostSearch);
		//カテゴリー検索
		$(document).on('click','#category_search_trigger',ajaxPostCategorySearch);
		//カテゴリー選択時のアクション
		$(document).on('click','#category_search_list li',categoryAppendAction);

		 //Preview画面のみ
		if($('#clEditor').size() > 0){
			$("#clEditor").cleditor();
			$(".cleditorToolbar .cleditorButton").each(function(){
				var dataTitle = $(this).attr('title');
				if(dataTitle == 'Insert Image'){
					$(this).off('click');
					$(this).addClass('trigger').attr('data-modal',"imageWindow");
				}
			});
			$(document).modalWindow();
			$('.datepicker').datepicker({
			    format: 'yyyy/mm/dd',
			    language: 'ja'
			});
			//clEditorクリック時にDatePicker非表示
			$($(".cleditorMain iframe")[0].contentWindow.document).bind('click',function(){
				$('div.datepicker').remove();
			});
		}
		$('#category_wrap_title').titleAcMenu();
		$('#tag_wrap_title').titleAcMenu();

		$('#form_post_tag_search').tagit({
		    removeConfirmation: false,
		});

	}
	//インクリメンタルサーチ
	var ajaxPostSearch = function(e){
		var data = {};
		data.value = $(this).val();
		data.action = 'ajaxPostSearch';
		$.ajax({
			url: G.baseUrl + 'postlist/post_search',
			type: 'POST',
			data: data,
			success: function(data) {
				//console.log(data);
				var pres = $.parseJSON(data);
				//console.log(pres);
				$('#table_post_list tbody').html('');
				var result;
				var postData = pres['post'];
				for(var i=0; i < postData.length; i++){
					result += '<tr>';
					result += '<td>';
					date = postData[i].registerdate.substring(0,postData[i].registerdate.indexOf(" ")).replace(/-/g,"/");//変換
					result += date;
					result += '</td>';
					result += '<td>';
					result += postData[i].post_title;
					result += '</td>';
					result += '<td>';
					result += '<a href="'+G.baseUrl+'postlist/preview?id='+postData[i].post_id+'" class="btn btn-primary">編集</a>';
					result += '</td>';
					result += '</tr>';
				}
				$('#table_post_list tbody').append(result);
				$('#pagination_wrap').html('');
				$('#pagination_wrap').html(pres['pagination']);
			},
			error: function(request, status, error) {
				alert("エラーが発生しました");
			}
		});
		return false;
	}
	//カテゴリー検索
	var ajaxPostCategorySearch = function(e){
		var data = {};
		data.action = 'ajaxPostCategorySearch';
		$.ajax({
			url: G.baseUrl + 'postlist/post_category_search',
			type: 'POST',
			data: data,
			success: function(data) {
				//console.log(data);
				var pres = $.parseJSON(data);
				var result = '';
				var cat_none_result = '';
				var categoryData = pres;
				for(obj in categoryData){
					for(i in categoryData[obj]){
						if(obj == -1){
							cat_none_result += '<li data-category-value="'+obj+'">';
							cat_none_result += '<a tabindex="-1" href="#">';
							cat_none_result += categoryData[obj][i].category_name;
							cat_none_result += '</a>';
							cat_none_result += '</li>';
						}  else {
							result += '<li data-category-value="'+i+'">';
							result += '<a tabindex="-1" href="#">';
							result += categoryData[obj][i].category_name;
							result += '</a>';
							result +=  '<ul class="dropdown-menu">';
							result += '<li>';
							result += '<a tabindex="-2" href="#">';
							result += 'aaaaaaaa';
							result += '</a>';
							result += '</li>';
							result += '</ul>';
							result += '</li>';
						}
					}
				}
				$('#category_search_list').html(cat_none_result + result);
			},
			error: function(request, status, error) {
				alert("エラーが発生しました");
			}
		});
	}

	function categoryAppendAction(){
		var value = $(this).children().text();
		$('#category_search_trigger').text(value);
	}


	/*==================================================
	Tag
	==================================================*/
	var initTag = function(){
		$(document).on('keyup','#form_tag_search',ajaxTagSearch);
	}
	//インクリメンタルサーチ
	var ajaxTagSearch = function(e){
		var data = {};
		data.value = $(this).val();
		data.action = 'ajaxTagSearch';
		$.ajax({
			url: G.baseUrl + 'tag/tag_search',
			type: 'POST',
			data: data,
			success: function(data) {
				//console.log(data);
				var pres = $.parseJSON(data);
				//console.log(pres);
				$('#table_tag_list tbody').html('');
				var result;
				var tagData = pres['tag'];
				for(var i=0; i < tagData.length; i++){
					result += '<tr>';
					result += '<td>';
					result += tagData[i].tag_name;
					result += '</td>';
					result += '<td>';
					result += tagData[i].tag_description;
					result += '</td>';
					result += '<td>';
					result += tagData[i].tag_slug;
					result += '</td>';
					result += '<td>';
					result += '<a href="'+G.baseUrl+'tag/preview?id='+tagData[i].tag_id+'" class="btn btn-primary">編集</a>';
					result += '</td>';
					result += '</tr>';
				}
				$('#table_tag_list tbody').append(result);
				$('#pagination_wrap').html('');
				$('#pagination_wrap').html(pres['pagination']);
			},
			error: function(request, status, error) {
				alert("エラーが発生しました");
			}
		});
		return false;
	}
	/*==================================================
	Category
	==================================================*/
	var initCategory = function(){
		$(document).on('keyup','#form_category_search',ajaxCategorySearch);
	}
	//インクリメンタルサーチ
	var ajaxCategorySearch = function(e){
		var data = {};
		data.value = $(this).val();
		data.action = 'ajaxCategorySearch';
		$.ajax({
			url: G.baseUrl + 'category/category_search',
			type: 'POST',
			data: data,
			success: function(data) {
				var pres = $.parseJSON(data);
				$('#table_category_list tbody').html('');
				var result;
				var categoryData = pres['category'];
				//console.log(categoryData);
				for(obj in categoryData){
					if(obj == -1){
						continue;
					}
					//オブジェクトであった場合
					if(categoryData[obj] instanceof Array == false){
						for(i in categoryData[obj]){
							result += '<tr>';
							result += '<td>';
							result += categoryData[obj][i].category_name;
							result += '</td>';
							result += '<td>';
							result += categoryData[obj][i].category_description;
							result += '</td>';
							result += '<td>';
							result += categoryData[obj][i].category_slug;
							result += '</td>';
							result += '<td>';
							result += '<a href="'+G.baseUrl+'category/preview?id='+obj+'" class="btn btn-primary">編集</a>';
							result += '</td>';
							result += '</tr>';
						}
					} else {
						//配列であった場合
						for(var i=0; i < categoryData[obj].length; i++){
							result += '<tr>';
							result += '<td>';
							result += categoryData[obj][i].category_name;
							result += '</td>';
							result += '<td>';
							result += categoryData[obj][i].category_description;
							result += '</td>';
							result += '<td>';
							result += categoryData[obj][i].category_slug;
							result += '</td>';
							result += '<td>';
							result += '<a href="'+G.baseUrl+'category/preview?id='+obj+'" class="btn btn-primary">編集</a>';
							result += '</td>';
							result += '</tr>';
						}
					}
				}
				//console.log(result);
				$('#table_category_list tbody').append(result);
				$('#pagination_wrap').html('');
				$('#pagination_wrap').html(pres['pagination']);
			},
			error: function(request, status, error) {
				alert("エラーが発生しました");
			}
		});
		return false;
	}
	/*==================================================
	PostList
	==================================================*/
	var initCalendar = function(){
	}
	/*==================================================
	実行処理
	==================================================*/
	var dataController = $('body').attr('data-controller');
	switch(dataController){
		//Main
		case 'Controller_Main':
			initMain();
		break;
		//PostList index
		case 'Controller_Postlist':
			initPostList();
		break;
		//PostList Preview
		case 'Controller_Postlist':
			initPostList();
		break;
		//Image
		case 'Controller_Image':
			initImage();
		break;
		//Tag
		case 'Controller_Tag':
			initTag();
		break;
		//Category
		case 'Controller_Category':
			initCategory();
		break;
		//Calendar
		case 'Controller_Calendar':
			initCalendar();
		break;


	}





});
