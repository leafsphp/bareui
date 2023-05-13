<?php

if (class_exists('\Leaf\App')) {
    app()->attach(function () {
        \Leaf\View::attach(\Leaf\BareUI::class, 'template');
    });
}
