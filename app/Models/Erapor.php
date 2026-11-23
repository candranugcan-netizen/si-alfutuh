<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Erapor extends Model
{
    use HasFactory;

    protected $table = 'erapor';

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'semester',
        'fase',
        'tanggal_rapor',
        'tempat_cetak',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'nama_guru_kelas',
        'nip_guru_kelas',
        'agama',
        'anak_ke',
        'alamat',
        'nama_ayah',
        'agama_ayah',
        'nama_ibu',
        'agama_ibu',
        'status_gizi',
        'kondisi_rambut',
        'kondisi_mata',
        'kondisi_telinga',
        'kondisi_hidung',
        'kondisi_mulut',
        'kondisi_kulit',
        'kebersihan_badan',
        'kebersihan_pakaian',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'kehadiran_sakit',
        'kehadiran_izin',
        'kehadiran_alfa',
        'catatan_guru',
        'umpan_balik_ortu',
        'narasi_agama',
        'foto_agama_1',
        'foto_agama_2',
        'narasi_jati_diri',
        'foto_jati_diri_1',
        'foto_jati_diri_2',
        'narasi_literasi_steam',
        'foto_literasi_steam_1',
        'foto_literasi_steam_2',
        'tema_projek',
        'narasi_projek',
        'foto_projek_1',
        'foto_projek_2',
        'status',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(EraporNilai::class, 'erapor_id')->orderBy('urutan', 'asc');
    }

    public function nilais(): HasMany
    {
        return $this->nilai();
    }

    /**
     * Daftar indikator perkembangan default sesuai format buku rapor RA Islam Al Futuh
     * Membedakan indikator hafalan Quran, Hadits, dan Doa untuk Kelas A dan Kelas B
     */
    public static function getDaftarIndikatorDefault(?string $kelompok = 'A'): array
    {
        $isKelasB = strtoupper(trim($kelompok ?? 'A')) === 'B';

        // 1. DAWAAMUL QUR'AN (DQ)
        if ($isKelasB) {
            $surahItems = [
                'An-Nas', 'Al-Falaq', 'Al-Ikhlas', 'Al-Lahab', 'An-Nasr',
                'Al-Kafirun', 'Al-Kautsar', 'Al-Ma\'un', 'Quraisy', 'Al-Fil',
                'Al-Humazah', 'Al-Ashr', 'At-Takatsur', 'Al-Qori’ah', 'Al-Adiyat',
                'Az-Zalzalah', 'Al-Bayyinah', 'Al-Qadr', 'Al-\'Alaq', 'At-Tin',
                'Al-Insyirah', 'Adh-Dhuha', 'Al-Lail', 'Asy-Syams', 'Al-Balad',
                'Al-Fajr', 'Al-Ghasyiyah', 'Al-A\'la', 'An-Nazi\'at', 'An-Naba\''
            ];
        } else {
            // Kelas A: An-Nas s/d Az-Zalzalah
            $surahItems = [
                'An-Nas', 'Al-Falaq', 'Al-Ikhlas', 'Al-Lahab', 'An-Nasr',
                'Al-Kafirun', 'Al-Kautsar', 'Al-Ma\'un', 'Quraisy', 'Al-Fil',
                'Al-Humazah', 'Al-Ashr', 'At-Takatsur', 'Al-Qori’ah', 'Al-Adiyat',
                'Az-Zalzalah'
            ];
        }

        $dqItems = [];
        foreach ($surahItems as $idx => $s) {
            $dqItems[] = ['kode' => 'DQ ' . ($idx + 1), 'indikator' => $s];
        }

        // 2. MUTIARA HADITS (MH)
        if ($isKelasB) {
            $haditsItems = [
                'Hadits tebarkan salam',
                'Hadits keutamaan berdoa',
                'Hadits memberi hadiah',
                'Hadits keutamaan tersenyum',
                'Hadits mukmin laksana cermin',
                'Hadits menjadi pelopor kebaikan',
                'Hadits menyambung silaturahmi',
                'Hadits adab makan',
                'Hadits menjaga rasa malu',
                'Hadits menyayangi yang kecil dan menghormati yang tua'
            ];
        } else {
            // Kelas A: Tambahan di atas daftar yang sudah ada
            $haditsItems = [
                'Hadits Niat',
                'Doa Adalah Ibadah',
                'Sesama Muslim Bersaudara',
                'Hadits Sedekah',
                'Berbuat Baik Kepada Sesama Makhluk',
                'Hadits Larangan Marah',
                'Kewajiban menuntut ilmu',
                'Menyampaikan walau satu ayat',
                'Meminta kepada Allah',
                'Senyum',
                'Menjaga Allah'
            ];
        }

        $mhItems = [];
        foreach ($haditsItems as $idx => $h) {
            $mhItems[] = ['kode' => 'MH ' . ($idx + 1), 'indikator' => $h];
        }

        // 3. DOA - DOA (DD)
        if ($isKelasB) {
            $doaItems = [
                'Doa keluar rumah',
                'Doa masuk rumah',
                'Doa setelah wudhu',
                'Doa buka puasa',
                'Doa masuk masjid',
                'Doa keluar masjid',
                'Doa sesudah adzan',
                'Doa ketika mendengar petir',
                'Doa ketika turun hujan',
                'Doa ketika angin kencang',
                'Doa meminta amal yang diterima'
            ];
        } else {
            // Kelas A: Tambahan di atas daftar yang sudah ada
            $doaItems = [
                'Doa Sapu Jagat',
                'Doa Minta Ampunan',
                'Doa Untuk Orang Tua',
                'Mendoakan Orang Lain',
                'Mendoakan Orang Yang Berbuat Baik',
                'Sebelum makan dan minum',
                'Setelah makan dan minum',
                'Sebelum tidur',
                'Bangun tidur',
                'Meminta tambahan ilmu',
                'Memakai pakaian',
                'Melepas pakaian'
            ];
        }

        $ddItems = [];
        foreach ($doaItems as $idx => $d) {
            $ddItems[] = ['kode' => 'DD ' . ($idx + 1), 'indikator' => $d];
        }

        return [
            // A. NILAI AGAMA DAN MORAL (NAM)
            [
                'kategori_kode' => 'A',
                'kategori_nama' => 'NILAI AGAMA DAN MORAL (NAM)',
                'sub_kategori' => 'DAWAAMUL QUR’AN (DQ)',
                'items' => $dqItems
            ],
            [
                'kategori_kode' => 'A',
                'kategori_nama' => 'NILAI AGAMA DAN MORAL (NAM)',
                'sub_kategori' => 'MUTIARA HADITS (MH)',
                'items' => $mhItems
            ],
            [
                'kategori_kode' => 'A',
                'kategori_nama' => 'NILAI AGAMA DAN MORAL (NAM)',
                'sub_kategori' => 'DOA - DOA (DD)',
                'items' => $ddItems
            ],
            [
                'kategori_kode' => 'A',
                'kategori_nama' => 'NILAI AGAMA DAN MORAL (NAM)',
                'sub_kategori' => 'DZIKIR (DZ)',
                'items' => [
                    ['kode' => 'DZ 1', 'indikator' => 'Tasbih'],
                    ['kode' => 'DZ 2', 'indikator' => 'Tahmid'],
                    ['kode' => 'DZ 3', 'indikator' => 'Tahlil'],
                    ['kode' => 'DZ 4', 'indikator' => 'Takbir'],
                    ['kode' => 'DZ 5', 'indikator' => 'Istighfar'],
                ]
            ],
            [
                'kategori_kode' => 'A',
                'kategori_nama' => 'NILAI AGAMA DAN MORAL (NAM)',
                'sub_kategori' => 'AKIDAH (AQ)',
                'items' => [
                    ['kode' => 'AQ 1', 'indikator' => 'Rukun Iman'],
                    ['kode' => 'AQ 2', 'indikator' => 'Rukun Islam'],
                    ['kode' => 'AQ 3', 'indikator' => 'Syahadatain'],
                    ['kode' => 'AQ 4', 'indikator' => 'Asmaul Husna'],
                ]
            ],
            [
                'kategori_kode' => 'A',
                'kategori_nama' => 'NILAI AGAMA DAN MORAL (NAM)',
                'sub_kategori' => 'AKHLAQ (AK)',
                'items' => [
                    ['kode' => 'AK 1', 'indikator' => 'Berbuat baik pada orang tua, guru dan teman'],
                    ['kode' => 'AK 2', 'indikator' => 'Menunjukkan perilaku baik'],
                    ['kode' => 'AK 3', 'indikator' => 'Membiasakan perilaku baik'],
                    ['kode' => 'AK 4', 'indikator' => 'Mengucapkan salam'],
                    ['kode' => 'AK 5', 'indikator' => 'Membedakan perilaku baik dan buruk'],
                    ['kode' => 'AK 6', 'indikator' => 'Membedakan benar dan salah'],
                    ['kode' => 'AK 7', 'indikator' => 'Membiasakan berperilaku jujur'],
                    ['kode' => 'AK 8', 'indikator' => 'Menyayangi sesama membiasakan berdoa sebelum dan melakukan kegiatan'],
                    ['kode' => 'AK 9', 'indikator' => 'Membiasakan bersikap ramah kepada orang lain'],
                    ['kode' => 'AK 10', 'indikator' => 'Membiasakan mengembalikan dan merapikan mainan yang telah digunakan'],
                    ['kode' => 'AK 11', 'indikator' => 'Membiasakan berani mengungkapkan pendapat'],
                    ['kode' => 'AK 12', 'indikator' => 'Membiasakan berani memimpin doa'],
                    ['kode' => 'AK 13', 'indikator' => 'Terbiasa menjaga kebersihan diri dan lingkungan'],
                    ['kode' => 'AK 14', 'indikator' => 'Terbiasa tidak berbohong'],
                    ['kode' => 'AK 15', 'indikator' => 'Terbiasa menghargai kepemilikan orang lain'],
                    ['kode' => 'AK 16', 'indikator' => 'Terbiasa mengembalikan benda yang bukan haknya'],
                    ['kode' => 'AK 17', 'indikator' => 'Terbiasa mengembalikan benda yang bukan miliknya'],
                    ['kode' => 'AK 18', 'indikator' => 'Mengakui kesalahannya'],
                    ['kode' => 'AK 19', 'indikator' => 'Mengakui kelebihan diri dan teman lainnya'],
                ]
            ],
            [
                'kategori_kode' => 'A',
                'kategori_nama' => 'NILAI AGAMA DAN MORAL (NAM)',
                'sub_kategori' => 'FIQIH (FQ)',
                'items' => [
                    ['kode' => 'FQ 1', 'indikator' => 'Mengenal tata cara wudhu'],
                    ['kode' => 'FQ 2', 'indikator' => 'Praktek wudhu'],
                    ['kode' => 'FQ 3', 'indikator' => 'Mengenal cara beribadah'],
                    ['kode' => 'FQ 4', 'indikator' => 'Melakukan kegiatan urutan gerakan sholat'],
                ]
            ],

            // B. FISIK MOTORIK
            [
                'kategori_kode' => 'B',
                'kategori_nama' => 'FISIK MOTORIK',
                'sub_kategori' => null,
                'items' => [
                    ['kode' => '2.1.1', 'indikator' => 'Terbiasa makan makanan gizi seimbang'],
                    ['kode' => '2.1.2', 'indikator' => 'Terbiasa memelihara kebersihan diri dan lingkungan'],
                    ['kode' => '4.3.1', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu melakukan gerakan bergelayutan (bergelayutan)'],
                    ['kode' => '4.3.2', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu melakukan gerakan melompat, meloncat, dan berlari secara terkoordinasi'],
                    ['kode' => '4.3.3', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu menangkap bola dengan tepat'],
                    ['kode' => '4.3.4', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu melakukan gerakan antisipasi (misal : permaianan lempar bola)'],
                    ['kode' => '4.3.5', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu menendang bola secara terarah'],
                    ['kode' => '4.3.6', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu memanfaatkan alat permainan didalam ruangan dan diluar ruangan'],
                    ['kode' => '4.3.7', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu menggunakan anggota badan untuk melakukan gerakan halus yang terkontrol (misal meronce)'],
                    ['kode' => '4.3.8', 'indikator' => 'Mulai terbiasa melakukan hidup bersih dan sehat'],
                    ['kode' => '4.4.1', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu mengenali bagian tubuh yang harus dilindungi dan cara melindungi dari kekerasan, termasuk kekerasan seksual'],
                    ['kode' => '4.4.2', 'indikator' => 'Mulai terbiasa mengkonsumsi makanan dan minuman yang bersih, sehat dan bergizi'],
                    ['kode' => '4.4.3', 'indikator' => 'Menggunakan toilet tanpa bantuan'],
                ]
            ],

            // C. KOGNITIF
            [
                'kategori_kode' => 'C',
                'kategori_nama' => 'KOGNITIF',
                'sub_kategori' => null,
                'items' => [
                    ['kode' => '2.2.1', 'indikator' => 'Terbiasa menunjukkan aktivitas yang bersifat eksploratif dan menyelidiki (seperti: aktif bertanya, mencoba melakukan sesuatu untuk mendapatkan jawaban)'],
                    ['kode' => '2.3.1', 'indikator' => 'Kreatif dalam menyelesaikan masalah menggunakan ide, gagasan diluar kebiasaan atau cara yang tidak biasa atau dengan menerapkan pengetahuan atau pengalaman baru'],
                    ['kode' => '2.3.2', 'indikator' => 'Menunjukkan inisiatif dalam memilih permainan (seperti: “ayo kita bermain pura - pura seperti burung”)'],
                    ['kode' => '4.5.1', 'indikator' => 'Mampu memecahkan masalah sederhana yang dihadapi dibantu oleh orang dewasa'],
                    ['kode' => '4.5.2', 'indikator' => 'Melanjutkan kegiatan sampai selesai'],
                    ['kode' => '4.6.1', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu mengenal benda dengan mengelompokkan berbagai benda berdasarkan ukuran (misal : tebal - tipis, berat - ringan)'],
                    ['kode' => '4.6.2', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu mengenal benda dengan memasangkan benda dengan pasangannya'],
                    ['kode' => '4.6.3', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu mengenal benda dengan mengurutkan benda berdasarkan ukuran dari yang terpendek sampai yang terpanjang, terkecil - kebesar'],
                    ['kode' => '4.6.4', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu mengenal benda berdasarkan bentuk, ukuran, dan warna melalui kegiatan mengelompokkan'],
                    ['kode' => '4.6.5', 'indikator' => 'Melakukan kegiatan yang menunjukkan anak mampu mengenal konsep besar - kecil, banyak - sedikit, panjang - pendek, berat - ringan, tinggi - rendah melalui kegiatan membandingkan'],
                    ['kode' => '4.7.1', 'indikator' => 'Menyebutkan nama anggota keluarga lain, teman, dan jenis kelamin mereka'],
                    ['kode' => '4.7.2', 'indikator' => 'Menyebut tempat dilingkungan sekitarnya'],
                    ['kode' => '4.7.3', 'indikator' => 'Menyebutkan arah ketempat yang sering dikunjungi radius yang lebih jauh (pasar, taman bermain)'],
                    ['kode' => '4.7.4', 'indikator' => 'Menyebutkan dan mengetahui perlengkapan / atribut yang berhubungan dengan pekerjaan orang - orang yang ada di sekitarnya'],
                    ['kode' => '4.7.5', 'indikator' => 'Mengikuti aturan'],
                    ['kode' => '4.8.1', 'indikator' => 'Menunjukkan nama dan kegunaan benda - benda alam'],
                    ['kode' => '4.8.2', 'indikator' => 'Mengungkapkan hasil karya yang dibuatnya secara sederhana yang berhubungan dengan pekerjaan orang - orang yang ada disekitarnya'],
                    ['kode' => '4.8.3', 'indikator' => 'Menunjukkan proses perkembangbiakan makhluk hidup misal : ayam'],
                    ['kode' => '4.9.1', 'indikator' => 'Menggunakan benda - benda teknologi sederhana (gunting, sikat gigi, sendok, membuka tutup botol, roda pada kendaraan)'],
                    ['kode' => '4.9.2', 'indikator' => 'Mengenal bahan - bahan pembuatan teknologi sederhana'],
                ]
            ],

            // D. BAHASA
            [
                'kategori_kode' => 'D',
                'kategori_nama' => 'BAHASA',
                'sub_kategori' => null,
                'items' => [
                    ['kode' => '2.14.1', 'indikator' => 'Terbiasa ramah menyapa siapapun dengan lembut dan santun'],
                    ['kode' => '4.10.1', 'indikator' => 'Menyimak dan membaca hijaiyah'],
                    ['kode' => '4.10.2', 'indikator' => 'Menceritakan kembali apa yang didengar dengan kosa kata yang terbatas'],
                    ['kode' => '4.10.3', 'indikator' => 'Melaksanakan perintah sederhana sesuai dengan aturan yang disampaikan (misal : aturan makan bersama)'],
                    ['kode' => '4.11.1', 'indikator' => 'Menggunakan kalimat pendek untuk berinteraksi dengan anak atau orang dewasa untuk menyatakan apa yang dilihat dan dirasa'],
                    ['kode' => '4.11.2', 'indikator' => 'Menceritakan gambar didalam buku'],
                    ['kode' => '4.11.3', 'indikator' => 'Berbicara sesuai dengan kebutuhan (kapan harus bertanya, berpendapat)'],
                    ['kode' => '4.11.4', 'indikator' => 'Bertanya dengan menggunakan lebih dari 2 kata - kata tanya seperti apa, mengapa, bagaimana, kapan, dimana, siapa'],
                    ['kode' => '4.12.1', 'indikator' => 'Menulis huruf - huruf yang dicontohkan dengan cara meniru'],
                    ['kode' => '4.12.2', 'indikator' => 'Menceritakan isi buku walaupun tidak sama tulisan dengan bahasa yang diungkapkan'],
                    ['kode' => '4.12.3', 'indikator' => 'Menghubungkan benda - benda konkret dengan lambang bilangan 1 - 10'],
                ]
            ],

            // E. SOSIAL EMOSIONAL
            [
                'kategori_kode' => 'E',
                'kategori_nama' => 'SOSIAL EMOSIONAL',
                'sub_kategori' => null,
                'items' => [
                    ['kode' => '2.5.1', 'indikator' => 'Terbiasa menyapa guru saat penyambutan'],
                    ['kode' => '2.5.2', 'indikator' => 'Berani tampil di depan teman, guru, orang tua dan lingkungan dan sosial lainnya'],
                    ['kode' => '2.5.3', 'indikator' => 'Berani mengemukakan pendapat'],
                    ['kode' => '2.5.4', 'indikator' => 'Berani menyampaikan keinginan'],
                    ['kode' => '2.5.5', 'indikator' => 'Berkomunikasi dengan orang yang belum dikenal sebelumnya dengan pengawasan guru'],
                    ['kode' => '2.5.6', 'indikator' => 'Bangga menunjukkan hasil karya'],
                    ['kode' => '2.5.7', 'indikator' => 'Senang ikut serta dalam kegiatan bersama'],
                    ['kode' => '2.5.8', 'indikator' => 'Tidak terpengaruh penilaian orang tentang dirinya'],
                    ['kode' => '2.6.1', 'indikator' => 'Tahu akan haknya'],
                    ['kode' => '2.6.2', 'indikator' => 'Mentaati aturan kelas (kegiatan, aturan)'],
                    ['kode' => '2.6.3', 'indikator' => 'Mengatur diri sendiri'],
                    ['kode' => '2.7.1', 'indikator' => 'Kesediaan diri untuk menahan diri'],
                    ['kode' => '2.7.2', 'indikator' => 'Bersikap tenang tidak lekas marah dan dapat menunda keinginan'],
                    ['kode' => '2.7.3', 'indikator' => 'Mau menunggu giliran, mau mendengarkan ketika orang lain bicara'],
                    ['kode' => '2.7.4', 'indikator' => 'Tidak menangis saat berpisah dengan orang tuanya'],
                    ['kode' => '2.7.5', 'indikator' => 'Tidak mudah mengeluh'],
                    ['kode' => '2.7.6', 'indikator' => 'Tidak tergesa-gesa'],
                    ['kode' => '2.7.7', 'indikator' => 'Selalu menyelesaikan tugas-tugasnya hingga tuntas'],
                    ['kode' => '2.7.8', 'indikator' => 'Berusaha tidak menyakiti atau membalas kekerasan'],
                    ['kode' => '2.8.1', 'indikator' => 'Terbiasa tidak bergantung pada orang lain'],
                    ['kode' => '2.8.2', 'indikator' => 'Terbiasa mengambil keputusan secara mandiri'],
                    ['kode' => '2.8.3', 'indikator' => 'Merencanakan, memilih, memiliki inisiatif untuk belajar atau melakukan sesuatu tanpa harus dibantu atau dibantu seperlunya'],
                    ['kode' => '2.9.1', 'indikator' => 'Mengetahui perasaan temannya dan merespon secara wajar'],
                    ['kode' => '2.9.2', 'indikator' => 'Berbagi dengan orang lain'],
                    ['kode' => '2.9.3', 'indikator' => 'Menghargai hak/pendapat/karya orang lain'],
                    ['kode' => '2.9.4', 'indikator' => 'Terbiasa mengindahkan dan memperhatikan kondisi teman'],
                    ['kode' => '2.9.5', 'indikator' => 'Mau menemani teman melakukan kegiatan bersama'],
                    ['kode' => '2.9.6', 'indikator' => 'Senang menawarkan bantuan pada teman atau guru, peka untuk membantu orang lain yang membutuhkan'],
                    ['kode' => '2.9.7', 'indikator' => 'Mampu menenangkan diri dan temannya dalam berbagai situasi'],
                    ['kode' => '2.9.8', 'indikator' => 'Senang mengajak temannya untuk berkomunikasi beraksi positif kepada semua temannya'],
                    ['kode' => '2.10.1', 'indikator' => 'Bermain dengan teman sebaya'],
                    ['kode' => '2.10.2', 'indikator' => 'Menerima perbedaan teman dengan dirinya'],
                    ['kode' => '2.10.3', 'indikator' => 'Menghargai karya teman'],
                    ['kode' => '2.10.4', 'indikator' => 'Tidak ingin menang sendiri'],
                    ['kode' => '2.10.5', 'indikator' => 'Menghargai pendapat-pendapat teman dan mendengarkan dengan sabar pendapat teman'],
                    ['kode' => '2.10.6', 'indikator' => 'Senang berteman dengan semuanya'],
                    ['kode' => '2.11.1', 'indikator' => 'Memperlihatkan diri untuk menyesuaikan dengan situasi'],
                    ['kode' => '2.11.2', 'indikator' => 'Memperlihatkan kehati-hatian kepada orang yang belum dikenal (menumbuhkan kepercayaan kepada orang dewasa yang tepat)'],
                    ['kode' => '2.11.3', 'indikator' => 'Bersikap kooperatif dengan teman'],
                    ['kode' => '2.11.4', 'indikator' => 'Menggunakan cara yang diterima secara sosial dalam menyelesaikan masalah menggunakan pikiran untuk menyelesaikan masalah Tetap tenang saat ditempat yang baru dengan situasi baru misal: saat bertemu, berada dipusat perbelanjaan, atau saat bertemu dengan guru baru'],
                    ['kode' => '2.11.5', 'indikator' => 'Bertanggung jawab atas perilaku untuk kebaikan diri sendiri'],
                    ['kode' => '2.12.1', 'indikator' => 'Bersedia untuk menerima konsekuensi atau menanggung akibat atas tindakan yang diperbuat baik secara sengaja maupun tidak sengaja'],
                    ['kode' => '2.12.2', 'indikator' => 'Mau mengakui kesalahan dengan meminta maaf'],
                    ['kode' => '2.12.3', 'indikator' => 'Merapikan/membereskan mainan pada tempat semula'],
                    ['kode' => '2.12.4', 'indikator' => 'Mengerjakan sesuatu hingga tuntas'],
                    ['kode' => '2.12.5', 'indikator' => 'Senang menjalankan kegiatan yang jadi tugasnya (misalnya piket sebagai pemimpin harus membantu menyiapkan alat makan)'],
                    ['kode' => '2.12.6', 'indikator' => 'Menjalin pertemanan dengan anak lain'],
                    ['kode' => '4.13.1', 'indikator' => 'Mempertahankan haknya untuk melindungi diri dengan bantuan orang lain, misal: meminta bantuan pada orang dewasa'],
                    ['kode' => '4.13.2', 'indikator' => 'Memilih satu macam dari 2-3 pilihan yang tersedia (misal: mainan, makanan, pakaian)'],
                    ['kode' => '4.14.1', 'indikator' => 'Memilih satu dari berbagai kegiatan/ benda yang disediakan'],
                ]
            ],

            // F. SENI
            [
                'kategori_kode' => 'F',
                'kategori_nama' => 'SENI',
                'sub_kategori' => null,
                'items' => [
                    ['kode' => '2.4.1', 'indikator' => 'Menghargai keindahan diri sendiri, karya sendiri atau orang lain, alam dan lingkungan sekitar'],
                    ['kode' => '2.4.2', 'indikator' => 'Menjaga kerapian diri'],
                    ['kode' => '2.4.4', 'indikator' => 'Merawat kerapian, kebersihan, dan keutuhan benda mainan atau milik pribadinya'],
                    ['kode' => '4.15.1', 'indikator' => 'Menghargai penampilan karya seni anak lain dengan bimbingan'],
                ]
            ],

            // G. MUATAN LOKAL / CIRI KHAS
            [
                'kategori_kode' => 'G',
                'kategori_nama' => 'MUATAN LOKAL / CIRI KHAS',
                'sub_kategori' => null,
                'items' => [
                    ['kode' => 'G 1', 'indikator' => 'Tahfidz surat pendek'],
                    ['kode' => 'G 2', 'indikator' => 'Tahfidz hadits'],
                    ['kode' => 'G 3', 'indikator' => 'Tahfidz doa harian'],
                    ['kode' => 'G 4', 'indikator' => 'Iqro’'],
                    ['kode' => 'G 5', 'indikator' => 'Anak islam suka membaca (AISM)'],
                    ['kode' => 'G 6', 'indikator' => 'Bahasa Jawa'],
                    ['kode' => 'G 7', 'indikator' => 'Bahasa Arab'],
                    ['kode' => 'G 8', 'indikator' => 'Bahasa Inggris'],
                ]
            ],
        ];
    }
}
