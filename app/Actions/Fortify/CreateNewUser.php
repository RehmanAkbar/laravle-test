<?php

namespace App\Actions\Fortify;

use Image;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        if (!request()->hasValidSignature()) {
            abort(401);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'min:4', 'max:20', Rule::unique(User::class),],
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();


        $user = User::create([
            'name' => $input['name'],
            'user_name' => $input['user_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $this->saveProfileImage($user);
        $user->user_role = User::USER_ROLE;
        $user->save();
        return $user;
    }

    /**
     * Upload user
     * @param mixed $user
     * @return void
     * @throws BindingResolutionException
     */
    public function saveProfileImage($user)
    {

        $request = request();
        $originalImage = $request->file('avatar');
        $thumbnailImage = Image::make($originalImage);
        $thumbnailPath = public_path('thumbnail/');
        $originalPath = public_path('images/');
        $thumbnailImage->save($originalPath . time() . $originalImage->getClientOriginalName());
        $thumbnailImage->resize(256, 256);
        $thumbnailImage->save($thumbnailPath . time() . $originalImage->getClientOriginalName());

        $user->avatar = time() . $originalImage->getClientOriginalName();
        $user->save();
    }
}
