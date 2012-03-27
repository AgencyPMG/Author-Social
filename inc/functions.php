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
        sanitize_title_with_dashes( $network )
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
