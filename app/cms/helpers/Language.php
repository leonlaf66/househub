<?php
namespace module\cms\helpers;

use WS;
use yii\db\Query;

class Language
{
	public static function submit($category, $source, $translation, $lang='zh-CN')
	{
		if(! $translation || trim($translation)=='') $translation = $source;

        $query = new Query();

        $findSql = 'select id from i18n_source_message where category=:category and message=:message';
        $sourceId = $query
            ->from('i18n_source_message')
            ->select('id')
            ->where(['category'=>$category, 'message'=>$source])
            ->scalar();
        if(! $sourceId) {
            WS::$app->db->createCommand()->insert('i18n_source_message', [
                    'category'=>$category, 'message'=>$source
                ])->execute();
            $sourceId = WS::$app->db->getLastInsertID();
        }
        else {
            WS::$app->db->createCommand()->update('i18n_source_message', ['message'=>$source], ['id'=>$sourceId]);
        }

        $isExistsMessageItem = $query->from('i18n_message')
            ->where(['id'=>$sourceId, 'language'=>$lang])
            ->exists();

        if($isExistsMessageItem) {
            if($source !== $translation) {
                WS::$app->db->createCommand()
                    ->update('i18n_message', ['translation'=>$translation], 'id=:id and language=:lang', [':id'=>$sourceId, ':lang'=>$lang])
                    ->execute();
            }
        }
        else {
            WS::$app->db->createCommand()
                ->insert('i18n_message', ['id'=>$sourceId, 'language'=>$lang, 'translation'=>$translation])
                ->execute();
        }
	}
}