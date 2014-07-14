<form id="searchform" class="searchform" method="get" action="<?php echo get_home_url(); ?>">

    <div class="clearfix default_searchform">

        <input type="text" name="s" class="s" onblur="if (this.value == '') {this.value = '<?php _e('Search here...','bean'); ?>';}" 
        
        onfocus="if (this.value == '<?php _e('Search here...','bean'); ?>') {this.value = '';}" value="<?php _e('Search here...','bean'); ?>" />
        
        <button type="submit" class="button"><span><?php _e('Search here...', 'bean'); ?></span></button>

    </div><!-- END .clearfix defaul_searchform -->

    <?php do_action('radium_search_form'); ?>

</form><!-- END #searchform -->

