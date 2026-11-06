<?php
require_once dirname(__DIR__) . '/app/auth.php';
require_admin();
redirect('editor.php');
