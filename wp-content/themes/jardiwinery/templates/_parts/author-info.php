<?php
// Get template args
extract(jardiwinery_template_last_args('single-footer'));

if (jardiwinery_get_custom_option("show_post_author") == 'yes') {
	$post_author_name = $post_author_socials = '';
	$show_post_author_socials = true;
	if ($post_data['post_type']=='post') {
		$post_author_title = esc_html__('About author', 'jardiwinery');
		$post_author_name = $post_data['post_author'];
		$post_author_url = $post_data['post_author_url'];
		$post_author_email = get_the_author_meta('user_email', $post_data['post_author_id']);
		$mult = jardiwinery_get_retina_multiplier();
		$post_author_avatar = get_avatar($post_author_email, 78*$mult);
		$post_author_descr = jardiwinery_do_shortcode(nl2br(get_the_author_meta('description', $post_data['post_author_id'])));
		if ($show_post_author_socials) 
			$post_author_socials = jardiwinery_show_user_socials( array(
				'author_id' => $post_data['post_author_id'],
				'size' => 'tiny',
				'shape' => 'round',
				'echo' => false
				)
			);
	}
	if (!empty($post_author_descr)) {
		?>
		<section class="post_author author vcard" itemprop="author" itemscope itemtype="//schema.org/Person">
			<div class="post_author_avatar"><a href="<?php echo esc_url($post_data['post_author_url']); ?>" itemprop="image"><?php jardiwinery_show_layout($post_author_avatar); ?></a></div>
            <span class="about_author"><?php echo esc_html($post_author_title); ?></span>
            <h5 class="post_author_title"> <span itemprop="name"><a href="<?php echo esc_url($post_author_url); ?>" class="fn"><?php jardiwinery_show_layout($post_author_name); ?></a></span></h5>
			<div class="post_author_info" itemprop="description">
			<?php jardiwinery_show_layout($post_author_descr); ?>
			<?php if ($post_author_socials!='') jardiwinery_show_layout($post_author_socials); ?>
			</div>
		</section>
		<?php
	}
}
?>