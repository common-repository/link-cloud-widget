<?php
/*
Plugin Name: Link-Cloud Widget
Plugin URI: http://wordpress.org/extend/plugins/link-cloud-widget/
Description: Dieses Widget stellt Links in Form einer Cloud dar. 
Version: 0.3
Author: pehbehbeh
Author URI: http://pehbehbeh.de/
*/

/**
 * PBB_Widget_LinkCloud
 */
class PBB_Widget_LinkCloud extends WP_Widget
{
    function PBB_Widget_LinkCloud()
    {
        $widget_ops = array('description' => 'Eine Link-Cloud');
        $this->WP_Widget('linkcloud', 'Link-Cloud', $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        $title = isset($instance['title']) ? $instance['title'] : 'Link-Cloud';
        $category = isset($instance['category']) ? $instance['category'] : false;

        if (!$category) {
            return;
        }

        $links = get_bookmarks(array(
            'category' => $category
        ));

        echo $before_widget;
        echo $before_title . $title . $after_title;

        foreach ($links as $link)
        {
            printf('<a title="%1$s" href="%2$s" style="font-size: %3$spt" rel="%4$s">%5$s</a>&nbsp;',
                    $link->link_title,
                    $link->link_url,
                    $link->link_rating+8,
                    $link->link_rel,
                    $link->link_name
                    );
        }

        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $new_instance = (array) $new_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['category'] = intval($new_instance['category']);
        
        return $instance;
    }

    function form($instance)
    {
        $title = esc_attr( $instance['title'] );
        $link_cats = get_terms( 'link_category');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>" class="screen-reader-text"><?php _e('Select Link Category'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
                <option value=""><?php _e('All Links'); ?></option>
                <?php
		foreach ($link_cats as $link_cat)
                {
                    echo '<option value="' . intval($link_cat->term_id) . '"'
                            . ( $link_cat->term_id == $instance['category'] ? ' selected="selected"' : '' )
                            . '>' . $link_cat->name . "</option>\n";
                }
		?>
            </select>
        </p>
        <?php
    }
}


/**
 * Init
 */
function PBB_Widget_LinkCloud_Init()
{
    register_widget('PBB_Widget_LinkCloud');
}

add_action('widgets_init', 'PBB_Widget_LinkCloud_Init');