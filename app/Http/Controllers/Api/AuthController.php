<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function signUp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'contact_no' => ['required', 'string', 'max:20', Rule::unique('users', 'contact_no')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $validated = collect($validator->validated())
            ->except(['password'])
            ->merge([
                'password' => Hash::make($request->input('password')),
                'otp' => config('app.demo_mode') ? '123456' : $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ])
            ->all();

        $user = User::create($validated);

        if (! config('app.demo_mode')) {
            Mail::to($user->email)->sendNow(new OtpMail($user, $otp));
        }

        $user->load('media');

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact_no' => $user->contact_no,
                    'picture' => $user->getFirstMediaUrl('picture'),
                ],
                'otp' => config('app.demo_mode') ? '123456' : $otp,
            ],
            'message' => __('general.otp_sent'),
        ], Response::HTTP_OK);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->input('email'))
            ->where('otp', $request->input('otp'))
            ->where('otp_expires_at', '>', now())
            ->first();

        if (! $user) {
            return response()->json([
                'message' => __('general.otp_invalid'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        $user->markEmailAsVerified();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->load('media');

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact_no' => $user->contact_no,
                    'picture' => $user->getFirstMediaUrl('picture'),
                ],
                'token' => $token,
            ],
            'message' => __('general.otp_verified'),
        ], Response::HTTP_OK);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::where('email', $request->input('email'))->firstOrFail();

        $user->fill([
            'otp' => config('app.demo_mode') ? '123456' : $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ])
            ->save();

        if (! config('app.demo_mode')) {
            Mail::to($user->email)->sendNow(new OtpMail($user, $otp));
        }

        return response()->json([
            'data' => [
                'otp' => config('app.demo_mode') ? '123456' : $otp,
            ],
            'message' => __('general.otp_sent'),
        ], Response::HTTP_OK);
    }

    public function signIn(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->load('media');

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact_no' => $user->contact_no,
                    'picture' => $user->getFirstMediaUrl('picture'),
                ],
                'token' => $token,
            ],
        ], Response::HTTP_OK);
    }

    public function getProfile(Request $request): UserResource
    {
        $user = $request->user();

        $user->load([
            'media',
        ]);

        return new UserResource($user);
    }

    public function updateProfile(Request $request): JsonResponse|UserResource
    {
        $user = $request->user();

        $rules = [
            'name' => ['sometimes', 'required', 'string', 'min:2', 'max:255'],
            'contact_no' => ['required', 'string', 'max:20', Rule::unique('users', 'contact_no')->ignore($user->id)],
            'picture' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = collect($validator->validated())
            ->except(['password', 'picture'])
            ->when($request->filled('password'), function (Collection $collection) use ($request) {
                $collection->push(['password' => Hash::make($request->input('password'))]);
            })
            ->all();

        $user->fill($validated)
            ->save();

        if ($request->hasFile('picture')) {
            $user->addMediaFromRequest('picture')
                ->toMediaCollection('picture');
        }

        $user->load(['media']);

        return new UserResource($user);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->input('email'))->firstOrFail();

        $user->fill([
            'password' => Hash::make($request->input('password')),
        ])
            ->save();

        return response()->json([

            'message' => __('general.password_changed'),
        ], Response::HTTP_OK);
    }
}
