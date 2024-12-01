<?php

if (class_exists('\Leaf\Config')) {
    \Leaf\Config::addScript(function () {
        \Leaf\App::attachView(\Leaf\BareUI::class, 'template');
    });
}
