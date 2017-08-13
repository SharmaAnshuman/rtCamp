<?php
session_start();
echo $_SESSION['fbfname'];
echo "<br><br><br><br><br><br><br><br><br>";
echo $_SESSION['fblname'];
echo "<br><br><br><br><br><br><br><br><br>";
echo $_SESSION['fbemail'];
echo "<br><br><br><br><br><br><br><br><br>";
echo $_SESSION['fbpic'];
echo "<br><br><br><br><br><br><br><br><br>";
echo $_SESSION['token'];
echo "<br><br><br><br><br><br><br><br><br>";
echo print_r($_SESSION['fbalbums']);