<?php

use MatthiasMullie\Minify\JS;
use MatthiasMullie\Minify\CSS;

if (strpos(url(), "localhost")) {
    /**
     * CSS
     */
    $minCss = new CSS();
    $minCss->add(__DIR__ . "/../../shared/styles/styles.css");
    $minCss->add(__DIR__ . "/../../shared/styles/boot.css");

    //theme CSS
    $cssDir = scandir(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/css");
    foreach ($cssDir as $css) {
        $cssFile = __DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/css/{$css}";
        if (is_file($cssFile) && pathinfo($cssFile)['extension'] == 'css') {
            $minCss->add($cssFile);
        }
    }

    //MINIFY CSS
    $minCss->minify(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/style.css");



    /**
     *  JS
     */
    $minJs = new JS();
    $minJs->add(__DIR__ . "/../../shared/scripts/jquery.min.js");
    $minJs->add(__DIR__ . "/../../shared/scripts/jquery.form.js");
    $minJs->add(__DIR__ . "/../../shared/scripts/jquery-ui.js");

    //theme JS
    $jsDir = scandir(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/js");
    foreach ($jsDir as $js) {
        $jsFile = __DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/js/{$js}";
        if (is_file($jsFile) && pathinfo($jsFile)['extension'] == 'js') {
            $minJs->add($jsFile);
        }
    }

    //MINIFY JS
    $minJs->minify(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/scripts.js");
}