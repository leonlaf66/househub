<?php
return [
    'home/default/index' => function ($req) {
        return '/';
    },
    'estate/house/purchase' => function ($req) {
        return '/purchase.html';
    },
    'estate/house/lease' => function ($req) {
        return '/rental.html';
    },
    'estate/house/purchase/view' => function ($req) {
        return ['/home.html', 'id' => $req->get('id')];
    },
    'estate/house/lease/view' => function ($req) {
        return ['/home.html', 'id' => $req->get('id')];
    },
    'news/default/index' => function ($req) {
        return '/newsMore.html';
    },
    'news/default/view' => function ($req) {
        return ['/news.html', 'id'=>$req->get('id')];
    }
];