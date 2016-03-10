<?php
/*
Plugin Name:	FSS Disable Saving NF Submissions
Description:	Disables saving Ninja Forms submissions on the website.
Author:			Flying Seal Systems, LLC
Version:		1.0
*/

function fss_disable_saving_nf_subs( $save, $form_id ) {
  $save = false;
  return $save;
}
add_filter( 'ninja_forms_save_submission', 'fss_disable_saving_nf_subs', 10, 2 );