<?php

if (class_exists('\Leaf\Config')) {
    \Leaf\Config::addScript(function () {
        \Leaf\Config::attachView(\Leaf\BareUI::class, 'template');
    });
}
