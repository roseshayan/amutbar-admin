<?php

require_once __DIR__ . '/includes/init.php';
admin_logout();
redirect(url_path('login.php'));
