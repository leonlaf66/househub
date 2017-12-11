<?php
return [
    'prop_type'=>[
        'title'=>'Property Type',
        'values'=>[
            'RN'=>'Rental', 
            'SF'=>'Single Family', 
            'MF'=>'Muti Family', 
            'CC'=>'Condominium',
            'CI'=>'Commercial', 
            'BU'=>'Business Opportunity', 
            'LD'=>'Land'
        ]
    ],
    'no_rooms'=>[
        'title'=>'Rooms'
    ],
    'no_bedrooms'=>[
        'title'=>'Bedrooms'
    ],
    'bath_count'=>[
        'title'=>'Baths'
    ],
    'lot_size'=>[
        'title'=>'Lot Size',
        'suffix'=>'Sq.Ft'
    ],
    'square_feet'=>[
        'title'=>'Living Area',
        'suffix'=>'Sq.Ft'
    ],
    'master_bath'=>[
        'title'=>'Master Bath',
        'values'=>[
            'Y'=>'Yes',
            'N'=>'No'
        ]
    ],
    'list_date'=>[
        'date'=>'Y-m-d'
    ],
    'status'=>[
        'map'=>1
    ],
    'basement'=>[
        'source'=>'rets/yesno1'
    ],
    'market_time_property'=>[
        'render'=>'getMarketTimeProperty'
    ]
];
