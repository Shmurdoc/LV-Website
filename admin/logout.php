<?php
session_unset();
session_destroy();
header('Location: ' . url('/admin/login'));
exit;
