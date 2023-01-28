<?php

function responseOk(string $msg, array $data=array()) :array
{
    return ['success' => true, 'message' => '', 'data' => $data];
}

function responseFail(string $msg, array $errors=array()) :array
{
    return ['success' => false, 'message' => $msg, 'errors' => $errors];
}