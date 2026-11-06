<?php
require_once __DIR__ . '/app/bootstrap.php';
security_headers(true);
redirect('admin/login.php');