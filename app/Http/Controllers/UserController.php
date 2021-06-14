<?php

namespace App\Http\Controllers;


use App\Models\Team;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Rules\Role;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Redirect;

class UserController extends Controller
{

    //use PasswordValidationRules;
    public function view($view, $data = [])
    {
        return Inertia::render($view, $data);
    }

     /**
     *
     * @param  \Illuminate\Http\Request  $request () -> request: {empresa_id: "3", user_id: "2"}
     * @return \Illuminate\Http\Response
     */
    public function renderPage(Request $query)
    {

        $usuarios = $this->findAllByEmpresa($query["empresa_id"]);

        return $this->view("Usuario/Index", ["query" => $query, "usuariosAll" => $usuarios]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $users = User::all();

        return ["users" => $users];
    }


    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body["query"];

        DB::transaction(function () use ($body) {
            $userCreate = $body["user"];

            if($userCreate['password'] === $userCreate['password_confirm'])
            {
                return tap(User::create([
                    'name' => $userCreate['name'],
                    'email' => $userCreate['email'],
                    'current_team_id' => $userCreate['current_team_id'],
                    'password' =>Hash::make($userCreate['password']),
                    'empresa_id' => $userCreate['empresa_id'],
                ]), function (User $user) {
                    $this->createTeamUser($user);
                });

            }
        });

        $usuarios = $this->findAllByEmpresa($query["empresa_id"]);

        return $this->view("Usuario/Index", ["query" => $query, "usuariosAll" => $usuarios]);
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
            'name' => "Administração",
            'personal_team' => true,
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function findAllByEmpresa($empresa_id) {

        $body = json_decode(file_get_contents('php://input'), true);

        $users = DB::table('users')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->select('users.*')
        ->where('empresas.id', '=', $empresa_id)
        ->get();

        return $users;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);;

        return ['user' => $user];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function updateInfo()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $input = $body['input'];
        $query= $body['query'];
        $user = $body['user'];
        $error = false;

        // var_dump($input);
        //var_dump($user);

        if ($input['name'] !== $user["name"]) {
            User::where("name", "=", $user["name"])
            ->where("email", "=", $user["email"])
            ->where("id", "=",  $user["id"])
            ->update([
                "name" => $input["name"],
            ]);
        }

        if(
            $input['email'] && $input['name'] && $input['password']
        ) {

            if(Hash::check($input['current_password'], $user["password"])){
                User::where("name", "=", $user["name"])
                ->where("email", "=", $user["email"])
                ->where("id", "=",  $user["id"])->update([
                    'password' => Hash::make($input['password']),
                ]);
            } else {
                $error = true;
            }
        }

        if(
            $input['inativo'] === true || $input['inativo'] === false
        ) {
            User::where("name", "=", $user["name"])
            ->where("email", "=", $user["email"])
            ->where("id", "=",  $user["id"])->update([
                'inativo' => $input['inativo'],
            ]);
        }

        $usuarios = $this->findAllByEmpresa($query["empresa_id"]);

        return $this->view("Usuario/Index", ["query" => $query, "usuariosAll" => $usuarios, "error" => $error]);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $body = json_decode(file_get_contents('php://input'), true);

        //busca a empresa do usuario que esta tentando deletar um usuario em sua empresa
        $user_id_empresa = User::findOrFail(["id" => $body["user_id"]]);

        //verificar se os usuarios sao da mesma empresa
        $user = User::where('id', '=',  $id)
        ->where('empresa_id', '=',  $user_id_empresa[0]->empresa_id)
        ->delete();

        return ["user" => $user];
    }

    public function addTeamUser($user, $team, string $email, string $role = null)
    {

    }

    public function createTeamUser(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => "Administração",
            'personal_team' => true,
        ]));
    }

}
