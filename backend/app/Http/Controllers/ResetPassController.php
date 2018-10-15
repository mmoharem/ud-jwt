<?php

namespace App\Http\Controllers;
// use App\Mail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;


class ResetPassController extends Controller
{
    public function sendEmail(Request $request) {
        if(!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }
        $this->send($request->email);
        return $this->successResponse();

    }

    public function send($email) {
        $token = $this->createToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    public function createToken($email) {
        $oldToken = DB::table('password_resets')->where('email', $email)->first();
        if($oldToken) {
            return $oldToken;
        }

        $token = str_random(60);
        $this->saveToken($token, $email);
        return $token;
    }

    public function saveToken($token, $email) {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email) {
        return !!User::where('email', $email)->first();
        // use !! to return true or false
    }

    public function failedResponse() {
        return response()->json([
            'error' => 'Email does\'t found on database'
        ], Response::HTTP_NOT_FOUND);
        
    }

    public function successResponse() {
        return response()->json([
            'data' => 'Reset Email send successfuly'
        ], Response::HTTP_OK);
        
    }
}
