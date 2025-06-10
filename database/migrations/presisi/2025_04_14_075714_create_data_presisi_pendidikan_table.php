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
        if (! Schema::connection('openkab')->hasTable('data_presisi_pendidikan')) {
            Schema::connection('openkab')->create('data_presisi_pendidikan', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->configId();
                $table->foreignUuid('data_presisi_tahun_id')->nullable()->constrained('data_presisi_tahun')->references('uuid')->cascadeOnUpdate()->cascadeOnDelete();
                $table->integer('rtm_id')->nullable()->index('FK_data_presisi_pendidikan_rtm');
                $table->integer('keluarga_id')->nullable()->index('FK_data_presisi_pendidikan_keluarga');
                $table->integer('anggota_id')->nullable()->index('FK_data_presisi_pendidikan_anggota');
                $table->string('pendidikan_dalam_kk')->nullable();
                $table->string('pendidikan_sedang_ditempuh')->nullable();
                $table->string('keikutsertaan_kip')->nullable();
                $table->string('jenis_pendidikan_kesetaraan_yg_diikuti')->nullable();
                $table->date('tanggal_pengisian')->nullable();
                $table->string('status_pengisian')->nullable();
                $table->timesWithUserstamps();

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
