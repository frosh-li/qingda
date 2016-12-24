<?php
class TestCommand extends CConsoleCommand
{
    public function init()
    {
        echo 'init';
    }

    public function actionIndex(){
        echo 'index';
    }
}
