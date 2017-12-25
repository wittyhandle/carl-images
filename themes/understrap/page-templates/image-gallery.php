<?php 
/**
* Template Name: Image Gallery Template
*/
get_header(); ?>

<div class="wrapper" id="wrapper-index">
	<div class="container" id="content" tabindex="-1">
		<main class="site-main" id="main">
			
			<?php
				global $current_user;
				wp_get_current_user();
			?>

			<p>User: <?php echo get_user_meta($current_user->ID, 'cdgd_client', true); ?></p>

			<?php

				$args = array(
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'post_status' => 'inherit',
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'cdgd_client',
							'value' => 'null'
						),
						array(
							'key' => 'cdgd_client',
							'value' => '58'
						)
					)
				);

				$query_images = new WP_Query( $args );
				$idx = 0;
				if ($query_images->have_posts()):
					while($query_images->have_posts()) : $query_images->the_post();
						$image = wp_get_attachment_image_src( get_the_ID(), 'carldetorres-grid-image')[0];			
			?>		
			
					<?php
						if ($idx++ % 4 == 0):
					?>
						<div class="row top-buffer">
					<?php
						endif;
					?>
							<div class="col-lg-3">
								<div class="card cdgd">
									<img class="card-img-top" src="<?php echo $image; ?>">
									<div class="card-block">
										<p class="card-title"><?php echo the_title(); ?></p>
										<a href="#" class="btn btn-secondary">Buy</a>
									</div>
								</div>
							</div>
					<?php
						if ($idx % 4 == 0):
					?>
						</div> <!-- .row -->
					<?php
						endif;
					?>
			<?php
					endwhile;
				endif;
			?>			
		</main>
	</div>  <!-- .container -->
</div>

<?php get_footer(); ?> 