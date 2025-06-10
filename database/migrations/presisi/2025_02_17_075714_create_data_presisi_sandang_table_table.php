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
        if (! Schema::connection('openkab')->hasTable('data_presisi_sandang')) {
            Schema::connection('openkab')->create('data_presisi_sandang', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->integer('config_id');
                $table->integer('rtm_id')->nullable()->index('FK_data_presisi_sandang_rtm');
                $table->integer('keluarga_id')->nullable()->index('FK_data_presisi_sandang_keluarga');
                $table->integer('anggota_id')->nullable()->index('FK_data_presisi_sandang_anggota');
                $table->string('jml_pakaian_yg_dimiliki')->nullable();
                $table->string('frekwensi_beli_pakaian_pertahun')->nullable();
                $table->string('jenis_pakaian')->nullable();
                $table->string('frekwensi_ganti_pakaian')->nullable();
                $table->string('tmpt_cuci_pakaian')->nullable();
                $table->string('jml_pakaian_seragam')->nullable();
                $table->string('jml_pakaian_sembahyang')->nullable();
                $table->string('jml_pakaian_kerja')->nullable();
                $table->date('tanggal_pengisian')->nullable();
                $table->string('status_pengisian')->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

                // Add foreign key constraints
                $table->foreign('rtm_id')->references('id')->on('tweb_rtm')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('keluarga_id')->references('id')->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('anggota_id')->references('id')->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
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
