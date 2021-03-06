<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beers', function ($table) {
            $table->string('product')->nullable()->after('type');
            $table->dropColumn('type');
        });
        DB::statement('ALTER TABLE `beers` MODIFY COLUMN `amount` decimal(8,2);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('beers', function ($table) {
            $table->dropColumn('product');
            $table->enum('type', ['beer', 'cider', 'deposit']);
        });
        DB::statement('ALTER TABLE `beers` MODIFY COLUMN `amount` int;');
    }
}
