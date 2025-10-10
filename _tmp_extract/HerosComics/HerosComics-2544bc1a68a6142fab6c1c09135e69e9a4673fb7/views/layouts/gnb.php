<?php
    $GNBSelector = isset($this->context->GNBSelector) ? $this->context->GNBSelector : '';
?>
<header id="header">
    <div class="cnt-inner">
        <div class="header-top"><h1 class="eng"><a class="cl-heros" href="/">HEROS COMICS</a></h1>
            <div class="header-search">
                <div class="type-pc">
                    <div class="slash-54"><input type="text" class="text" placeholder="가디언즈 오브 갤럭시" /></div>
                </div>
                <div class="type-mobile">
                    <div class="header-search-btn not-active"><a href="#"><img src="/assets/img/icon-search.png" alt="" /></a></div>
                    <div class="header-search-layer" style="display:none;">
                        <input type="text" class="text" placeholder="가디언즈 오브 갤럭시" /><a href="#" class="btn btn-search-s"><img src="/assets/img/icon-search.png" alt="" /></a>
                    </div>
                </div>
            </div>
            <div class="header-my">
                <a href="#" class="js-my-open js-pc"><img src="/assets/img/icon-my.png" alt="개인화영역" /></a>
                <div class="header-my-open" style="display:none;">
                    <ul>
                        <li><strong>홍길동</strong> 님</li>
                        <li><a href="my-page.html">마이페이지</a></li>
                        <li><a href="my-page-library.html">내서재</a></li>
                        <li><a href="my-page-wish.html">위시리스트</a></li>
                        <li><a href="my-order-list.html">구매내역</a></li>
                        <li><a href="main.html">로그아웃</a></li>
                    </ul>
                </div>
            </div>
            <div class="header-cart">
                <a href="order-bag.html"><img src="/assets/img/icon-cart.png" alt="장바구니" /><span class="header-cart-count">20</span></a>
            </div>
        </div>
    </div>
    <div class="cnt-wrapper bg-gray">
        <div class="cnt-inner">
            <div id="gnb">
                <nav class="gnb-nav">
                    <ul>
                        <li class="eng <?if($GNBSelector=='news'){?>is-selected<?}?>"><a href="/news">NEWS</a></li>
                        <li class="eng <?if($GNBSelector=='books'){?>is-selected<?}?>"><a href="/books">BOOKS</a></li>
                        <li class="eng <?if($GNBSelector=='comm'){?>is-selected<?}?>"><a href="/comm">COMMUNITY</a></li>
                    </ul>
                </nav>
                <button type="button" title="확장메뉴" class="nav-icon is-close"><span class="icon-line "></span> <span class="icon-line"></span> <span class="icon-line "></span></button>
                <nav class="gnb-nav-extend" style="display:none;">
                    <ul>
                        <li><p class="eng">NEWS</p>
                            <ul>
                                <li><a href="books-home.html">종합</a></li>
                                <li><a href="#">MARVEL COMICS</a></li>
                                <li><a href="#">DC COMICS</a></li>
                                <li><a href="#">IMAGE COMICS</a></li>
                            </ul>
                        </li>
                        <li><p class="eng">BOOKS</p>
                            <ul>
                                <li><a href="/books/issue">신간</a></li>
                                <li><a href="/books/best">베스트</a></li>
                                <li><a href="/books/brand-all">브랜드별</a></li>
                                <li><a href="/books/recommend">추천</a></li>
                            </ul>
                        </li>
                        <li><p class="eng">COMMUNITY</p>
                            <ul>
                                <li><a href="comm-list.html">MARVEL COMICS</a></li>
                                <li><a href="#">DC COMICS</a></li>
                                <li><a href="#">IMAGE COMICS</a></li>
                            </ul>
                        </li>
                        <li><p class="">고객센터</p>
                            <ul>
                                <li><a href="cs-main.html">고객센터 안내</a></li>
                                <li><a href="cs-notice-list.html">공지사항</a></li>
                                <li><a href="cs-faq-list.html">자주 묻는 질문</a></li>
                                <li><a href="cs-1vs1-list.html">1:1 문 의</a></li>
                                <li><a href="cs-book-list.html">도서신청</a></li>
                                <li><a href="cs-inquiry-list.html">건의사항</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!--// header -->