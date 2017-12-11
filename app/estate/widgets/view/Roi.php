<?php
namespace module\estate\widgets\view;

use WS;

class Roi extends \yii\base\Widget 
{  
    public $rets = null;

    public function run()
    {
        /*****临时用****/
        $result = [
            'EST_ROI_CASH' => 0,
            'EST_ANNUAL_INCOME_CASH' => 0
        ];

        $aveResult = [
            'AVE_ROI_CASH' => 0,
            'EST_ANNUAL_INCOME_CASH' => 0,
            'AVE_ANNUAL_INCOME_CASH' => 0
        ];

        $result = array_merge($result, $aveResult);

        return $this->render('roi.phtml', [
            'result'=>$result
        ]);


        $listNo = $this->rets->list_no;
        $zipCode = $this->rets->zip_code;

        $result = WS::$app->db->createCommand('select * from house_info_roi where "LIST_NO"=:id', [
                ':id' => $listNo
            ])->queryOne();
        if (! $result) {
            $result = [
                'EST_ROI_CASH' => 0,
                'EST_ANNUAL_INCOME_CASH' => 0
            ];
        }

        $aveResult = WS::$app->db->createCommand('select * from zipcode_roi_ave where "ZIP_CODE"=:zip', [
                ':zip' => $zipCode
            ])->queryOne();
        if (! $aveResult) {
            $aveResult = [
                'AVE_ROI_CASH' => 0,
                'EST_ANNUAL_INCOME_CASH' => 0,
                'AVE_ANNUAL_INCOME_CASH' => 0
            ];
        }

        $result = array_merge($result, $aveResult);

        return $this->render('roi.phtml', [
            'result'=>$result
        ]);
    }
}