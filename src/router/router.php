<?php

use Src\Controllers\ProblemaUnoController;
use Src\Controllers\ProblemaDosController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     switch($_SERVER['REQUEST_URI']){
          case '/problema-uno':
               ProblemaUnoController::verifyFile($_FILES['file']);
               break;
          case '/problema-dos':
               ProblemaDosController::verifyFile($_FILES['file']);
               break;
          case '/' : 
               echo 'Method not allowed'; break;
      }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   switch($_SERVER['REQUEST_URI']){
     case '/problema-uno':
            include __DIR__.'/../views/problemaUno.php'; break;
     case '/problema-dos':
          include __DIR__.'/../views/problemaDos.php'; break;
     case '/' : 
          include __DIR__.'/../views/index.php'; break;
     default: 
        include __DIR__.'/../views/index.php';
   }
}