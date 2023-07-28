<?php

namespace App\Controllers;

class XcigenceTuanLe extends BaseController
{
    public function index(): string
    {
        helper('file');
        $file = APPPATH . '../Task (1).json';
        $data['report'] = json_decode(file_get_contents($file), true);
//        print_r($data);
        return view('xcigence_tuan_le', $data);
    }
}