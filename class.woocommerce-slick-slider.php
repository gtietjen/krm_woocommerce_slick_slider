<?php

if ( !defined( 'ABSPATH' )  ) {
	exit; // Exit if accessed directly
}

/**
 * Implements admin features of  WooCommerce Slick Slider
 *
 * @class   KRM_WC_Slick_Slider
 * @package WooCommerce Slick Slider
 * @since   1.0.0
 * @author  Kream
 */
if ( !class_exists( 'KRM_WC_Slick_Slider' ) ) {

	class KRM_WC_Slick_Slider {

		/**
		 * Single instance of the class
		 *
		 * @var \KRM_WC_Slick_Slider
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \KRM_WC_Slick_Slider
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 */
		public function __construct() {

			//custom styles and javascripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ), 11 );

			add_shortcode( 'slick_slider', array( $this, 'slick_slider' ) );

			//custom fields for single product
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_custom_fields_for_single_products' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'save_custom_fields_for_single_products' ), 10, 2 );

		}

		public function add_custom_fields_for_single_products(){
			global $thepostid;
			$_logo     = get_post_meta( $thepostid, '_logo', true );
			?>
			<div class="options_group show_if_simple">
                <p class="form-field logo">
                    <label for="_ywsbs_price_is_per"><?php _e( 'Logo Prodotto', 'krm-woocommerce-slick-slider' ); ?></label>
                    <input type="text" class="short" name="_logo" id="_logo" value="<?php echo esc_attr( $_logo ); ?>" />
                </p>
			<?php
		}

		/**
         * Save custom fields for single product
         *
         * @since   1.0.0
         * @author  Emanuela Castorina
         * @return  void
         */
        public function save_custom_fields_for_single_products( $post_id, $post ){

            //reset custom field for the parent product
            if ( isset( $_POST['_logo'] ) ) {
                update_post_meta( $post_id, '_logo', $_POST['_logo'] );
            }


        }

		public function enqueue_styles_scripts(){

			wp_enqueue_style( 'slick', WSLICKSLIDER_ASSETS_URL . '/slick/slick.css', WSLICKSLIDER_VERSION );
			wp_enqueue_style( 'slick-theme', WSLICKSLIDER_ASSETS_URL . '/slick/slick-theme.css', WSLICKSLIDER_VERSION );
			wp_enqueue_script( 'slick', WSLICKSLIDER_ASSETS_URL . '/slick/slick.min.js', array( 'jquery' ), WSLICKSLIDER_VERSION, true );
			wp_enqueue_script( 'krm-slick-slider', WSLICKSLIDER_ASSETS_URL . '/slick-slider.js', array( 'jquery', 'slick' ), WSLICKSLIDER_VERSION, true );

		}


		public function slick_slider( $atts ){
			ob_start();
			extract( shortcode_atts( array(
				      'button' => 'off',
			     ), $atts ) );

			$args = array(
				'orderby'    => '',
				'hide_empty' => 1,
			);
			$product_tabs = array();
			$product_categories = get_terms( 'product_cat', $args );
			if( !empty( $product_categories)):
				?>
				<div class="w-tabs">
					<ul class="w-tabs-nav">
						<?php
						foreach (  $product_categories as $category ) :

							$args = array(
								'posts_per_page' => -1,
								'tax_query' => array(
									'relation' => 'AND',
									array(
										'taxonomy' => 'product_cat',
										'field' => 'slug',
										'terms' => $category->slug
									)
								),
								'post_type' => 'product',
								'orderby' => 'title'
							);
							$products = get_posts( $args );
							$product_tab[$category->slug] = $products;
							?>
							<li><a href="#<?php echo $category->slug ?>" data-toggle="tab"><?php echo $category->name ?></a></li>
						<?php endforeach; ?>
					</ul>

					<div class="w-tab-wrapper">
						<?php
						foreach ( $product_tab as $key=>$val  ) :

							?>
							<div class="tab-panel w-tab" id="<?php echo $key ?>">
								<div class="product-nav">

									<?php foreach ( $val as $post_product ):
										$weight = get_post_meta($post_product->ID,'_weight',true);
										$logo = get_post_meta($post_product->ID,'_logo',true);
										$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_product->ID) );
										?>
										<div class="product-slider">
											<div class="product-slider-pic col col-8">
												<?php
												if( $feat_image ){
													 echo '<img src="'.$feat_image.'">';
												}
												?></div>
											<div class="product-slider-desc col col-4">
												<div class="product-slider-wrapper">
													<div class="product-slider-content">
														<?php
															if( $logo ){
																 echo '<div class="logo_prod"><img src="'.$logo.'"></div>';
															}
														?>
														<h3> <?php echo $post_product->post_title ?></h3>
														<?php if( $weight ): ?>
															<p class="weight"><?php echo $weight ?>g</p>
														<?php endif ?>
														<p><?php echo $post_product->post_content ?></p>
														<?php if( $button == 'on' ): ?>
														 <a href="<?php echo get_permalink( $post_product->ID) ?>" class="product-info"><?php _e("Vedi Scheda", 'krm-woocommerce-slick-slider') ?></a>
														<?php endif ?>
													</div>
												</div>
											</div>

										</div>
									<?php endforeach;
									?>
								</div>
							</div>
						<?php endforeach;

						?>
					</div>
				</div>
			<?php endif ?>

			<?


			return ob_get_clean();
		}
	}
}

/**
 * Unique access to instance of KRM_WC_Slick_Slider class
 *
 * @return \KRM_WC_Slick_Slider
 */
function KRM_WC_Slick_Slider() {
	return KRM_WC_Slick_Slider::get_instance();
}
