<?php

/**
 * Adds FeedWidget widget.
 */
class GigyaFeed_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$args = array(
				'description' => __( 'Activity Feed block by Gigya' )
		);
		parent::__construct( 'gigya_feed', __( 'Gigya Activity Feed' ), $args );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $this->getContent( $args, $instance );
	}

	/**
	 * @param $args
	 * @param $instance
	 *
	 * @return string
	 */
	public function getContent( $args, $instance ) {
		$output = '';
		$title  = apply_filters( 'widget_title', $instance['title'] );

		// Get the data from the argument.
		require_once GIGYA__PLUGIN_DIR . 'features/feed/GigyaFeedSet.php';
		$feed = new GigyaFeedSet();
		$data = $feed->getParams();

		// Override params or take the defaults.
		if ( ! empty( $instance['override'] ) ) {
			foreach ( $instance as $key => $value ) {
				if ( ! empty( $value ) ) {
					$data[$key] = esc_attr( $value );
				}
			}
		}

		// Set the output.
		$output .= $args['before_widget'];
		if ( ! empty( $title ) ) {
			$output .= $args['before_title'] . $title . $args['after_title'];
		}
		$output .= '<div class="gigya-feed-widget"></div>';
		$output .= '<script class="data-feed" type="application/json">' . json_encode( $data ) . '</script>';

		$output .= $args['after_widget'];

		return $output;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {

		$form = array();

		$form[$this->get_field_id( 'title' )] = array(
				'type'  => 'text',
				'value' => esc_attr( _gigParam( $instance, 'title', '' ) ),
				'label' => __( 'Title' ),
				'class' => 'size',
				'name'  => $this->get_field_name( 'title' )
		);

		$form[$this->get_field_id( 'override' )] = array(
				'type'  => 'checkbox',
				'value' => esc_attr( _gigParam( $instance, 'override', '' ) ),
				'label' => __( 'Override' ),
				'class' => 'gigya-widget-override',
				'name'  => $this->get_field_name( 'override' )
		);

		$form[$this->get_field_id( 'width' )] = array(
				'type'  => 'text',
				'value' => esc_attr( _gigParam( $instance, 'width', '' ) ),
				'label' => __( 'Width' ),
				'class' => 'size',
				'name'  => $this->get_field_name( 'width' )
		);

		$form[$this->get_field_id( 'height' )] = array(
				'type'  => 'text',
				'value' => esc_attr( _gigParam( $instance, 'height', '' ) ),
				'label' => __( 'Height' ),
				'class' => 'size',
				'name'  => $this->get_field_name( 'height' )
		);

		echo _gigya_form_render( $form );
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$new_instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $new_instance;
	}

}