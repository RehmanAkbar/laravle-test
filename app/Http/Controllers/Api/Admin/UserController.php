<?php

namespace App\Http\Controllers\Api\Admin;


use App\User;
use App\Mail\InviteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

	public function invite($email)
	{
        $url = URL::signedRoute('register', ['email' => $email]);

        Mail::to($email)->send(new InviteUser($url));

        return $url;
	}
}
