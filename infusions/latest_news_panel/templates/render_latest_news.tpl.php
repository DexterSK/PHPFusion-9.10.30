<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: latest_downloads.tpl.php
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

if (!function_exists('render_latest_news')) {
    function render_latest_news($info) {
        $locale = fusion_get_locale();

        add_to_jquery("$('[data-trim-text]').trim_text();");

        openside($info['title']);
        if (!empty($info['item'])) {
				echo '<div class="card">';
					echo '<div class="row equal-height">';
            foreach ($info['item'] as $data) {
					$link = INFUSIONS.'news/news.php?readmore='.$data['news_id'];
					
					
					echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 m-t-15 m-b-20">';
						echo '<article class="item">';
							echo '<div>';
							echo '<figure class="thumb">';
								echo '<a href="'.$link.'">';
									$thumb = !empty($data['news_image_optimized']) ? $data['news_image_optimized'] : get_image('imagenotfound');
									echo '<img class="img-responsive" src="'.$thumb.'" alt="'.$data['news_subject'].'">';
								echo '</a>';
							echo '</figure>';
							echo '<div class="post clearfix">';
								echo '<h2 class="post-title"><a href="'.$link.'">'.$data['news_subject'].'</a></h2>';
								echo '<div class="meta">';
									echo '<span class="m-r-5"><i class="fa fa-user"></i> '.$data['profile_link'].'</span>';
									echo '<span class="m-r-5"><i class="fa fa-clock"></i> '.showdate(fusion_get_settings('newsdate'), $data['news_datestamp']).'</span>';
									echo '<span><i class="fa fa-folder"></i> <a href="'.INFUSIONS.'news/news.php?cat_id='.$data['news_cat_id'].'">'.$data['news_cat_name'].'</a></span>';
								echo '</div>';
							echo '</div>';
							echo '</div>';

							echo '<a href="'.$link.'" class="readmore">'.MaterialTheme\Core::setLocale('readmore').'</a>';
						echo '</article>';
					echo '</div>';
            }
				echo '</div>';

			echo '</div>';

        } else {
            echo $info['no_item'];
        }
        closeside();
    }
}