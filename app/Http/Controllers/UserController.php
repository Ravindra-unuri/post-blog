<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Jobs\MailSentJob;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

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

        MailSentJob::dispatch($user);

        return $this->sendSuccessResponse(__('User Registered Successfully'), $user);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && $request->password == $user->password) {
            $token = $user->createToken($request->email)->plainTextToken;
            return $this->sendSuccessResponse(__('User Loggedin Successfully'), null, $token);
        }

        return $this->sendFailedResponse(__('Unauthorized User'));
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);

        if (!$user) {
            return $this->sendNotFoundResponse(__('User not found'));
        }

        $validatedData = $request->validated();

        $user->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
        ]);

        return $this->sendSuccessResponse(__('User updated successfully'), $user);
    }


    public function updatePassword(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::find($userId);

        if (!$user) {
            return $this->sendFailedResponse(__('User not found'));
        }

        if ($user->password == $request->old_password) {
            $updated = $user->update([
                'password' => $request->input('password')
            ]);
        } else {
            return $this->sendFailedResponse(__('Old Password dose not match'));
        }

        if ($updated) {
            return $this->sendSuccessResponse(__('Password Updated Successfully'), $updated);
        } else {
            return $this->sendFailedResponse(__('Failed to Update Password'));
        }
    }

    public function MyProfile()
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
