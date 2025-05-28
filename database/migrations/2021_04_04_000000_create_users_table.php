<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned()->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('role_id')->unsigned()->nullable()->default(null);
            $table->boolean('is_superadmin')->default(false);
            $table->string('user_type')->default('staff_members');
            $table->boolean('login_enabled')->default(true);
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('phone')->nullable()->default(null);
            $table->string('profile_image')->nullable()->default(null);
            $table->string('address', 1000)->nullable()->default(null);
            $table->string('shipping_address', 1000)->nullable()->default(null);
            $table->string('email_verification_code', 50)->nullable()->default(null);

            $table->string('status')->default('enabled');
            $table->string('reset_code')->nullable()->default(null);

            $table->string('timezone', 50)->default('America/Los_Angeles');
            $table->string('date_format', 20)->default('d-m-Y');
            $table->string('date_picker_format', 20)->default('dd-mm-yyyy');
            $table->string('time_format', 20)->default('h:i a');

            $table->timestamps();
        });

        $company = Company::where('is_global', 0)->first();

        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin is allowed to manage everything of the app.',
        ]);

        $agentRole = Role::create([
            'name' => 'agent',
            'display_name' => 'Agent',
            'description' => 'Agent can manage campaigns and tasks assigned to them.',
        ]);

        $permissions = [
            'leads_view_all',
            'leads_create',
            'leads_delete',
            'campaigns_export',
            'call_managers_view',
            'sales_view',
            'sales_view_all',
            'sales_create',
            'sales_edit',
            'sales_delete',
        ];

        foreach ($permissions as $permissionName) {
            $permission = \App\Models\Permission::where('name', $permissionName)->first();
            if ($permission) {
            $agentRole->permissions()->attach($permission->id);
            }
        }

        $adminId = DB::table('users')->insertGetId([
            'company_id' => $company->id,
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('contrasena'),
            'user_type' => 'staff_members',
            'role_id' => $adminRole->id,
        ]);

        $admin = User::where('id', $adminId)->first();

        $admin->assignRole($adminRole->name);

        $company->admin_id = $adminId;
        $company->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
