<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Books;
use app\models\BooksMeta;
use app\models\BooksMetaQuery;


class BooksController extends Controller
{
    public $GNBSelector = 'books';

    public function actionIndex()
    {
        return $this->render('home');
    }

    public function actionBest()
    {
        return $this->render('best');
    }

    public function actionBrandAll()
    {
        return $this->render('brandAll');
    }

    public function actionBrandSeries()
    {
        return $this->render('brandSeries');
    }

    public function actionBrandView()
    {
        return $this->render('brandView');
    }

    public function actionDetail()
    {
        return $this->render('detail');
    }

    public function actionIssue()
    {
        return $this->render('issue');
    }

    public function actionRecommend()
    {
        return $this->render('recommend');
    }

    public function actionRecommendBook()
    {
        return $this->render('recommendBook');
    }

    public function actionReviewList()
    {
        return $this->render('reviewList');
    }

    public function actionReviewView()
    {
        return $this->render('reviewView');
    }

    public function actionReviewWrite()
    {
        return $this->render('reviewWrite');
    }
}
