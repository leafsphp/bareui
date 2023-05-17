<?php

if (class_exists('\Leaf\Config')) {
    \Leaf\Config::addScript(function () {
        \Leaf\View::attach(\Leaf\BareUI::class, 'template');
    });
}
