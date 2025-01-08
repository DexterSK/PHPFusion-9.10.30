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

if (!function_exists('render_latest_downloads')) {
    function render_latest_downloads($info) {
        $locale = fusion_get_locale();

        add_to_jquery("$('[data-trim-text]').trim_text();");

        openside($info['title']);
        if (!empty($info['download_item']))
		{
			echo '<div class="card">';
				echo '<div class="row equal-height">';
				foreach ($info['download_item'] as $data)
				{
					echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 m-t-15 m-b-20">';
						echo '<article class="item">';
							echo '<div>';
							echo '<figure class="thumb">';
								echo '<a href="'.$data['download_file_link'].'">';
									$thumb = !empty($data['download_image']) ? $data['download_image'] : get_image('imagenotfound');
									echo '<img class="img-responsive" src="'.$thumb.'" alt="'.$data['download_title'].'" width="150">';
								echo '</a>';
							echo '</figure>';
							echo '<div class="post clearfix">';
								echo '<h2 class="post-title"><a href="'.$data['download_file_link'].'">'.$data['download_title'].'</a></h2>';
								echo '<div class="meta">';
									echo '<span class="m-r-5"><i class="fa fa-user"></i> '.$data['download_author_link'].'</span>';
									echo '<span class="m-r-5"><i class="fa fa-clock"></i> '.$data['download_post_time'].'</span>';
									echo '<span><i class="fa fa-folder"></i> <a href="'.$data['download_file_link'].'">'.$data['download_post_cat'].'</a></span>';
								echo '</div>';
							echo '</div>';
							echo '</div>';

							//echo '<a href="'.$data['download_file_link'].'" class="readmore">Čítať viac</a>';
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