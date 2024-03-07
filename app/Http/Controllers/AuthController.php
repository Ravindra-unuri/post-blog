<?php

// namespace App\Http\Controllers;

// use App\Http\Requests\RegistrationRequest;
// use App\Models\User;
// use App\Traits\ResponseTrait;
// use Illuminate\Http\Request;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Bus\DispatchesJobs;
// use Illuminate\Foundation\Validation\ValidatesRequests;
// use Illuminate\Routing\Controller as BaseController;


// class AuthController extends Controller
// {
//     use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
//     use ResponseTrait;


//     public function Registration(RegistrationRequest $request)
//     {
//         if (User::where('email', $request->input('email'))->exists()) {

//             return $this->sendConflictResponse(__('The requested user alreay registered'));
//         }

//         $user = User::create([
//             'first_name' => $request->input('first_name'),
//             'last_name' => $request->input('last_name'),
//             'email' => $request->input('email'),
//             'password' => $request->input('password'),
//         ]);
//         // dispatch(new MailSentJob($user, 'sendmail', 'MailSent_worker'));
//         return $this->sendSuccessResponse(__('User Registered Successfully'), $user);
//     }

//     public function login(Request $request)
//     {
//         $user = User::where('email', $request->email)->first();
//         if ($user && $request->password == $user->password) {
//             $token = $user->createToken($request->email)->plainTextToken;
//             return $this->sendSuccessResponse(__('User Loggedin Successfully'), null, $token);
//         }
//         return response([
//             'messsage' => 'Unauthorized User',
//             'status' => 'failed'
//         ], 401);
//     }

//     public function profile()
//     {
//         $data = auth()->user();
//         return $this->sendSuccessResponse(__('User Profile'), $data);
//     }

//     public function logout()
//     {
//         auth()->user()->tokens()->delete();
//         return $this->sendSuccessResponse(__('User Loggedout Successfully'));
//     }
// }
