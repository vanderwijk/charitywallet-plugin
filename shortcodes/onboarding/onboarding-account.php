<?php get_header(); ?>

<section id="primary" class="content-area">
    <main id="main" class="site-main">
        <div id="post-wrap">

        <?php // BEGIN Include ?>

<?php wp_enqueue_script( 'onboarding_account' ); ?>

<style>
    .entry-title { display: none; }
    .col-1,
    .col-2 {
        margin-bottom: 3%;
    }
    .col-2 {
        display: flex;
        justify-content: space-between;
    }
    .col-2 .col {
        width: 48%;
    }
    input[type="text"].parsley-error {
        border-color: #FE6C61;
    }
    ul.parsley-errors-list {
        list-style: none;
        margin: 0;
        color: #FE6C61;
        padding: 0;
    }
    ul.parsley-errors-list li {
        margin-bottom: -10px;
    }
    .gfield_required {
        display: none;
    }
    #step-3 .gform_wrapper div.validation_error {
        display: none;
    }
    #step-3 .gform_wrapper li.gfield.gfield_error {
        background-color: transparent;
        border: none;
    }
    #step-3 .gform_wrapper .field_description_below .gfield_description {
        padding-top: 0;
    }
    #step-3 .gform_wrapper .gfield_error .gfield_label,
    #step-3 .gform_wrapper .validation_message,
    #step-3 .gform_wrapper li.gfield_error div.ginput_complex.ginput_container label {
        color: #FE6C61;
    }
    #step-3 .gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]) {
        border-color: #FE6C61;
    }
    .entry-content p a.button {
        color: #fff;
    }
</style>

<div class="step" id="step-1">
    <h1><?php _e('Leuk dat je er bent!', 'chawa'); ?></h1>
    <p><?php _e('Ben je klaar voor het nieuwe doneren of wil je eerst even rondkijken?', 'chawa'); ?></p>
    <p><button class="next" id="next-2"><?php _e('Ik wil doneren', 'chawa'); ?></button></p>
    <p><a href="/charity/"><?php _e('Ik kijk nog even rond', 'chawa'); ?></a></p>
</div>

<div class="step" id="step-2" style="display: none;">
    <h1><?php _e('Account maken', 'chawa'); ?></h1>
    <p><?php _e('Goed idee om vanaf vandaag te gaan doneren zonder deurbel of handtekening!', 'chawa'); ?></p>
    <p><strong><?php _e('We gaan eerst een account voor je aanmaken.', 'chawa'); ?></strong></p>
    <p><button class="next" id="next-3"><?php _e('Next', 'chawa'); ?></button></p>
</div>

<div class="step" id="step-3" style="display: none;">
    <h1><?php _e('Account maken', 'chawa'); ?></h1>
    <?php echo do_shortcode( '[gravityform id=1 title=false description=false ajax=true tabindex=49]' ); ?>
</div>

<?php // END Include ?>

        </div>
    </main>
</section>

<?php get_footer(); ?>