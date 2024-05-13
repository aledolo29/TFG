<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (isset($_SESSION['empl_Usuario']) && isset($_SESSION['empl_Password'])) {
    echo true;
} else {
    echo false;
}