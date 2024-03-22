<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Jobs\MailSentJob;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class UserController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ResponseTrait;


    public function Registration(RegistrationRequest $request)
    {
        if (User::where('email', $request->input('email'))->exists()) {

            return $this->sendConflictResponse(__('The requested user alreay registered'));
        }

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);
        // dispatch(new MailSentJob($user->email)); 
        MailSentJob::dispatch($user);
        // MailSentJob::dispatch($user, 'sendmail', 'MailSent_worker'); 

        return $this->sendSuccessResponse(__('User Registered Successfully'), $user);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && $request->password == $user->password) {
            $token = $user->createToken($request->email)->plainTextToken;
            return $this->sendSuccessResponse(__('User Loggedin Successfully'), null, $token);
        }
        return response([
            'messsage' => 'Unauthorized User',
            'status' => 'failed'
        ], 401);
    }

    // public function updateUser(Request $request, $id)
    // {
    // $userId = auth()->user()->id;
    // $user = User::find($id);

    // $user->update([
    //     'first_name' => $request->input('first_name'),
    //     'last_name' => $request->input('last_name'),
    //     'email' => $request->input('email'),
    // ]);
    // if ($updated) {
    //     return $this->sendSuccessResponse(__('User Updated Successfully'), $updated);
    // } else {
    //     return $this->sendFailedResponse(__('Failed to update user'));
    // }
    // }

    public function updateUser(Request $request)
    {
        $id=auth()->user()->id;
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'
        ]);

        $user->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function updatePassword(Request $request)
    {


        $userId = auth()->user()->id;
        $updated = User::where('id', $userId)
            ->update([
                'password' => $request->input('password')
            ]);
        if ($updated) {
            return $this->sendSuccessResponse(__('Password Updated Successfully'), $updated);
        } else {
            return $this->sendFailedResponse(__('Failed to Update Password'));
        }
    }

    public function profile()
    {
        $data = auth()->user();
        return $this->sendSuccessResponse(__('User Profile'), $data);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendSuccessResponse(__('User Loggedout Successfully'));
    }

    public function get()
    {
        $id = auth()->user()->id;
        $data = User::where('id', $id)->select('id', 'first_name')->first();
        return response($data, 200);
    }
}
