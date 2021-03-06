<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function hook_bootstrap() {
    
    $CI =& get_instance();
    
    # add css
    $CI->template->add_css('bootstrap/css/bootstrap.css');
    $CI->template->add_css('css/style.css');
    $CI->template->add_css('css/calendrical.css');
    
    # add js
    $CI->template->add_js('js/jquery-2.0.3.min.js');
    $CI->template->add_js('bootstrap/js/bootstrap.min.js');
    $CI->template->add_js('js/jquery.calendrical.js');
    
    # add to the template
    $CI->template->write_view('navbar_sign_in', 'users/navbar-sign-in');
    $CI->template->write_view('navbar_signed_in', 'users/navbar-signed-in');
    $CI->template->write('flash', CI()->flash);
    
    $current_theme = $CI->_auth->get_user_theme($CI->auth->get_user_id());
    
    if ($current_theme == 0) {
        $CI->template->write('theme', 'navbar-inverse');
    } else {
        $CI->template->write('theme', '');
    }
    
}