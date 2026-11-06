<?php
require_once dirname(__DIR__) . '/app/auth.php';
require_admin();
require_post();
destroy_session();
redirect('login.php');
