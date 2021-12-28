<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{

    public function SendEmail(Request $request){
        return [
            'data' => [
                'status'    =>  200
            ]
        ];
    }
}
