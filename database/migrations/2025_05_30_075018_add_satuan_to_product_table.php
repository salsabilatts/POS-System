<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSatuanToProductTable extends Migration
{
    public function up()
{
    Schema::table('product', function (Blueprint $table) {
        $table->string('satuan', 50)->after('stock')->nullable();
    });
}

public function down()
{
    Schema::table('product', function (Blueprint $table) {
        $table->dropColumn('satuan');
    });
}

}
