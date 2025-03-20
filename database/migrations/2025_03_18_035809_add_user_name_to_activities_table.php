<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // Isi dengan kode ini:
public function up()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->string('user_name')->nullable()->after('user_id');
    });
}

public function down()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->dropColumn('user_name');
    });
}

};
