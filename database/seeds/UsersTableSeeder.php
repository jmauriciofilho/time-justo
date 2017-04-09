<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionAdmin = new \App\Models\Permission();
        $permissionAdmin->descriptions = "Permitido realizar qualquer alteração.";
        $permissionAdmin->save();

	    $permissionUser = new \App\Models\Permission();
	    $permissionUser->descriptions = "Usuário comum da aplicação.";
	    $permissionUser->save();

        $roleAdmin = new \App\Models\Role();
        $roleAdmin->name = "Administrador";
        $roleAdmin->save();

	    $roleUser = new \App\Models\Role();
	    $roleUser->name = "Usuário";
	    $roleUser->save();

	    $userAdmin = \App\Models\User::create([
		    'name' => 'João das Neves',
		    'email' => 'admin@admin.com',
		    'password' => bcrypt('default'),
	    ]);

	    $roleAdmin->permissions()->attach($permissionAdmin);
	    $roleUser->permissions()->attach($permissionUser);
	    $userAdmin->roles()->attach($roleAdmin);
    }
}
