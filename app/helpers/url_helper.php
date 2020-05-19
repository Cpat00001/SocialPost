<?php

//redirect to URL
function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}