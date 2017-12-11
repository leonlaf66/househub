<?php
namespace module\estate\helpers;

use WS;

class SearchAutocomplete
{
    public static function map($isLease)
    {
        $stateId = WS::$app->area->stateId;

        return \models\City::getSearchList($stateId, function ($query) use ($isLease) {
            if ($isLease) {
                $query->innerJoin('listhub_index i', "e.id=i.city_id and i.prop_type='RN'");
            } else {
                $query->innerJoin('listhub_index i', "e.id=i.city_id and i.prop_type<>'RN'");
            }
        });
    }
}