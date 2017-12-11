<?php
return [
	'urlRules' => [
        'pro-service/'  => 'yellowpage/default/index',
        'pro-service/search/city<city:\d+>/<type:\d+>/' => 'yellowpage/search/result',
        'pro-service/search/city<city:\d+>/' => 'yellowpage/search/result',
        
        'pro-service/search/<type:\d+>/<page:\d+>/' => 'yellowpage/search/result',
        'pro-service/search/<type:\d+>/' => 'yellowpage/search/result',
        'pro-service/search/' => 'yellowpage/search/result',
        
        'pro-service/<id:\d+>/<page:\d+>/' => 'yellowpage/default/view',
        'pro-service/<id:\d+>/' => 'yellowpage/default/view'
	]
];