<?php
return [
    'home/default/index' => function ($req) {
        return '/';
    },
    'estate/house/purchase' => function ($req) {
        return '/house/search/purchase';
    },
    'estate/house/lease' => function ($req) {
        return '/house/search/lease';
    },
    'estate/house/purchase/view' => function ($req) {
        return '/house/' . $req->get('id');
    },
    'estate/house/lease/view' => function ($req) {
        return '/house/' . $req->get('id');
    },
    'yellowpage/default/index' => function ($req) {
        return '/yellowpage';
    },
    'yellowpage/search/result' => function ($req) {
        return '/yellowpage';
    },
    'yellowpage/default/view' => function ($req) {
        return 'yellowpage/' . $req->get('type_id');
    },
    'news/default/index' => function ($req) {
        return '/news';
    },
    'news/default/view' => function ($req) {
        return '/news/' . $req->get('id');
    }
];