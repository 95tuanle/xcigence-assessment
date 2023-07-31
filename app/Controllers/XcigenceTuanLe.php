<?php

namespace App\Controllers;

class XcigenceTuanLe extends BaseController
{
    public function report(): string
    {
        $file = APPPATH . '../Task (1).json';
        if (file_exists($file)) {
            $jsonContents = file_get_contents($file);
            $data['report'] = json_decode($jsonContents, true);
        } else {
            $data['report'] = null;
        }
        return view('xcigence_tuan_le', $data);
    }
}