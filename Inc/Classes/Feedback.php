<?php
namespace JLTIUSL\Inc\Classes;

use JLTIUSL\Inc\Classes\Notifications\Base\User_Data;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feedback
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */
class Feedback {

    use User_Data;

	/**
	 * Construct Method
	 *
	 * @return void
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
    public function __construct(){
        add_action( 'admin_enqueue_scripts' , [ $this,'admin_suvery_scripts'] );
        add_action( 'admin_footer' , [ $this , 'deactivation_footer' ] );
        add_action( 'wp_ajax_jltiusl_deactivation_survey', array( $this, 'jltiusl_deactivation_survey' ) );

    }


    public function proceed(){

        global $current_screen;
        if(
            isset($current_screen->parent_file)
            && $current_screen->parent_file == 'plugins.php'
            && isset($current_screen->id)
            && $current_screen->id == 'plugins'
        ){
           return true;
        }
        return false;

    }

    public function admin_suvery_scripts($handle){
        if('plugins.php' === $handle){
            wp_enqueue_style( 'jltiusl-survey' , JLTIUSL_ASSETS . 'css/plugin-survey.css' );
        }
    }

    /**
     * Deactivation Survey
     */
    public function jltiusl_deactivation_survey(){
        check_ajax_referer( 'jltiusl_deactivation_nonce' );

        $deactivation_reason  = ! empty( $_POST['deactivation_reason'] ) ? sanitize_text_field( wp_unslash( $_POST['deactivation_reason'] ) ) : '';

        if( empty( $deactivation_reason )){
            return;
        }

        $email = get_bloginfo( 'admin_email' );
        $author_obj = get_user_by( 'email', $email );
        $user_id    = $author_obj->ID;
        $full_name  = $author_obj->display_name;

        $response = $this->get_collect_data( $user_id, array(
            'first_name'              => $full_name,
            'email'                   => $email,
            'deactivation_reason'     => $deactivation_reason,
        ) );

        return $response;
    }


    public function get_survey_questions(){

        return [
			'no_longer_needed' => [
				'title' => esc_html__( 'I no longer need the plugin', 'increase-maximum-upload-file-size-limit' ),
				'input_placeholder' => '',
			],
			'found_a_better_plugin' => [
				'title' => esc_html__( 'I found a better plugin', 'increase-maximum-upload-file-size-limit' ),
				'input_placeholder' => esc_html__( 'Please share which plugin', 'increase-maximum-upload-file-size-limit' ),
			],
			'couldnt_get_the_plugin_to_work' => [
				'title' => esc_html__( 'I couldn\'t get the plugin to work', 'increase-maximum-upload-file-size-limit' ),
				'input_placeholder' => '',
			],
			'temporary_deactivation' => [
				'title' => esc_html__( 'It\'s a temporary deactivation', 'increase-maximum-upload-file-size-limit' ),
				'input_placeholder' => '',
			],
			'jltiusl_pro' => [
				'title' => sprintf( esc_html__( 'I have %1$s Pro', 'increase-maximum-upload-file-size-limit' ), JLTIUSL ),
				'input_placeholder' => '',
				'alert' => sprintf( esc_html__( 'Wait! Don\'t deactivate %1$s. You have to activate both %1$s and %1$s Pro in order for the plugin to work.', 'increase-maximum-upload-file-size-limit' ), JLTIUSL ),
			],
			'need_better_design' => [
				'title' => esc_html__( 'I need better design and presets', 'increase-maximum-upload-file-size-limit' ),
				'input_placeholder' => esc_html__( 'Let us know your thoughts', 'increase-maximum-upload-file-size-limit' ),
			],
            'other' => [
				'title' => esc_html__( 'Other', 'increase-maximum-upload-file-size-limit' ),
				'input_placeholder' => esc_html__( 'Please share the reason', 'increase-maximum-upload-file-size-limit' ),
			],
		];
    }


