@extends('layouts.app')
@section('title', 'Catat Transaksi Kas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Form Transaksi Kas Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('keuangan.transaksi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Arus Uang <span class="text-danger">*</span></label>
                        <select name="jenis" id="jenis_transaksi" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Pemasukan">Pemasukan (Uang Masuk)</option>
                            <option value="Pengeluaran">Pengeluaran (Uang Keluar)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kategori Transaksi <span class="text-danger">*</span></label>
                        <select name="kategori_transaksi_id" id="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}" data-jenis="{{ $k->jenis }}">{{ $k->nama_kategori }} ({{ $k->jenis }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="blok_siswa" style="display: none;" class="card p-3 bg-light mb-3">
                        <h5 class="text-success font-weight-bold">Informasi Pembayaran Siswa</h5>
                        <div class="form-group">
                            <label>Pilih Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control">
                                <option value="">-- Cari Nama Siswa --</option>
                                @foreach($siswa as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_siswa }} - Kelas {{ $s->kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Untuk Pembayaran Bulan</label>
                            <select name="untuk_bulan" class="form-control">
                                <option value="">-- Pilih Bulan (Khusus SPP) --</option>
                                @foreach($daftarBulan as $b)
                                    <option value="{{ $b }} {{ date('Y') }}">{{ $b }} {{ date('Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nominal (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="nominal" id="nominal_uang" placeholder="Masukkan angka tanpa titik, contoh: 95000" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Tambahan / Catatan</label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Contoh: Pembelian token listrik, Donasi hamba Allah..."></textarea>
                    </div>

                    <hr>
                    <a href="{{ route('keuangan.transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('jenis_transaksi').addEventListener('change', function() {
        let jenis = this.value;
        let categories = document.querySelectorAll('#kategori_id option');
        let blokSiswa = document.getElementById('blok_siswa');

        // Filter kategori berdasarkan jenis yang dipilih
        categories.forEach(opt => {
            if(opt.value !== "") {
                if(opt.getAttribute('data-jenis') === jenis) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            }
        });
        document.getElementById('kategori_id').value = "";

        // Tampilkan blok siswa jika jenisnya Pemasukan
        if(jenis === 'Pemasukan') {
            blokSiswa.style.display = 'block';
        } else {
            blokSiswa.style.display = 'none';
            document.getElementById('siswa_id').value = "";
        }
    });

    // AJAX Sederhana untuk deteksi SPP bawaan siswa
    document.getElementById('siswa_id').addEventListener('change', function() {
        let idSiswa = this.value;
        if(idSiswa) {
            fetch(`/keuangan/transaksi/get-spp/${idSiswa}`)
                .then(response => response.json())
                .then(data => {
                    if(data.nominal_spp) {
                        document.getElementById('nominal_uang').value = Math.round(data.nominal_spp);
                    }
                })
                .catch(error => console.error('Gagal mengambil data tarif SPP:', error));
        }
    });
</script>
@endsection
