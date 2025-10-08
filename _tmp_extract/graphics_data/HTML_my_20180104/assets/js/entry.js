function tabDetector() {
  $("body").find(".tab-menu").each(function(){
    var targetLink = $(this).children(".tab-menu__box").hasClass("is-unlink");
    if(!targetLink) {
      var tabCurrent = $(this).children(".tab-menu__box").children(".is-selected").children(".tab-menu__btn").attr("href");
      $(tabCurrent).css({display:"block"});
    } else {
      $(this).siblings(".tab-contents").css({display:"block"});
    }
  });
};

function tabOpener(e) {
  var targetLink = $(this).parent(".tab-menu__list").parent(".tab-menu__box").hasClass("is-unlink");
  if(!targetLink) {
    var a = $(this).parent();
    a.siblings().removeClass("is-selected");
    b = a.attr("class");
    a.addClass("is-selected");
    c = a.children(".tab-menu__btn").attr("href");
    a.parent(".tab-menu__box").parent(".tab-menu").siblings(".tab-contents").css({display:"none"});
    $(c).css({display:"block"});
    e.preventDefault();
  }
};

function fl_banner(oj) {
	if(!oj) return false;
	var bn = $(oj);
	var p = $(window).scrollTop();
	var $body =$("body");
	if($body.hasClass("have-top")){
		if(p > 650){
			bn.slideDown();
		}
		else if(p < 1600){
			bn.slideUp();
		}
	}else{//main 상단 slide height
		if(p > 0){
			bn.slideDown();
		}
		else{
			bn.slideUp();
		}
	}
}

$(document).ready(function(){

  var $window = $(window),
    $document = $(document),
    $html = $('html'),
    $body = $('body');

	$(".js-my-open").on("click", function(e){
		e.preventDefault();
		if($(this).hasClass("is-focus")){
			$(this).removeClass("is-focus");
			$(".header-my-open").slideUp();
		}else{
			$(this).addClass("is-focus");
			$(".header-my-open").slideDown();
		}
		return false;
	})

	$(".gnb-nav-more").on("click", function(e){
		e.preventDefault();
		if($(this).hasClass("is-close")){
			$(this).removeClass("is-close");
			$(this).addClass("is-open");
			$(".gnb-nav-extend").slideDown();
		}else{
			$(this).addClass("is-close");
			$(this).removeClass("is-open");
			$(".gnb-nav-extend").slideUp();
		}
		return false;
	})
	$(".nav-icon").on("click", function(e){
		e.preventDefault();
		if($(this).hasClass("is-close")){
			$(this).removeClass("is-close");
			$(this).addClass("is-open");
			$(this).children(".icon-line").addClass("is-active");
			$(".gnb-nav-extend").slideDown();
		}else{
			$(this).addClass("is-close");
			$(this).removeClass("is-open");
			$(this).children(".icon-line").removeClass("is-active");
			$(".gnb-nav-extend").slideUp();
		}
		return false;
	});
	$(".header-search-btn").on("click", function(e){
		e.preventDefault();
		if($(this).hasClass("not-active")){
			$(this).removeClass("not-active");
			$(this).addClass("is-active");
			$(".header-search-layer").slideDown();
		}else{
			$(this).addClass("not-active");
			$(this).removeClass("is-active");
			$(".header-search-layer").slideUp();
		}
		return false;
	});

  // selecbox : s
  $('.selectbox--type1 select').niceSelect();
  // selectbox : e

  // tab-menu : s
  tabDetector();
  $(".tab-menu .tab-menu__btn").bind("click", tabOpener);
  // tab-menu : e

  // placeholder : s
  var input = document.createElement("input");
  if(('placeholder' in input)==false) {
    $('[placeholder]').focus(function() {
      var i = $(this);
      if(i.val() == i.attr('placeholder')) {
        i.val('').removeClass('placeholder');
        if(i.hasClass('password')) {
          i.removeClass('password');
          this.type='password';
        }
      }
    }).blur(function() {
      var i = $(this);
      if(i.val() == '' || i.val() == i.attr('placeholder')) {
        if(this.type=='password') {
          i.addClass('password');
          this.type='text';
        }
        i.addClass('placeholder').val(i.attr('placeholder'));
      }
    }).blur().parents('form').submit(function() {
      $(this).find('[placeholder]').each(function() {
        var i = $(this);
        if(i.val() == i.attr('placeholder'))
          i.val('');
      })
    });
  }
  // placeholder : e

  // modal : s
  $('.js-modal__btn-open').on('click', function(e) {
    e.preventDefault();
    var _$this = $(this),
      _$modal = $(_$this.attr('href'));
    if(_$modal.length > 0) _$modal.addClass('is-expanded').attr('tabindex', '0').focus();
  });
  $('.js-modal__btn-close').on('click', function(e) {
    e.preventDefault();
    var _$this = $(this),
        _$modal = _$this.closest('.js-modal'),
        _$btn = $(_$this.attr('href'));
    _$modal.removeClass('is-expanded').attr('tabindex', '-1');
    if(_$btn.length > 0) _$btn.focus();
  });
  // modal : e

  // window scroll : s
  $(window).on('scroll', function(){
	fl_banner('.floating-banner');
  });
  // window scroll : e

  // window resize : s
  $(window).on('resize', function(){

  });
  // window resize : e

});