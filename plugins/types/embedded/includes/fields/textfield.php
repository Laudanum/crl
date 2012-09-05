<<<<<<< HEAD
<?php
/**
 * Register data (called automatically).
 * 
 * @return type 
 */
function wpcf_fields_textfield() {
    return array(
        'id' => 'wpcf-texfield',
        'title' => __('Single line', 'wpcf'),
        'description' => __('Texfield', 'wpcf'),
        'validate' => array('required'),
    );
=======
<?php
/**
 * Register data (called automatically).
 * 
 * @return type 
 */
function wpcf_fields_textfield() {
    return array(
        'id' => 'wpcf-texfield',
        'title' => __('Single line', 'wpcf'),
        'description' => __('Texfield', 'wpcf'),
        'validate' => array('required'),
    );
>>>>>>> 8f0ec37444fdd5d736c0ea14f5f7af594014484a
}