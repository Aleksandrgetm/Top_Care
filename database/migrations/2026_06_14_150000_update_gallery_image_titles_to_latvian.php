<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gallery_images')) {
            return;
        }

        $titleMap = [
            'images/fasada-mazgasana-pirms-pec.png' => 'Fasādes mazgāšana pirms un pēc',
            'images/fasades-apdares-darbi-01.jpeg' => 'Fasādes apdares darbi',
            'images/fasades-mazgasana-darba-process.png' => 'Fasādes mazgāšanas process',
            'images/fasades-mazgasana-privatmaja-jurmala.jpeg' => 'Privātmājas fasādes mazgāšana',
            'images/interjera-renovacija-01.jpeg' => 'Interjera renovācija',
            'images/jumta-tirisana-latvija.jpg' => 'Jumta tīrīšana',
            'images/jumta-tirisana-pirms-pec.jpeg' => 'Jumta tīrīšana pirms un pēc',
            'images/jumta-tirisana-pirms-pec.png' => 'Jumta tīrīšana pirms un pēc',
            'images/kapnu-izbuve-01.jpeg' => 'Kāpņu izbūve',
            'images/koka-logu-restauracija-jurmala.jpeg' => 'Koka logu restaurācija',
            'images/logu-restauracijas-darbi-latvija.jpeg' => 'Koka logu restaurācijas process',
            'images/majas-eksterjera-atjaunosana.jpeg' => 'Mājas eksterjera atjaunošana',
            'images/malkas-krausana.png' => 'Saskaldīta malka klientam',
            'images/malkas-skaldisana-1.jpeg' => 'Mobilā malkas skaldīšana',
            'images/malkas-skaldisana-2.jpeg' => 'Mobilās malkas skaldīšanas process',
            'images/malkas-skaldisana-3.jpeg' => 'Saskaldīta malka klientam',
            'images/malkas-skaldisana-un-piegade.jpeg' => 'Mobilā malkas skaldīšana un piegāde',
            'images/metala-jumts-pirms-tirisanas.png' => 'Metāla jumts pirms tīrīšanas',
            'images/privatmajas-renovacija-latvija.jpeg' => 'Privātmājas renovācija',
            'images/profesionala-koka-logu-restauracija-jurmala.jpeg' => 'Profesionāla koka logu restaurācija',
            'images/pvc-logi-02.jpeg' => 'PVC logi',
            'images/pvc-logi-03.jpeg' => 'PVC logi',
            'images/renovacijas-demontaza-01.jpeg' => 'Renovācijas demontāžas darbi',
            'images/renovacijas-demontaza-02.jpeg' => 'Renovācijas demontāžas process',
            'images/renovacijas-istaba-01.jpeg' => 'Iekštelpu renovācija',
            'images/renovacijas-istaba-02.jpeg' => 'Iekštelpu renovācija',
            'images/renovacijas-istaba-03.jpeg' => 'Iekštelpu renovācija',
            'images/renovacijas-istaba-04.jpeg' => 'Iekštelpu renovācija',
            'images/renovacijas-istaba-05.jpeg' => 'Iekštelpu renovācija',
            'images/renovacijas-istaba-06.jpeg' => 'Iekštelpu renovācija',
            'images/renovacijas-koridors-01.jpeg' => 'Koridora renovācija',
            'images/renovacijas-koridors-02.jpeg' => 'Koridora renovācija',
            'images/renovacijas-koridors-03.jpeg' => 'Koridora renovācija',
            'images/sienu-krasosana-01.jpeg' => 'Sienu krāsošana',
            'images/teritorijas-labiekartosana-01.jpeg' => 'Teritorijas labiekārtošana',
            'images/teritorijas-labiekartosana-darbi.jpeg' => 'Teritorijas labiekārtošanas darbi',
            'images/topcare-komanda.png' => 'Top Care Group komanda',
            'images/virtuves-renovacija-01.jpeg' => 'Virtuves renovācija',
        ];

        foreach ($titleMap as $image => $title) {
            DB::table('gallery_images')
                ->where('image', $image)
                ->update(['title' => $title]);
        }
    }

    public function down(): void
    {
    }
};
