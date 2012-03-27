<?php
/**
 * Returns a name/key for a given network.  Used internally to fetch usermeta
 * keys and create field names/ids
 *
 * @since 1.0
 */
function cd_auth_soc_social_id( $network )
{
    return sprintf(
        'cd_auth_soc_%s',
        preg_replace( '/[^A-Za-z0-9_-]/', '-', $network )
    );
}


/**
 * Fetch the array of social networks that this plugin supports
 *
 * @since 1.0
 * @return array network_id => network label
 */
function cd_auth_soc_networks()
{
    $networks = array(
        'facebook'      => __( 'Facebook', 'author-social' ),
        'twitter'       => __( 'Twitter', 'author-social' ),
        'youtube'       => __( 'Youtube','author-social' ),
        'linkedin'      => __( 'LinkedIn', 'author-social' ),
        'pinterest'     => __( 'Pinterest', 'author-social' ),
        'google_plus'   => __( 'Google+', 'author-social' )
    );
    return apply_filters( 'author_social_networks', $networks );
}


/**
 * Fetch the social network URI for a given user
 *
 * @since 1.0
 * @uses cd_auth_soc_social_id
 * @uses get_user_meta
 * @param string $network The social network key
 * @param int $user_id The user id
 * @return string The URL for the user's social network
 */
function cd_auth_soc_get_network( $network, $user_id )
{
    $key = cd_auth_soc_social_id( $network );
    return get_user_meta( $user_id, $key, true );
}


/**
 * Fetches an associative array of social links for a given author
 *
 * @since 1.0
 * @uses cd_auth_soc_networks
 * @uses cd_auth_soc_get_network
 * @param int $user_id The ID of the user we wish to fetch
 * @return array Network name as the key and user's URL as the value
 */
function get_the_author_social( $user_id )
{
    $networks = array_keys( cd_auth_soc_networks() );
    $rv = array();
    foreach( $networks as $n )
    {
        if( $uri = cd_auth_soc_get_network( $n, $user_id ) )
        {
            $rv[$n] = $uri;
        }
    }
    return $rv;
}


/**
 * Main API entry point for this plugin.  Fetches the display of Social Links
 * (with icons as the anchor images) for display.
 * 
 * $args allows you to customize the output of this function. The defaults:
 *      `base_url` - Where to find the images. Default:  this plugins folder + '/images/'
 *      `use_style` - Echo out the CSS on the page? Default: true
 *
 * @since 1.0
 * @param int $user_id 
 * @param array $args
 * @uses get_the_author_social
 */
function the_author_social( $user_id, $args=array() )
{
    $args = wp_parse_args(
        array(
            'base_url'  => CD_AUTH_SOC_URL,
            'use_style' => true
        ),
        $args
    );
    $networks = cd_auth_soc_networks();
    if( empty( $networks ) ) return;
    echo '<div class="author-social-container">';
    foreach( get_the_author_social( $user_id ) as $n => $uri )
    {
        $img = apply_filters(
            "author_social_image_{$n}",
            trailingslashit( $args['base_url'] ) . 'images/' . $n . '.png'
        );
        $alt = isset( $networks[$n] ) ? esc_attr( $networks[$n] ) : '';
        $rel = apply_filters( "author_social_rel_{$n}", 'nofollow' );
        echo '<a href="' . esc_url( $uri ) . '" class="' . esc_attr( $n ) . '" rel="' . esc_attr( $rel ) . '">';
        echo '<img src="' . esc_url( $img ) . '" title="' . $alt . '" alt="' . $alt . '" />';
        echo '</a>';
    }
    echo '</div>';
    if( $args['use_style'] )
    {
        echo '<style type="text/css">.author-social-container a { display:inline-block; margin-right:5px; }</style>';
    }
}

// rel="me" on google plus
add_filter( 'author_social_rel_google_plus', 'cd_auth_soc_gplus_rel' );
function cd_auth_soc_gplus_rel( $rel )
{
    return $rel . ' me';
}
