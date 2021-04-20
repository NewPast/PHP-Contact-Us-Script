<?php
include 'z-ad.php';
if ( !function_exists( 'np_is_amp' ) ) {
    if ( !function_exists( 'ad_head' ) ) {
        function ad_head() {}
    }
    if ( !function_exists( 'ad_body_open' ) ) {
        function ad_body_open() {}
    }
    if ( !function_exists( 'np_ad' ) ) {
        function np_ad() {}
    }
}