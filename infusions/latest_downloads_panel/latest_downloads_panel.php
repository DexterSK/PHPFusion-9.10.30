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

if( defined('DOWNLOADS_EXISTS') )
{
    include_once INFUSIONS."latest_downloads_panel/templates/render_latest_downloads.tpl.php";

    $result = dbquery("SELECT d.download_id, d.download_description_short, d.download_copyright, d.download_os, d.download_license, d.download_version, d.download_description, d.download_count, d.download_datestamp, d.download_title, d.download_cat, d.download_image, u.user_id, u.user_name, u.user_status, u.user_avatar, dc.download_cat_id, dc.download_cat_name
        FROM ".DB_DOWNLOADS." d
        INNER JOIN ".DB_DOWNLOAD_CATS." dc ON d.download_cat=dc.download_cat_id
        LEFT JOIN ".DB_USERS." u ON u.user_id = d.download_user
        ".(multilang_table("DL") ? "WHERE ".in_group('download_cat_language', LANGUAGE)." AND " : "WHERE ").groupaccess('download_visibility')."
        ORDER BY download_datestamp DESC
        LIMIT 5
    ");

    $info = [];

    $info['title'] = $locale['global_032'];

    if (dbrows($result)) {
        while ($data = dbarray($result)) {
			$item = [
                "download_description_short"        => parse_text($data['download_description_short'], [
					'decode'          => FALSE,
					'add_line_breaks' => TRUE
				]),
                "download_description"            	=> $data['download_id'],
					"download_id"            		=> parse_text($data['download_description'], [
					'parse_smileys'        => FALSE,
					'parse_bbcode'         => FALSE,
					'default_image_folder' => NULL,
					'add_line_breaks'      => TRUE
				]),
				"download_image"            		=> INFUSIONS."downloads/images/".$data['download_image'],
				"download_file_link"            	=> INFUSIONS."downloads/downloads.php?file_id=".$data['download_id'],
				"download_post_author"            	=> display_avatar($data, '25px', '', TRUE, 'img-rounded m-r-5').profile_link($data['user_id'], $data['user_name'], $data['user_status']),
				"download_author_link"            	=> profile_link($data['user_id'], $data['user_name'], $data['user_status']),
				"download_post_cat"            		=> $locale['in']." <a href='".INFUSIONS."downloads/downloads.php?cat_id=".$data['download_cat']."'>".$data['download_cat_name']."</a>",
				"download_post_time"            	=> showdate('shortdate', $data['download_datestamp']),
				"download_post_time2"            	=> $locale['global_049']." ".timer($data['download_datestamp']),
				"download_count"            		=> format_word($data['download_count'], $locale['fmt_download']),
				"download_version"            		=> !empty($data['download_version']) ? $data['download_version'] : $locale['na'],
				"download_license"            		=> !empty($data['download_license']) ? $data['download_license'] : $locale['na'],
				"download_os"            			=> !empty($data['download_os']) ? $data['download_os'] : $locale['na'],
				"download_copyright"            	=> $data['download_id'],
				"download_id"            			=> !empty($data['download_copyright']) ? $data['download_copyright'] : $locale['na'],
				"download_title"            		=> $data['download_title'],
				'download_updated' 					=> $locale['global_049']." ".timer($data['download_datestamp']),

                'userdata'       	=> [
                    'user_id'     	=> $data['user_id'],
                    'user_name'   	=> $data['user_name'],
                    'user_status' 	=> $data['user_status'],
                    'user_avatar' 	=> $data['user_avatar']
                ],
                'download_link'   	=> "<a class='text-dark' href='".INFUSIONS."downloads/downloads.php?cat_id=".$data['download_cat']."&download_id=".$data['download_id']."'>".$data['download_title']."</a>"
				
            ];

            //$info['download_item'] = $item;
			$info['download_item'][] = $item;
        }
    } else {
        $info['no_item'] = $locale['global_033'];
    }

    render_latest_downloads($info);
}