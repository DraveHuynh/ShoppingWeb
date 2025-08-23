<?php


class LogoutController {
    public function index() {
        session_unset(); 
        header('Location: ?url=home');
    }
   
}
