<footer id="footer">
    <div class="footer-bar">
        <div class="cnt-inner">
            <div class="footer-cs-center"><img src="/assets/img/icon-tel.png" alt="" />고객센터  1234-5678</div>
            <div class="footer-cs-link">
                <a href="#">로그인</a>
                <a href="#">주문배송</a>
                <a href="#">고객문의</a>
            </div>
        </div>
    </div>
    <div class="footer-info">
        <div class="cnt-inner">
            ㈜ 비콘   |   대표   박북톡<br>
            서울특별시 마포구 월드컵북로 396 누리꿈스퀘어 비즈타워 1103호<br>
            사업자 등록번호 000-00-00000<br>
            통신판매업 신고 제 000 – 서울 00 00-00000호<br>
            개인정보보호 책임 admin@booktalk.co.kr<br>
            <div class="footer-link">
                <a href="#" class="btn btn-link">이용약관</a>
                <a href="#" class="btn btn-link">개인정보처리방침</a>
                <a href="#" class="btn btn-link">청소년보호정책</a>
                <a href="#" class="btn btn-link">사업자 정보 확인</a>
            </div>
        </div>
    </div>

</footer>
<!-- footer -->
<script type="text/javascript" src="/assets/js/lib/masonry.pkgd.min.js"></script>
<script>
    (function() {
        var $gridMasonry = $('.js-grid-masonry');
        $gridMasonry.masonry({
            itemSelector: '.js-grid-masonry__item'
            //columnWidth: 380,
            //gutter: 18
        });
    }());
</script>
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });


    $(function () {
        var rating = 0.8;
        $(".counter").text(rating);
        var changeRating = function (rating) {
            $(this).next().text(rating);
        };
        $("#rateYo1").rateYo({rating: 4.2});
        $("#rateYo2").rateYo({rating: 3.8});
        $("#rateYo3").rateYo({rating: 4.8});
        $("#rateYo4").rateYo({rating: 2.2});
        $("#rateYo5").rateYo({rating: 1.2});
        $("#rateYo6").rateYo({rating: 2.8});
        $("#rateYo7").rateYo({rating: 4.8});
        $("#rateYo8").rateYo({rating: 4.2});
    });
</script>