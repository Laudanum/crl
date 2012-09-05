<<<<<<< HEAD
<?php
/**
 * Register data (called automatically).
 * 
 * @return type 
 */
function wpcf_fields_phone() {
    return array(
        'id' => 'wpcf-phone',
        'title' => __('Phone', 'wpcf'),
        'description' => __('Phone', 'wpcf'),
        'validate' => array('required'),
        'inherited_field_type' => 'textfield',
    );
=======
<?php
/**
 * Register data (called automatically).
 * 
 * @return type 
 */
function wpcf_fields_phone() {
    return array(
        'id' => 'wpcf-phone',
        'title' => __('Phone', 'wpcf'),
        'description' => __('Phone', 'wpcf'),
        'validate' => array('required'),
        'inherited_field_type' => 'textfield',
    );
>>>>>>> 8f0ec37444fdd5d736c0ea14f5f7af594014484a
}