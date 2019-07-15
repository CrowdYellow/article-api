<?php

if ( !function_exists('user') ) {
    /**
     * @param null $driver
     * @return mixed
     */
    function user($driver = null)
    {
        if ( $driver ) {
            return app('auth')->guard($driver)->user();
        }
        return app('auth')->user();
    }
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return \Illuminate\Support\Str::limit($excerpt, $length);
}