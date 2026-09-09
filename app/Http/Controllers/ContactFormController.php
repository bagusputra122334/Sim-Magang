<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactFormController extends Controller
{
    public function send(Request $request)
    {
        return app(ContactController::class)->send($request);
    }
}

