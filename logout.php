<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['eticookie']);
header("Location:./");