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
            $news_subject = stripslashes($data['news_subject']);
            $info['news_link'] = get_settings("news")['news_image_link'] == 0 ? INFUSIONS."news/news.php?cat_id=".$data['news_cat'] : INFUSIONS."news/news.php?readmore=".$data['news_id'];
            $info['print_url'] = BASEDIR."print.php?type=N&amp;item_id=".$data['news_id'];

			$imageSource = IMAGES_N."news_default.jpg";
            $imageRaw = '';
            if (!empty($data['news_cat_image'])) {
                $imageSource = get_image("nc_".$data['news_cat_name']);
                $imageRaw = $imageSource;
            }
            if (!get_settings("news")['news_image_frontpage']) {
                if (!empty($data['news_image']) && file_exists(IMAGES_N.$data['news_image'])) {
                    $imageSource = IMAGES_N.$data['news_image'];
                    $imageRaw = $imageSource;
                }
                if (!empty($data['news_image_t2']) && file_exists(IMAGES_N_T.$data['news_image_t2'])) {
                    $imageSource = IMAGES_N_T.$data['news_image_t2'];
                }
                if (!empty($data['news_image_t1']) && file_exists(IMAGES_N_T.$data['news_image_t1'])) {
                    $imageSource = IMAGES_N_T.$data['news_image_t1'];
                }
            }
			
            // Image with link always use the hi-res ones
            $image = "<img class='img-responsive' src='$imageSource' alt='".$news_subject."' />\n";

            if (!empty($data['news_extended'])) {
                $news_image = "<a class='img-link' href='".$info['news_link']."'>$image</a>\n";
            } else {
                $news_image = $image;
            }

            $news_cat_image = "<a href='".$info['news_link']."'>";
            if (!empty($data['news_image_t2']) && get_settings("news")['news_image_frontpage'] == 0) {
                $news_cat_image .= $image."</a>";
            } else if (!empty($data['news_cat_image'])) {
                $news_cat_image .= "<img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' class='img-responsive news-category' /></a>";
            }

			
            $item = [
                'news_id'   			=> $data['news_id'],
                'news_subject'   		=> $news_subject,
                "news_date"             => $data['news_datestamp'],
                "news_cat_id"           => $data['news_cat'],
                "news_cat_name"         => !empty($data['news_cat_name']) ? $data['news_cat_name'] : fusion_get_locale('news_0006'),
                "news_image_url"        => (get_settings("news")['news_image_link'] == 0 ? INFUSIONS."news/news.php?cat_id=".$data['news_cat_id'] : INFUSIONS."news/news.php?readmore=".$data['news_id']),
                "news_cat_image"        => $news_cat_image,
                "news_image"            => $news_image, // image with news link enclosed
                'news_image_src'        => $imageRaw, // raw full image
                "news_image_optimized"  => $imageSource, // optimized image
                "news_ext"              => $data['news_extended'] ? "y" : "n",
                "news_reads"            => $data['news_reads'],
				'link' 					=> INFUSIONS.'news/news.php?readmore='.$data['news_id'],

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