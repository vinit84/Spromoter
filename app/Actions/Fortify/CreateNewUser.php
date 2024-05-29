<?php

namespace App\Actions\Fortify;

use App\Jobs\CreateStripeCustomerJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'agreement' => ['required', 'accepted'],
        ], [
            'agreement.accepted' => trans('You must agree to the terms and conditions.'),
        ])->validate();

        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'username' => $this->generateUsername($input['first_name'], $input['last_name']),
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            activity('users')
                ->causedBy($user)
                ->performedOn($user)
                ->log('Account created');

            DB::commit();

            return $user;
        }catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateUsername(string $first_name, string $last_name, $number = null): string
    {
        $username = strtolower($first_name[0] . $last_name);
        $username = preg_replace('/[^a-z0-9]/', '', $username);
        $username = preg_replace('/[0-9]/', '', $username);
        $username = substr($username, 0, 20);
        $username = $username . $number;

        if (User::where('username', $username)->exists()) {
            return $this->generateUsername($first_name, $last_name, rand(0, 9));
        }

        return $username;
    }
}
