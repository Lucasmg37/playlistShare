<?php


namespace App\Controller\Api;


use App\Controller\Controller;
use App\Integrations\YouTube;

class ConverterController extends Controller
{

    public function getAction($id)
    {
        $youtube = new YouTube();
        return $youtube->getListVideoYoutube($id);
    }

    public function downloadAllAction()
    {
        $youtube = new YouTube();
        return $youtube->downloadAll();
    }
}
