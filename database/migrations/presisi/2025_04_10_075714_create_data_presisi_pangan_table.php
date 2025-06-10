<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection('openkab')->hasTable('data_presisi_pangan')) {
            Schema::connection('openkab')->create('data_presisi_pangan', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->integer('config_id');
                $table->integer('rtm_id')->nullable()->index('FK_data_presisi_pangan_rtm');
                $table->integer('keluarga_id')->nullable()->index('FK_data_presisi_pangan_keluarga');

                $table->string('jenis_lahan')->nullable();
                $table->string('luas_lahan')->nullable();
                $table->string('luas_tanam')->nullable();
                $table->string('status_lahan')->nullable();
                $table->string('komoditi_utama_tanaman_pangan')->nullable();
                $table->string('komoditi_tanaman_pangan_lainnya')->nullable();
                $table->string('jumlah_berdasarkan_jenis_komoditi')->nullable();
                $table->string('usia_komoditi')->nullable();
                $table->string('jenis_peternakan')->nullable();
                $table->string('jumlah_populasi')->nullable();
                $table->string('jenis_perikanan')->nullable();
                $table->string('frekwensi_makanan_perhari')->nullable();
                $table->string('frekwensi_konsumsi_sayur_perhari')->nullable();
                $table->string('frekwensi_konsumsi_buah_perhari')->nullable();
                $table->string('frekwensi_konsumsi_daging_perhari')->nullable();

                $table->date('tanggal_pengisian')->nullable();
                $table->string('status_pengisian')->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

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
        //
    }
};
