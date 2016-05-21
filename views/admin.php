<?php 
	$categories = get_categories();
?>
<p>
	<label for="<?php echo $this->get_field_id( 'category' ); ?>">
		Category:
	</label>
	<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="widefat" >
		<option value="all"<?php selected( $instance['category'], 'all' ) ?> )>All</option>
		<?php
			foreach ( $categories as $category ) {
				echo '<option value="'.esc_attr( $category->term_id ).'" '.selected( $instance['category'], esc_attr( $category->term_id ) ) .' >'.esc_html( $category->cat_name ).'</option>';
		    }
		?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'featuredimage' ); ?>">
		Display Featured Image?:
	</label>
	<input <?php checked( $instance['featuredimage'], 1 ); ?> type="checkbox" value="1" id="<?php echo $this->get_field_id( 'featuredimage' ); ?>" name="<?php echo $this->get_field_name( 'featuredimage' ); ?>" class="widefat" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'imagesize' ); ?>">
		Featured Image Size:
	</label>
	<select id="<?php echo $this->get_field_id( 'imagesize' ); ?>" name="<?php echo $this->get_field_name( 'imagesize' ); ?>" class="widefat" >
		<?php
			$imageSizes = get_intermediate_image_sizes();
			foreach ( $imageSizes as $size ) {
				echo '<option value="'.$size.'" '.selected( $instance['imagesize'], $size ) .' >'.esc_html( $size ).'</option>';
		    }
		?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'itemcount' ); ?>">
		How many items to display:
	</label>
	<input value="<?php echo $instance['itemcount'] ?>" type="number" id="<?php echo $this->get_field_id( 'itemcount' ); ?>"  name="<?php echo $this->get_field_name( 'itemcount' ); ?>" class="widefat" min="1" max="6">
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'itemmargin' ); ?>">
		Margin between items (in pixels):
	</label>
	<input type="number" value="<?php echo $instance['itemmargin'] ?>" id="<?php echo $this->get_field_id( 'itemmargin' ); ?>" name="<?php echo $this->get_field_name( 'itemmargin' ); ?>" class="widefat" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'nav' ); ?>">
		Show Prev / Next Buttons?:
	</label>
	<input <?php checked( $instance['nav'], 1 ); ?> type="checkbox" value="1" id="<?php echo $this->get_field_id( 'nav' ); ?>" name="<?php echo $this->get_field_name( 'nav' ); ?>" class="widefat" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'navdots' ); ?>">
		Show Navigation Dots?:
	</label>
	<input <?php checked( $instance['navdots'], 1 ); ?> type="checkbox" value="1" id="<?php echo $this->get_field_id( 'navdots' ); ?>" name="<?php echo $this->get_field_name( 'navdots' ); ?>" class="widefat" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'autoplay' ); ?>">
		Autoplay Slides?:
	</label>
	<input <?php checked( $instance['autoplay'], 1 ); ?> type="checkbox" value="1" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" class="widefat" />
</p>