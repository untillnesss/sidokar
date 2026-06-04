<?php

namespace App\Controllers;

use App\Models\UserModel;

class Test extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $data = $model->findAll();

        echo "<pre>";
        print_r($data);
    }
}
