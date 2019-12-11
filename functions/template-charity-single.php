<?php
function chawa_add_charity_content( $content ) {
	if( is_singular('charity') ) {
		$content .= '<form>';
		$content .= '<input type="number" step="1" min="1" name="quantity" value="1" title="Aantal" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">';
		$content .= '<button type="submit" class="single_add_to_cart_button button alt">Doneer</button>';
		$content .= '</form>';
	}
	return $content;
}
add_filter( 'the_content', 'chawa_add_charity_content' );