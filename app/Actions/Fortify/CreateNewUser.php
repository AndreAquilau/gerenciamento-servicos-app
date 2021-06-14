<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {

            $empresa = Empresa::create([
                'fantasia' => $input['fantasia'],
                'razao_social' => $input['fantasia'],
                'created_at' => now(),
                'updated_at' => now(),
                'inscricao_estadual' => '',
                'cpf' => '',
                'cnpj' => '',
                'telefone' => '',
                'celular' => '',
                'bairro' => '',
                'rua' => '',
                'numero' => '',
                'cidade' => '',
                'estado' => '',
                'cep' => '',
                'email' => $input['email'],
            ]);

            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'empresa_id' => $empresa->id,
            ]), function (User $user) {

                $this->createTeam($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            //'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'name' => "Administração",
            'personal_team' => true,
        ]));
    }

}
