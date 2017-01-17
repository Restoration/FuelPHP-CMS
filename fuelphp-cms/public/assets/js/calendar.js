
$(document).ready(function() {
	//グローバルオブジェクト定義
	var G = new Object();

    // Documentの読み込みが完了するまで待機し、カレンダーを初期化します。
    $('#calendar').fullCalendar({
	    lang: 'ja',
        // ヘッダーのタイトルとボタン
        header: {
            // title, prev, next, prevYear, nextYear, today
            left: 'prev,next today',
            center: 'title',
            right: 'month agendaWeek agendaDay'
        },
        // jQuery UI theme
        theme: false,
        // 最初の曜日
        firstDay: 1, // 1:月曜日
        // 土曜、日曜を表示
        weekends: true,
        // 週モード (fixed, liquid, variable)
        weekMode: 'fixed',
        // 週数を表示
        weekNumbers: false,
        // 高さ(px)
        //height: 700,
        // コンテンツの高さ(px)
        //contentHeight: 600,
        // カレンダーの縦横比(比率が大きくなると高さが縮む)
        //aspectRatio: 1.35,
        // ビュー表示イベント
        viewDisplay: function(view) {
            //alert('ビュー表示イベント ' + view.title);
        },
        // ウィンドウリサイズイベント
        windowResize: function(view) {
            //alert('ウィンドウリサイズイベント');
        },
        // 日付クリックイベント
        dayClick: function () {
            //alert('日付クリックイベント');
        },
        // 初期表示ビュー
        defaultView: 'month',
        // 終日スロットを表示
        allDaySlot: true,
        // 終日スロットのタイトル
        allDayText: '終日',
        // スロットの時間の書式
        axisFormat: 'H(:mm)',
        // スロットの分
        slotMinutes: 15,
        // 選択する時間間隔
        snapMinutes: 15,
        // TODO よくわからない
        //defaultEventMinutes: 120,
        // スクロール開始時間
        firstHour: 9,
        // 最小時間
        minTime: 6,
        // 最大時間
        maxTime: 20,
        // 表示する年
        //year: 2012,
        // 表示する月
        //month: 12,
        // 表示する日
        //day: 31,
        // 時間の書式
        timeFormat: 'H(:mm)',
        // 列の書式
        columnFormat: {
            month: 'D',
            week: "D",
            day: "D(ddd)"
        },
        // タイトルの書式
        titleFormat: {
            month: 'YYYY年M月',
            week: "YYYY年M月D日",
            day: "YYYY年M月D日",
        },
        // ボタン文字列
        buttonText: {
            prev:     '<',
            next:     '>',
            prevYear: '<<',
            nextYear: '>>',
            today:    '今日',
            month:    '月',
            week:     '週',
            day:      '日'
        },
        // 月名称
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        // 月略称
        monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        // 曜日名称
        dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
        // 曜日略称
        dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
        // 選択可
        selectable: true,
        // 選択時にプレースホルダーを描画
        selectHelper: true,
        // 自動選択解除
        unselectAuto: true,
        // 自動選択解除対象外の要素
        unselectCancel: '',
		selectable: true,
		selectHelper: true,
		select: function(start, end) {
			G.start = start;
			G.end = end;
			G.type = 'add';
			showModal();
			/*
			var title = prompt('Event Title:');
			var eventData;
			if (title) {
				eventData = {
					title: title,
					start: start,
					end: end
				};
				$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
			}
			$('#calendar').fullCalendar('unselect');
			*/
		},
		editable: true,
		eventResize: function(event, delta, revertFunc) {
			if (!confirm(event.title + " を" + event.end.format() + 'に変更しますがよろしいですか？')) {
            	revertFunc();
        	}
    	},
		eventClick: function(calEvent, jsEvent, view) {
			G.start = calEvent.start;
			G.end = calEvent.end;
			G.event = calEvent;
			G.type = 'edit';
			showModal();
    	},
    });
    // 動的にオプションを変更する
    //$('#calendar').fullCalendar('option', 'height', 700);

    // カレンダーをレンダリング。表示切替時などに使用
    //$('#calendar').fullCalendar('render');

    // カレンダーを破棄（イベントハンドラや内部データも破棄する）
    //$('#calendar').fullCalendar('destroy')

	//モーダルウィンドウ作成
	jQuery.fn.center = function () {
		this.css("position","absolute");
		this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
		this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
		return this;
	};

	var showModal = function(){
		$('#modalWindow').center();
		$('#modalWindow').show();
		$('#overLay').show();
		if(G.type == 'add'){
			$('#addBtn').css('display','inline-block');
			$('#editBtn').css('display','none');
			$('#eventDelete').click(function(){
				$('#eventPrompt').val('');
			});
		} else {
			$('#addBtn').css('display','none');
			$('#editBtn').css('display','inline-block');
			$('#eventPrompt').val(G.event.title);
			$('#eventDelete').click(function(){
				G.type = 'delete';
			});
		}
	}

	var hideModal = function(){
		$('#eventPrompt').val('');
		$('#modalWindow').hide();
		$('#overLay').hide();

		for(var key in G){
			delete G[key];
		}
	}
	var eventAction = function(){
		if($('#eventPrompt').val() == ''){
			$('#calendar').fullCalendar('unselect');
			hideModal();
		}
		switch(G.type){
			case 'add':
				var eventData;
				eventData = {
					title: $('#eventPrompt').val(),
					start: G.start,
					end: G.end
				};
				$('#calendar').fullCalendar('renderEvent', eventData, true);
			break;
			case 'edit':
				G.event.title = $('#eventPrompt').val();
				$('#calendar').fullCalendar('updateEvent', G.event);
			break;
			case 'delete':
				if(confirm('イベントを削除しますがよろしいですか？')){
					$('#calendar').fullCalendar("removeEvents", G.event._id);
				}
			break;
		}
		$('#calendar').fullCalendar('unselect');
		hideModal();
	}
	//イベントの紐付け
	$(document).on('click','#eventAdd,#eventEdit,#eventDelete',eventAction);

});
