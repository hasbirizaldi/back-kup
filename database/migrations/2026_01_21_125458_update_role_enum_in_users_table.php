<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Di MySQL, ubah enum dengan modifyColumn
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'admin_pegawai'])
                ->default('admin')
                ->change();
        });
    }

    public function down()
    {
        // Rollback ke enum lama tanpa admin_pegawai
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin'])
                ->default('admin')
                ->change();
        });
    }
};
