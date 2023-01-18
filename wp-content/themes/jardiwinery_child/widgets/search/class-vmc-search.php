<?php

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Vmc_Search class
 *
 * @extends WP_Widget
 * @class Vmc_Search The class that registered a new widget
 * entire Vmc_Search plugin
 */

class Vmc_Search_Widget extends WP_Widget {

    /**
     * Constructor for the Vmc_Search class
     *
     * @uses is_admin()
     */
    public function __construct() {
        parent::__construct(
            'vmc_search',
            __( 'Recherche multicritère', 'vmc' ),
            array( 'description' => __( 'Recherche multicritère', 'vmc' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( $args && is_array( $args ) ) {
            extract( $args, EXTR_SKIP ); // phpcs:ignore
        }

        $title              = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) : '';

        echo isset( $before_widget ) ? $before_widget : '';

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <div class="dokan-product-search vmc-search">
            <form role="search" method="get" class="" action="<?php echo esc_url( home_url( '/multisearch' ) ); ?>">
                <div class="input-group">
                    <input type="text" autocomplete="off" id="input-vmc-search"  class="form-control" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php echo $title; ?>" />
		    <div id="prix-interval">
                        <label for="amount"><?php echo __( 'Price range', 'vmc' );?></label>
                        <input type="text" id="amount" readonly class="input-prix-interval">
                        <div id="slider-range"></div>
                        <br>
                    </div>
                    <span class="input-group-addon">
                        <select  name='post_type' id="vmc-search-select" class='orderby' >
                            <option class="level-0" value="product"
                            <?php if(get_post_type_search_query()=='product'){?>selected="selected"<?php }?>>
                                <?php echo __( 'Product', 'vmc' );?>
                            </option>
                            <option class="level-0" value="vendor" 
                            <?php if(get_post_type_search_query()=='vendor'){?>selected="selected"<?php }?>>
                                <?php echo __( 'Vendor', 'vmc' );?>
                            </option>
                            <option class="level-0" value="prix"
                            <?php if(get_post_type_search_query()=='prix'){?> selected="selected"<?php }?>>
                                <?php echo __( 'Price', 'vmc' );?>
                            </option>
                        </select>
                    </span>
                    <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'woocommerce' ); ?></button>
                </div>
            </form>
        </div>
        <?php
        echo isset( $after_widget ) ? $after_widget : '';
    }

    public function form( $instance ) {
        if ( isset( $instance['title'] ) ) {
            $title = $instance['title'];
        } else {
            $title = __( 'Recherche multicritère', 'vmc' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'vmc' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

}
