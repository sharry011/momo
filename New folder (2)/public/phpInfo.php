<?php


try {
        echo '<pre>';print_r(config('cache.default'));echo 'out';
    } catch (\Exception $e) {
        return 'Redis error: ' . $e->getMessage();
    }
