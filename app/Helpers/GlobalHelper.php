<?php

/* bật tab sidebar*/

if (!function_exists('setSidebar')) {
    function setSidebar(array $routes): ?String
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return "mm-active";
            }
        }
        return null;
    }
}
