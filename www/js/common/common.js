// JavaScript Document

$(function() {
	initPage();
});

function initPage(){
	$('#menu_publish_center_title').mouseenter(function(){
		$(this).addClass('active');
		$('#submenu_publish_center').show();
	});
	$('#menu_publish_center').mouseleave(function(){
		$('#menu_publish_center_title').removeClass('active');
		$('#submenu_publish_center').hide();
	});
	$('#menu_user_in_title').mouseenter(function(){
		$(this).addClass('active');
		$('#submenu_user_in').show();
	});
	$('#menu_user_in').mouseleave(function(){
		$('#menu_user_in_title').removeClass('active');
		$('#submenu_user_in').hide();
	});
	
	$('#header1 img').click(function(){
		goPage('home');
	});
	$('#menu_editour').click(function(){
		goPage('editour');
	});
	$('#submenu_publish_center li,#submenu_user_in li').click(function(){
		goPage($(this).text());
	});
	$('.footer_icon img').mouseover(function(){
		$(this).addClass('over');
	}).mouseout(function(){
		$(this).removeClass('over');
	});
	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		monthNames: ['1월','2월','3월','4월','5월','6월',
		'7월','8월','9월','10월','11월','12월'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월',
		'7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		duration:200,
		showAnim:'show',
		showMonthAfterYear: true,
		yearSuffix: '년'};
	$.datepicker.setDefaults($.datepicker.regional['ko']);
	$('.popup').imagesLoaded(settingPopupLayer);
	$(window).scroll(settingPopupLayer);
	$(window).resize(settingPopupLayer);
	$('.thumb_image_list>div:nth-child(3n)').css('margin-right','0');
}

function settingPopupLayer(){
	docHeight=$(document).height();
	$('.popup, .popup_bg').height(docHeight);
	$('.popup_content, .popup_content2').each(function(i){
		popupContentPostion={
			'top' : ($(window).height()-$(this).height())/2+$(window).scrollTop(),
			'left' : ($(window).width()-$(this).width())/2,
		}
		$(this).css('top',popupContentPostion.top);
		$(this).css('left',popupContentPostion.left);
	});
			
}

function initInfoBox(){
	$('.info_box').mouseenter(function(){
		$(this).find('.cover').show();
	}).mouseleave(function(){
		$(this).find('.cover').hide();
	});
	
	$('.info_box').click(function(){
		location.href="#";
	});
}

function goPage(param){
	alert(param);
	if(param='home') location.href='#';
	else if(param='editour') location.href='#';
	else if(param='발행센터') location.href='#';
	else if(param='알립니다') location.href='#';
	
	else if(param='찜목록') location.href='#';
	else if(param='구매목록') location.href='#';
	else if(param='내포인트') location.href='#';
	else if(param='회원정보수정') location.href='#';
	else if(param='로그아웃') location.href='#';
}