        /**
         * Deactivation Footer
         */
        public function deactivation_footer(){

        if(!$this->proceed()){
            return;
        }

        ?>
        <div class="jltiusl-deactivate-survey-overlay" id="jltiusl-deactivate-survey-overlay"></div>
        <div class="jltiusl-deactivate-survey-modal" id="jltiusl-deactivate-survey-modal">
            <header>
                <div class="jltiusl-deactivate-survey-header">
                    <img src="<?php echo esc_url(JLTIUSL_IMAGES . '/menu-icon.png'); ?>" />
                    <h3><?php echo wp_sprintf( '%1$s %2$s', JLTIUSL, __( '- Feedback', 'increase-maximum-upload-file-size-limit' ),  ); ?></h3>
                </div>
            </header>
            <div class="jltiusl-deactivate-info">
                <?php echo wp_sprintf( '%1$s %2$s', __( 'If you have a moment, please share why you are deactivating', 'increase-maximum-upload-file-size-limit' ), JLTIUSL ); ?>
            </div>
            <div class="jltiusl-deactivate-content-wrapper">
                <form action="#" class="jltiusl-deactivate-form-wrapper">
                    <?php foreach($this->get_survey_questions() as $reason_key => $reason){ ?>
                        <div class="jltiusl-deactivate-input-wrapper">
                            <input id="jltiusl-deactivate-feedback-<?php echo esc_attr($reason_key); ?>" class="jltiusl-deactivate-feedback-dialog-input" type="radio" name="reason_key" value="<?php echo $reason_key; ?>">
                            <label for="jltiusl-deactivate-feedback-<?php echo esc_attr($reason_key); ?>" class="jltiusl-deactivate-feedback-dialog-label"><?php echo esc_html( $reason['title'] ); ?></label>
							<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
								<input class="jltiusl-deactivate-feedback-text" type="text" name="reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>" />
							<?php endif; ?>
                        </div>
                    <?php } ?>
                    <div class="jltiusl-deactivate-footer">
                        <button id="jltiusl-dialog-lightbox-submit" class="jltiusl-dialog-lightbox-submit"><?php echo esc_html__( 'Submit &amp; Deactivate', 'increase-maximum-upload-file-size-limit' ); ?></button>
                        <button id="jltiusl-dialog-lightbox-skip" class="jltiusl-dialog-lightbox-skip"><?php echo esc_html__( 'Skip & Deactivate', 'increase-maximum-upload-file-size-limit' ); ?></button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            var deactivate_url = '#';

            jQuery(document).on('click', '#deactivate-increase-maximum-upload-file-size-limit', function(e) {
                e.preventDefault();
                deactivate_url = e.target.href;
                jQuery('#jltiusl-deactivate-survey-overlay').addClass('jltiusl-deactivate-survey-is-visible');
                jQuery('#jltiusl-deactivate-survey-modal').addClass('jltiusl-deactivate-survey-is-visible');
            });

            jQuery('#jltiusl-dialog-lightbox-skip').on('click', function (e) {
                e.preventDefault();
                window.location.replace(deactivate_url);
            });


            jQuery(document).on('click', '#jltiusl-dialog-lightbox-submit', async function(e) {
                e.preventDefault();

                jQuery('#jltiusl-dialog-lightbox-submit').addClass('jltiusl-loading');

                var $dialogModal = jQuery('.jltiusl-deactivate-input-wrapper'),
                    radioSelector = '.jltiusl-deactivate-feedback-dialog-input';
                $dialogModal.find(radioSelector).on('change', function () {
                    $dialogModal.attr('data-feedback-selected', jQuery(this).val());
                });
                $dialogModal.find(radioSelector + ':checked').trigger('change');


                // Reasons for deactivation
                var deactivation_reason = '';
                var reasonData = jQuery('.jltiusl-deactivate-form-wrapper').serializeArray();

                jQuery.each(reasonData, function (reason_index, reason_value) {
                    if ('reason_key' == reason_value.name && reason_value.value != '') {
                        const reason_input_id = '#jltiusl-deactivate-feedback-' + reason_value.value,
                            reason_title = jQuery(reason_input_id).siblings('label').text(),
                            reason_placeholder_input = jQuery(reason_input_id).siblings('input').val(),
                            format_title_with_key = reason_value.value + ' - '  + reason_placeholder_input,
                            format_title = reason_title + ' - '  + reason_placeholder_input;

                        deactivation_reason = reason_value.value;

                        if ('found_a_better_plugin' == reason_value.value ) {
                            deactivation_reason = format_title_with_key;
                        }

                        if ('need_better_design' == reason_value.value ) {
                            deactivation_reason = format_title_with_key;
                        }

                        if ('other' == reason_value.value) {
                            deactivation_reason = format_title_with_key;
                        }
                    }
                });

                await jQuery.ajax({
                        url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                        method: 'POST',
                        // crossDomain: true,
                        async: true,
                        // dataType: 'jsonp',
                        data: {
                            action: 'jltiusl_deactivation_survey',
                            _wpnonce: '<?php echo esc_js( wp_create_nonce( 'jltiusl_deactivation_nonce' ) ); ?>',
                            deactivation_reason: deactivation_reason
                        },
                        success:function(response){
                            window.location.replace(deactivate_url);
                        }
                });
                return true;
            });

            jQuery('#jltiusl-deactivate-survey-overlay').on('click', function () {
                jQuery('#jltiusl-deactivate-survey-overlay').removeClass('jltiusl-deactivate-survey-is-visible');
                jQuery('#jltiusl-deactivate-survey-modal').removeClass('jltiusl-deactivate-survey-is-visible');
            });
        </script>
        <?php
    }

}