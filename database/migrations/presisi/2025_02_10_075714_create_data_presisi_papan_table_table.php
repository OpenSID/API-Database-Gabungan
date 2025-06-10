<?php

use Modules\DataPresisi\Models\Papan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection('openkab')->hasTable('data_presisi_papan')) {
            Schema::connection('openkab')->create('data_presisi_papan', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->configId();
                $table->integer('rtm_id')->nullable();
                $table->integer('keluarga_id')->nullable();
                $table->timesWithUserstamps();
                $table->string('kd_stat_bangunan_tinggal', 2)->nullable();
                $table->integer('luas_lahan_pekarangan')->nullable();
                $table->integer('jumlah_kamar_tidur')->nullable();
                $table->integer('luas_lantai')->nullable();
                $table->string('kd_jenis_lantai_terluas', 2)->nullable();
                $table->string('kd_jenis_dinding', 2)->nullable();
                $table->string('kd_kondisi_dinding', 2)->nullable();
                $table->string('kd_jenis_atap', 2)->nullable();
                $table->string('kd_kondisi_atap', 2)->nullable();

                $table->string('kd_sumber_air_minum', 2)->nullable();
                $table->string('kd_jarak_sumber_air_ke_tpl', 2)->nullable();

                $table->string('kd_pembuangan_akhir_tinja', 2)->nullable();

                $table->string('kd_sumber_penerangan_utama', 2)->nullable();
                $table->string('kd_daya_terpasang', 2)->nullable();
                $table->string('kd_daya_terpasang2', 2)->nullable();
                $table->string('kd_daya_terpasang3', 2)->nullable();

                // Add foreign key constraints
                $table->foreign('rtm_id')->references('id')->on('tweb_rtm')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('keluarga_id')->references('id')->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
