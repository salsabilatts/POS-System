<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        DB::statement('ALTER TABLE product MODIFY COLUMN satuan VARCHAR(50) AFTER product_name');
    }

    public function down()
    {
        // Bisa di-reset ke akhir lagi jika perlu
        DB::statement('ALTER TABLE product MODIFY COLUMN satuan VARCHAR(50) AFTER stock');
    }

};
