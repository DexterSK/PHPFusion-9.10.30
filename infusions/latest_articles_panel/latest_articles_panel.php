<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: latest_articles_panel.php
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

if( defined('ARTICLES_EXISTS') )
{
    include_once INFUSIONS."latest_articles_panel/templates/render_latest_articles.tpl.php";

    $result = dbquery("SELECT
            ar.article_id AS id,
            ar.article_subject AS title,
            ar.article_snippet AS content,
            ar.article_reads AS views_count,
            ar.article_datestamp AS datestamp,
            ac.article_cat_id AS cat_id,
            ac.article_cat_name AS cat_name,
            ar.article_thumbnail AS image_main,
            ".(!empty($comments_query) ? $comments_query : '')."
            ".(!empty($ratings_query) ? $ratings_query : '')."
            u.user_id, u.user_name, u.user_status
            FROM ".DB_ARTICLES." AS ar
            LEFT JOIN ".DB_ARTICLE_CATS." AS ac ON ac.article_cat_id = ar.article_cat
            LEFT JOIN ".DB_USERS." AS u ON u.user_id = ar.article_name
            WHERE ar.article_draft = 0
            AND ".groupaccess('ar.article_visibility')." ".(multilang_table("AR") ? "
            AND ".in_group('ac.article_cat_language', LANGUAGE) : "")."
            ORDER BY ar.article_datestamp DESC LIMIT 5"
        );

    $info = [];

    $info['title'] = $locale['home_0001'];

    if (dbrows($result))
	{
        while ($data = dbarray($result))
		{
			$data['content'] = parse_text($data['content'], ['parse_smileys' => FALSE, 'default_image_folder' => NULL]);
			$data['title'] = $data['title'];
            $data['url'] = INFUSIONS.'articles/articles.php?article_id='.$data['id'];
            $data['category_link'] = INFUSIONS.'articles/articles.php?cat_id='.$data['cat_id'];
            $data['views'] = format_word($data['views_count'], $locale['fmt_read']);
            $data['datestamp'] = $data['datestamp'];
            $data['cat_name'] = $data['cat_name'];
			$data['profile_link'] = profile_link($data['user_id'], $data['user_name'], $data['user_status']);
			
            if (!empty($data['image_main']) && file_exists(IMAGES_A_T.$data['image_main'])) {
               $data['image'] = IMAGES_A_T.$data['image_main'];
            }

            $info['data'][] = $data;
        }
    } else {
        $info['no_item'] = $locale['home_0051'];
    }

    render_latest_articles($info);
}