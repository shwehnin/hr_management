<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission_name')->nullable();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('roles_permissions')->insert([
            ['permission_name' => 'Administrator'],
            ['permission_name' => 'CEO'],
            ['permission_name' => 'Manager'],
            ['permission_name' => 'Team Leader'],
            ['permission_name' => 'Accountant'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_permissions');
    }
};
