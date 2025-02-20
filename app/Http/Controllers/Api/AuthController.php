<?php

namespace App\Http\Controllers\Api;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError(errors: $validator->errors(), code: 422);
    //     }

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'role_id' => Roles::where('name', 'pelapor')->first()?->id ?? 3,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));

    //     $result = [
    //         'id' => $user->id,
    //         'name' => $user->name,
    //         'email' => $user->email,
    //         'created_at' => $user->created_at,
    //     ];

    //     return $this->sendResponse(
    //         data: $result,
    //         message: 'Registrasi akun berhasil, silahkan cek email untuk verifikasi akun.',
    //         code: 201,
    //     );
    // }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(errors: $validator->errors(), code: 422);
        }

        /** @var User $user */
        $user = User::query()->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->sendError(errors: ['email' => ['Email atau password salah.']], code: 422);
        }

        $token = $user->createToken('Login via API')->plainTextToken;

        $result = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return $this->sendResponse($result, 'Berhasil masuk ke akun.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse(message: 'Berhasil keluar dari akun.');
    }

    // public function reset(Request $request)
    // {
    //     $request->validate([
    //         'email' => ['required', 'email'],
    //     ]);

    //     // We will send the password reset link to this user. Once we have attempted
    //     // to send the link, we will examine the response then see the message we
    //     // need to show to the user. Finally, we'll send out a proper response.
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     if ($status == Password::RESET_LINK_SENT) {
    //         return $this->sendResponse(
    //             message: 'Tautan reset password telah dikirimkan ke email Anda.'
    //         );
    //     }

    //     return $this->sendResponse(
    //         message: 'Email yang Anda masukkan tidak terdaftar atau terjadi kesalahan.'
    //     );
    // }

    // public function resend(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     $user = User::where('email', $request->input('email'))->first();

    //     if ($user->hasVerifiedEmail()) {
    //         return $this->sendResponse(
    //             message: 'Email sudah diverifikasi'
    //         );
    //     }

    //     $user->sendEmailVerificationNotification();

    //     return $this->sendResponse(
    //         message: 'Tautan verifikasi telah dikirim ulang ke email Anda'
    //     );
    // }
}
