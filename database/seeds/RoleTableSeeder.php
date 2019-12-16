<?php

use barrilete\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Admin Role */
        $role = new Role();
        $role->name = 'admin';
        $role->redirectTo = '/dashboard';
        $role->description = 'Administrator';
        $role->save();
        /** Editor Role */
        $role = new Role();
        $role->name = 'editor';
        $role->redirectTo = '/dashboard';
        $role->description = 'Editor';
        $role->save();
        /** User Role */
        $role = new Role();
        $role->name = 'user';
        $role->redirectTo = '/';
        $role->description = 'User';
        $role->save();
    }
}
