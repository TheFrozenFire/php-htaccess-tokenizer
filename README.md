php-htaccess-tokenizer
======================

A PHP library for tokenizing apache configuration files.

Usage
-----

    <?php
    $data = file_get_contents(".htaccess");
    $tokens = ApacheConfig_Tokenizer::parse($data);
