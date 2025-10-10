<?php
namespace app\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Floating extends \yii\bootstrap\Widget
{

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
?>
        <div class="floating-banner type-pc">
            <div class="cart-list">
                <p class="cart-list-title">최근 본 상품</p>
                <!-- 최근 본 상품이 없을 경우 //
                <span class="noitem">
                    최근 본 상품이 <br>없습니다.
                </span> -->
                <a class="cart-list-thumbnail" href="#"><img src="/assets/img/@img-book-01.jpg" alt="" /></a>
                <a class="cart-list-thumbnail" href="#"><img src="/assets/img/@img-book-01.jpg" alt="" /></a>
                <a href="#" class="btn btn-cart">장바구니</a>
            </div>
        </div>
<?
    }
}
