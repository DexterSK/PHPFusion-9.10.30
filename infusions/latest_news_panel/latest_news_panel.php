<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: latest_downloads_panel.php
| Author: Core Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
defined('IN_FUSION') || exit;

$locale = fusion_get_locale();

if( defined('NEWS_EXISTS') )
{
    include_once INFUSIONS."latest_news_panel/templates/render_latest_news.tpl.php";

			$result = dbquery(
			   "SELECT * FROM ".DB_NEWS." tn
				  LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
				  LEFT JOIN ".DB_NEWS_CATS." tc ON tn.news_cat=tc.news_cat_id
			   WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") AND news_draft='0'
			   ORDER BY news_sticky DESC, news_datestamp DESC LIMIT 3"
			);

    $info = [];

    $info['title'] = "Novinky";

    if (dbrows($result)) {
        while ($data = dbarray($result)) {
			
            $item = [
                'news_id'   		=> $data['news_id'],
                'news_cat_id'  	 	=> $data['news_cat_id'],
                'news_subject'   	=> $data['news_subject'],
                'news_cat_name'   	=> $data['news_cat_name'],
                'news_datestamp'   	=> $data['news_datestamp'],
				'link' 				=> INFUSIONS.'news/news.php?readmore='.$data['news_id'],

                'userdata'       	=> [
                    'user_id'     	=> $data['user_id'],
                    'user_name'   	=> $data['user_name'],
                    'user_status' 	=> $data['user_status'],
                    'user_avatar' 	=> $data['user_avatar']
                ],
                'profile_link'   	=> profile_link($data['user_id'], $data['user_name'], $data['user_status'])
				
            ];
			
            $info['item'][] = $item;
        }
    } else {
        $info['no_item'] = $locale['global_033'];
    }

    render_latest_news($info);
}