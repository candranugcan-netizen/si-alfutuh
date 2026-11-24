@extends('layouts.app')
@section('title', 'Catat Transaksi Kas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Form Transaksi Kas Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('keuangan.transaksi.store') }}" method="POST" id="formTransaksi">
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
                                <option value="{{ $k->id }}" data-jenis="{{ $k->jenis }}" data-nama="{{ $k->nama_kategori }}">{{ $k->nama_kategori }} ({{ $k->jenis }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="blok_sumber_dana" style="display: none;" class="card p-3 bg-light mb-3 border-left-warning">
                        <h5 class="text-warning font-weight-bold"><i class="fas fa-wallet"></i> Alokasi Sumber Dana</h5>
                        <div class="form-group mb-0">
                            <label>Dipotong dari Pos Kas / Pemasukan</label>
                            <select name="sumber_dana_id" id="sumber_dana_id" class="form-control">
                                <option value="">-- Kas Umum / Saldo Bebas (Bukan Dana Terikat) --</option>
                                @foreach($kategoriPemasukan as $kp)
                                    <option value="{{ $kp->id }}">{{ $kp->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-1 d-block">Pilih kategori pemasukan di atas jika pengeluaran ini memotong anggaran khusus (Misal: Uang SPP, Uang Seragam, dll).</small>
                        </div>
                    </div>

                    <div id="blok_siswa" style="display: none;" class="card p-3 bg-light mb-3 border-left-success">
                        <h5 class="text-success font-weight-bold mb-3"><i class="fas fa-user-graduate mr-1"></i> Informasi Pembayaran Siswa</h5>
                        <div class="form-group">
                            <label class="font-weight-bold">Pilih Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control">
                                <option value="">-- Cari Nama Siswa --</option>
                                @foreach($siswa as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_siswa }} - Kelas {{ $s->kelas->nama_kelas ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Card Info Status SPP Siswa --}}
                        <div id="info_spp_siswa" style="display: none;" class="card mb-3 border-info">
                            <div class="card-body py-2 px-3 bg-white">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                        <i class="fas fa-money-bill-wave text-success mr-1"></i>
                                        Tarif SPP: <strong class="text-dark" id="info_tarif_spp">Rp 0</strong> / bulan
                                    </div>
                                    <div id="info_terakhir_lunas" class="small font-weight-bold text-info"></div>
                                </div>
                                <div class="mt-2 pt-2 border-top" id="container_list_lunas" style="display: none;">
                                    <small class="text-muted d-block mb-1">Riwayat bulan yang sudah lunas:</small>
                                    <div id="list_bulan_lunas" class="d-flex flex-wrap" style="gap: 4px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi Pembayaran Khusus SPP --}}
                        <div id="blok_periode_spp">
                            <div class="form-group mb-2">
                                <label class="font-weight-bold d-block">Pilihan Pembayaran SPP:</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="mode_single" name="mode_pembayaran" value="single" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="mode_single">1 Bulan (Pilih Bulan & Tahun)</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="mode_multi" name="mode_pembayaran" value="multi" class="custom-control-input">
                                    <label class="custom-control-label" for="mode_multi">Sekaligus Banyak Bulan (Pilihan Rentang Bulan)</label>
                                </div>
                            </div>

                            {{-- Mode 1: Single Month (Bulan & Tahun bebas) --}}
                            <div id="container_single_bulan" class="p-3 bg-white rounded border mb-2">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group mb-md-0">
                                            <label class="small font-weight-bold">Tahun Pembayaran <span class="text-danger">*</span></label>
                                            <select name="untuk_tahun" id="untuk_tahun_single" class="form-control form-control-sm">
                                                @foreach($daftarTahun as $th)
                                                    <option value="{{ $th }}" {{ $th == date('Y') ? 'selected' : '' }}>
                                                        Tahun {{ $th }} {{ $th == date('Y') ? '(Tahun Berjalan)' : ($th == date('Y') + 1 ? '(Tahun Depan)' : '') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Bulan Pembayaran <span class="text-danger">*</span></label>
                                            <select name="untuk_bulan" id="untuk_bulan_single" class="form-control form-control-sm">
                                                <option value="">-- Pilih Bulan --</option>
                                                @foreach($daftarBulan as $b)
                                                    <option value="{{ $b }}">{{ $b }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex align-items-center justify-content-between">
                                    <small class="text-info font-weight-bold" id="preview_periode_single">
                                        Periode: <span id="label_periode_single">-</span>
                                    </small>
                                    <small class="badge badge-warning text-dark px-2 py-1" id="alert_sudah_lunas_single" style="display: none;">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Bulan ini sudah pernah tercatat lunas
                                    </small>
                                </div>
                            </div>

                            {{-- Mode 2: Multi Months (Rentang Bulan - Cukup Dropdown Pilihan Bulan) --}}
                            <div id="container_multi_bulan" class="p-3 bg-white rounded border mb-2" style="display: none;">
                                <div class="small font-weight-bold text-dark mb-2">
                                    <i class="fas fa-calendar-alt text-success mr-1"></i> Tentukan Rentang Pembayaran Bulan:
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <label class="small font-weight-bold text-muted mb-1">Mulai Dari Bulan:</label>
                                        <div class="form-row">
                                            <div class="col-7">
                                                <select id="multi_dari_bulan" class="form-control form-control-sm">
                                                    <option value="">-- Pilih Bulan --</option>
                                                    @foreach($daftarBulan as $idx => $b)
                                                        <option value="{{ $idx + 1 }}">{{ $b }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-5">
                                                <select id="multi_dari_tahun" class="form-control form-control-sm">
                                                    @foreach($daftarTahun as $th)
                                                        <option value="{{ $th }}" {{ $th == date('Y') ? 'selected' : '' }}>{{ $th }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center my-2 my-md-0 font-weight-bold text-muted small">
                                        s/d
                                    </div>
                                    <div class="col-md-5">
                                        <label class="small font-weight-bold text-muted mb-1">Sampai Dengan Bulan:</label>
                                        <div class="form-row">
                                            <div class="col-7">
                                                <select id="multi_sampai_bulan" class="form-control form-control-sm">
                                                    <option value="">-- Pilih Bulan --</option>
                                                    @foreach($daftarBulan as $idx => $b)
                                                        <option value="{{ $idx + 1 }}">{{ $b }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-5">
                                                <select id="multi_sampai_tahun" class="form-control form-control-sm">
                                                    @foreach($daftarTahun as $th)
                                                        <option value="{{ $th }}" {{ $th == date('Y') ? 'selected' : '' }}>{{ $th }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Info Hasil Pilihan Rentang Bulan --}}
                                <div class="mt-3 pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
                                        <div>
                                            <span class="badge badge-success px-2 py-1 font-weight-bold" id="badge_count_multi">0 bulan dipilih</span>
                                        </div>
                                        <div id="alert_multi_invalid" class="small text-danger font-weight-bold" style="display: none;">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Bulan akhir tidak boleh lebih awal dari bulan awal!
                                        </div>
                                    </div>
                                    <div id="list_terpilih_multi" class="small text-muted mt-2">
                                        Silakan pilih bulan awal dan bulan akhir di atas.
                                    </div>
                                </div>

                                {{-- Container hidden inputs untuk bulan_dipilih[] --}}
                                <div id="hidden_inputs_multi"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nominal (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal" id="nominal_uang" placeholder="Masukkan angka tanpa titik, contoh: 95000" value="{{ old('nominal') }}" required>
                        @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted mt-1 d-block" id="hint_nominal">Nominal dapat disesuaikan jika diperlukan.</small>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Tambahan / Catatan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan_transaksi" rows="3" placeholder="Contoh: Pembayaran SPP lunas sampai tahun depan, donasi..."></textarea>
                    </div>

                    <hr>
                    <a href="{{ route('keuangan.transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const daftarBulanList = @json($daftarBulan);
    let bulanSudahLunas = [];
    let tarifSppPerBulan = 0;

    // 1. Tampilkan / Sembunyikan blok berdasarkan jenis
    document.getElementById('jenis_transaksi').addEventListener('change', function() {
        let jenis = this.value;
        let categories = document.querySelectorAll('#kategori_id option');
        let blokSiswa = document.getElementById('blok_siswa');
        let blokSumberDana = document.getElementById('blok_sumber_dana');

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

        if(jenis === 'Pemasukan') {
            blokSiswa.style.display = 'block';
            blokSumberDana.style.display = 'none';
            document.getElementById('sumber_dana_id').value = "";
        } else if(jenis === 'Pengeluaran') {
            blokSiswa.style.display = 'none';
            blokSumberDana.style.display = 'block';
            document.getElementById('siswa_id').value = "";
        } else {
            blokSiswa.style.display = 'none';
            blokSumberDana.style.display = 'none';
        }
    });

    // 2. Deteksi apakah kategori adalah SPP
    document.getElementById('kategori_id').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];
        let nama = selected ? (selected.getAttribute('data-nama') || selected.text) : '';
        let blokPeriodeSpp = document.getElementById('blok_periode_spp');

        if(nama.toUpperCase().includes('SPP')) {
            blokPeriodeSpp.style.display = 'block';
        } else {
            blokPeriodeSpp.style.display = 'none';
            document.getElementById('untuk_bulan_single').value = "";
        }
    });

    // 3. Switch mode single vs multi
    document.querySelectorAll('input[name="mode_pembayaran"]').forEach(radio => {
        radio.addEventListener('change', function() {
            let mode = this.value;
            let singleContainer = document.getElementById('container_single_bulan');
            let multiContainer = document.getElementById('container_multi_bulan');

            if(mode === 'multi') {
                singleContainer.style.display = 'none';
                multiContainer.style.display = 'block';
                document.getElementById('untuk_bulan_single').value = "";
                calculateMultiRange();
            } else {
                singleContainer.style.display = 'block';
                multiContainer.style.display = 'none';
                document.getElementById('hidden_inputs_multi').innerHTML = '';
                updateSinglePreview();
                if(tarifSppPerBulan > 0) {
                    document.getElementById('nominal_uang').value = tarifSppPerBulan;
                    document.getElementById('hint_nominal').textContent = 'Tarif normal 1 bulan: Rp ' + tarifSppPerBulan.toLocaleString('id-ID');
                }
            }
        });
    });

    // 4. Update preview single month & alert jika sudah lunas
    function updateSinglePreview() {
        let bln = document.getElementById('untuk_bulan_single').value;
        let thn = document.getElementById('untuk_tahun_single').value;
        let label = document.getElementById('label_periode_single');
        let alertBox = document.getElementById('alert_sudah_lunas_single');

        if(bln && thn) {
            let periode = bln + ' ' + thn;
            label.textContent = periode;

            if(bulanSudahLunas.includes(periode)) {
                alertBox.style.display = 'inline-block';
            } else {
                alertBox.style.display = 'none';
            }
        } else {
            label.textContent = '-';
            alertBox.style.display = 'none';
        }
    }

    document.getElementById('untuk_bulan_single').addEventListener('change', updateSinglePreview);
    document.getElementById('untuk_tahun_single').addEventListener('change', updateSinglePreview);

    // 5. AJAX untuk deteksi SPP & riwayat lunas siswa
    document.getElementById('siswa_id').addEventListener('change', function() {
        let idSiswa = this.value;
        let infoCard = document.getElementById('info_spp_siswa');
        let infoTarif = document.getElementById('info_tarif_spp');
        let infoTerakhir = document.getElementById('info_terakhir_lunas');
        let containerList = document.getElementById('container_list_lunas');
        let listBadges = document.getElementById('list_bulan_lunas');

        if(idSiswa) {
            fetch(`/keuangan/transaksi/get-spp/${idSiswa}`)
                .then(response => response.json())
                .then(data => {
                    tarifSppPerBulan = data.nominal_spp ? Math.round(data.nominal_spp) : 0;
                    bulanSudahLunas = data.sudah_dibayar || [];

                    // Update UI Info
                    infoTarif.textContent = 'Rp ' + tarifSppPerBulan.toLocaleString('id-ID');
                    infoCard.style.display = 'block';

                    if(bulanSudahLunas.length > 0) {
                        infoTerakhir.innerHTML = '<span class="text-success"><i class="fas fa-check-circle mr-1"></i>Total Lunas: ' + bulanSudahLunas.length + ' Bulan</span>';
                        containerList.style.display = 'block';
                        listBadges.innerHTML = bulanSudahLunas.map(b => `<span class="badge badge-success px-2 py-1"><i class="fas fa-check"></i> ${b}</span>`).join(' ');
                    } else {
                        infoTerakhir.innerHTML = '<span class="text-muted"><i class="fas fa-clock mr-1"></i>Belum ada data pembayaran</span>';
                        containerList.style.display = 'none';
                        listBadges.innerHTML = '';
                    }

                    // Isi nominal default pada mode single
                    let isSingle = document.getElementById('mode_single').checked;
                    if(isSingle && tarifSppPerBulan > 0) {
                        document.getElementById('nominal_uang').value = tarifSppPerBulan;
                    }

                    updateSinglePreview();
                    calculateMultiRange();
                })
                .catch(error => console.error('Gagal mengambil data tarif SPP:', error));
        } else {
            infoCard.style.display = 'none';
            bulanSudahLunas = [];
            tarifSppPerBulan = 0;
            updateSinglePreview();
            calculateMultiRange();
        }
    });

    // 6. Logika Perhitungan Rentang Bulan (Multi-Bulan dengan Dropdown)
    function calculateMultiRange() {
        let m1Val = document.getElementById('multi_dari_bulan').value;
        let y1Val = document.getElementById('multi_dari_tahun').value;
        let m2Val = document.getElementById('multi_sampai_bulan').value;
        let y2Val = document.getElementById('multi_sampai_tahun').value;

        let badgeCount = document.getElementById('badge_count_multi');
        let listText = document.getElementById('list_terpilih_multi');
        let alertInvalid = document.getElementById('alert_multi_invalid');
        let hiddenContainer = document.getElementById('hidden_inputs_multi');

        if(!m1Val || !m2Val) {
            badgeCount.textContent = '0 bulan dipilih';
            listText.innerHTML = '<span class="text-muted">Pilih bulan awal dan bulan akhir untuk menghitung total.</span>';
            alertInvalid.style.display = 'none';
            hiddenContainer.innerHTML = '';
            return;
        }

        let m1 = parseInt(m1Val);
        let y1 = parseInt(y1Val);
        let m2 = parseInt(m2Val);
        let y2 = parseInt(y2Val);

        // Validasi: Sampai tidak boleh sebelum Dari
        if((y2 < y1) || (y2 === y1 && m2 < m1)) {
            alertInvalid.style.display = 'inline-block';
            badgeCount.textContent = '0 bulan';
            listText.innerHTML = '<span class="text-danger font-weight-bold">Urutan bulan tidak valid. Pastikan bulan akhir tidak mendahului bulan awal.</span>';
            hiddenContainer.innerHTML = '';
            return;
        }

        alertInvalid.style.display = 'none';

        let allMonthsInRange = [];
        let unpaidMonths = [];
        let alreadyPaidMonths = [];
        let curY = y1;
        let curM = m1;

        while(curY < y2 || (curY === y2 && curM <= m2)) {
            let namaBln = daftarBulanList[curM - 1];
            let periode = namaBln + ' ' + curY;
            allMonthsInRange.push(periode);

            if(bulanSudahLunas.includes(periode)) {
                alreadyPaidMonths.push(periode);
            } else {
                unpaidMonths.push(periode);
            }

            curM++;
            if(curM > 12) {
                curM = 1;
                curY++;
            }
        }

        let count = unpaidMonths.length;
        badgeCount.textContent = count + ' bulan akan dibayar';

        // Render badge dan info
        let htmlList = '';
        if(unpaidMonths.length > 0) {
            htmlList += '<div class="mb-1 font-weight-bold text-success"><i class="fas fa-check-circle mr-1"></i>Bulan Baru yang Akan Dibayar (' + count + ' bulan):</div>';
            htmlList += unpaidMonths.map(b => `<span class="badge badge-primary mr-1 mb-1 p-1" style="font-size: 0.8rem;"><i class="fas fa-calendar-check mr-1"></i>${b}</span>`).join('');
        } else {
            htmlList += '<div class="alert alert-warning py-1 px-2 mb-1 small font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i>Semua bulan dalam rentang ini sudah lunas sebelumnya. Tidak ada tagihan baru.</div>';
        }

        if(alreadyPaidMonths.length > 0) {
            htmlList += '<div class="mt-2 mb-1 small text-muted font-weight-bold"><i class="fas fa-info-circle mr-1 text-info"></i>Dilewati Otomatis (Sudah Lunas):</div>';
            htmlList += alreadyPaidMonths.map(b => `<span class="badge badge-secondary mr-1 mb-1 p-1" style="font-size: 0.8rem;"><i class="fas fa-check mr-1"></i>${b} (Lunas)</span>`).join('');
        }

        listText.innerHTML = htmlList;

        // Masukkan HANYA bulan yang belum lunas ke hidden input bulan_dipilih[]
        hiddenContainer.innerHTML = unpaidMonths.map(b => `<input type="hidden" name="bulan_dipilih[]" value="${b}">`).join('');

        // Hitung nominal otomatis HANYA untuk bulan yang belum lunas
        if(tarifSppPerBulan > 0) {
            let totalNominal = count * tarifSppPerBulan;
            document.getElementById('nominal_uang').value = totalNominal;
            document.getElementById('hint_nominal').textContent = count > 0 
                ? `${count} bulan baru x Rp ${tarifSppPerBulan.toLocaleString('id-ID')} = Rp ${totalNominal.toLocaleString('id-ID')}`
                : 'Tidak ada bulan baru yang perlu dibayar.';
        }

        // Keterangan otomatis
        let ketInput = document.getElementById('keterangan_transaksi');
        let dariStr = daftarBulanList[m1 - 1] + ' ' + y1;
        let sampaiStr = daftarBulanList[m2 - 1] + ' ' + y2;
        if(count > 0) {
            ketInput.value = `Pembayaran SPP ${count} bulan: ${unpaidMonths.join(', ')}`;
        } else {
            ketInput.value = '';
        }
    }

    // Pasang listener pada keempat dropdown multi-range
    document.getElementById('multi_dari_bulan').addEventListener('change', calculateMultiRange);
    document.getElementById('multi_dari_tahun').addEventListener('change', calculateMultiRange);
    document.getElementById('multi_sampai_bulan').addEventListener('change', calculateMultiRange);
    document.getElementById('multi_sampai_tahun').addEventListener('change', calculateMultiRange);
</script>
@endsection
