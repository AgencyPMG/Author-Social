<?php
/**
 * Admin area functionality for Author Social
 *
 * @author Christopher Davis <http://christopherdavis.me>
 * @package Author Social
 * @since 1.0
 */

/**
 * Container class for all the admin are functionality
 *
 * @since 1.0
 */
class CD_Author_Social_Admin
{
    /**
     * Social networks supported by this plugin
     *
     * @since 1.0
     * @access protected
     */
    protected $networks;

    /**
     * Nonce action name & and nonce field name
     *
     * @since 1.0
     * @access protected
     */
    protected $nonce = 'cd_auth_soc_nonce';

    /**
     * Constructor
     *
     * @since 1.0
     * @access public
     * @uses add_action
     * @uses add_filter
     */
    public function __construct()
    {
        add_action( 'show_user_profile', array( &$this, 'profile' ) );
        add_action( 'edit_user_profile', array( &$this, 'profile' ) );
        add_action( 'edit_user_profile_update', array( &$this, 'save' ) );
        add_action( 'personal_options_update', array( &$this, 'save' ) );
        add_filter( 'plugin_action_links_' . CD_AUTH_SOC_NAME, array( &$this, 'actions' ) );

        $networks = array(
            'facebook'    => __( 'Facebook', 'author-social' ),
            'twitter'     => __( 'Twitter', 'author-social' ),
            'youtube'     => __( 'Youtube', 'author-social' ),
            'linkedin'    => __( 'LinkedIn', 'author-social' ),
            'pinterest'   => __( 'Pinterest', 'author-social' ),
            'google_plus' => __( 'Google+', 'author-social' )
        );
        $this->networks = apply_filters( 'author_social_networks', $networks );
    }

    /**
     * Displaces the profile fields added by this plugin
     *
     * @since 1.0
     * @access public
     */
    public function profile( $user )
    {
        wp_nonce_field( $this->nonce, $this->nonce, false );
        ?>
            <h4><?php _e( 'Author Social Options', 'author-social' ); ?></h4>
            <table class="form-table">
                <?php
                foreach( $this->networks as $key => $label )
                {
                    $label = $this->label( $key, $label );
                    $field = $this->field( $key, $user->ID );
                    echo $this->form_row( $label, $field );
                }
                ?>
            </table>
        <?php
    }

    /**
     * Saves the data entered into the profile fields for this plugin
     *
     * @since 1.0
     * @access public
     * @uses wp_verify_nonce
     * @uses update_user_meta
     */
    public function save( $user_id )
    {
        if( ! isset( $_POST[$this->nonce] ) || ! wp_verify_nonce( $_POST[$this->nonce], $this->nonce ) )
        {
            return;
        }
        foreach( array_keys( $this->networks) as $n )
        {
            $id = $this->field_name( $n );
            if( isset( $_POST[$id] ) && $_POST[$id] )
            {
                $old = get_user_meta( $user_id, $id, true );
                if( $old )
                {
                    update_user_meta( $user_id, $id, esc_url( $_POST[$id] ), $old );
                }
                else
                {
                    add_user_meta( $user_id, $id, esc_url( $_POST[$id] ), true );
                }
            }
            else
            {
                delete_user_meta( $user_id, $id );
            }
        }
    }

    /**
     * Used to display a text field for the social network inputs
     * 
     * @since 1.0
     * @access protected
     */
    protected function field( $network, $user_id )
    {
        $name = $this->field_name( $network );
        $val = get_user_meta( $user_id, $name, true );
        return "<input type='text' name='{$name}' id='{$name}' class='regular-text' value='" . esc_url( $val ) . "' />";
    }

    /**
     * Make a <label>
     *
     * @since 1.0
     * @access protected
     */
    protected function label( $network, $label )
    {
        $id = $this->field_name( $network );
        return "<label for='{$id}'>{$label}</label>";
    }

    /**
     * Get a nicely prefixed field name from a social network
     *
     * @since 1.0
     * @access protected
     */
    protected function field_name( $network )
    {
        return sprintf(
            'cd_auth_soc_%s',
            esc_attr( $network )
        );
    }

    /**
     * Make a form table row
     *
     * @since 1.0
     * @access protected
     */
    protected function form_row( $label, $field )
    {
        return "<tr><th scope='row'>{$label}</th><td>{$field}</td></tr>";
    }

    
} // end class

new CD_Author_Social_Admin();
