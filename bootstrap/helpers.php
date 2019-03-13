<?php

/**
 * 将当前请求的路由名称转换为 CSS 类名称，允许我们针对某个页面做页面样式定制
 * @return mixed
 */
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}