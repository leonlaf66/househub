<?php
namespace module\catalog\models;

class SubwayStation extends \models\SubwayStation
{
    public static function getStationsByLine($code)
    {
        if(is_numeric($code)) {
            $line = SubwayLine::find($code);
            if($line && $line->code) {
                $code = $line->code;
            }
            else {
                return [];
            }
        }

        return self::find(['line_code'=>$code])
                ->orderBy(['sort_order'=>SORT_ASC])
                ->all();
    }

    public static function getAllStations()
    {
        $allStations = [];

        $lines = SubwayLine::getMapOptions();
        foreach($lines as $code=>$item) {
            $allStations[$code] = $item;
        }

        $items = self::find()->orderBy(['sort_order'=>SORT_ASC])->all();
        foreach($items as $item) {
            $code = $item['line_code'];
            if(isset($allStations[$code])) {
                $allStations[$code]->stations[] = $item;
            }
        }

        return $allStations;
    }

    public static function dictOptions()
    {
        $results = [];

        $stations = self::getAllStations();
        foreach ($stations as $line) {
            $id = $line->id;
            $results[$id] = $line->toArray();

            $stations = $line->stations;
            $stations = array_map(function ($d) {
                return $d->toArray();
            }, $stations);

            $results[$id]['stations'] = array_column($stations, null, 'id');
        }

        return $results;
    }
}