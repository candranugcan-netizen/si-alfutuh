{{-- Modal Pratinjau & Unduh Dokumen Siswa / PPDB / Alumni --}}
<div class="modal fade" id="modalDokumenSiswa" tabindex="-1" role="dialog" aria-labelledby="modalDokumenSiswaTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            {{-- Header Modal --}}
            <div class="modal-header bg-white px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center mr-3 shadow-sm"
                        style="width: 42px; height: 42px; min-width: 42px; border-radius: 10px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-folder-open fa-lg"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center flex-wrap">
                            <h5 class="modal-title font-weight-bold text-gray-900 mb-0" id="modalDokumenSiswaTitle">
                                Dokumen Siswa
                            </h5>
                            <span class="badge badge-pill badge-primary ml-2 px-2 py-1 font-weight-bold" id="modalDokNama" style="font-size: 0.8rem;">
                                Nama Siswa
                            </span>
                        </div>
                        <small class="text-muted" id="modalDokInfo">Informasi Berkas</small>
                    </div>
                </div>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close" style="outline: none; font-size: 1.5rem; opacity: 0.6;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Navigasi Tab Dokumen --}}
            <div class="px-4 pt-3 pb-2 bg-light border-bottom" style="border-color: #edf2f7 !important;">
                <div class="nav nav-pills nav-fill" id="dokumenTabPills" role="tablist">
                    <button type="button" class="nav-link btn btn-sm btn-doc-tab active font-weight-bold mr-1 mb-1 shadow-sm"
                        id="tabDocFOTO" data-type="foto" style="border-radius: 8px;">
                        <i class="fas fa-camera mr-1"></i> Foto Siswa
                        <span id="modalBadgeFoto" class="ml-1"></span>
                    </button>
                    <button type="button" class="nav-link btn btn-sm btn-doc-tab font-weight-bold mr-1 mb-1 shadow-sm"
                        id="tabDocKK" data-type="kk" style="border-radius: 8px;">
                        <i class="fas fa-id-card mr-1"></i> Kartu Keluarga (KK)
                        <span id="modalBadgeKk" class="ml-1"></span>
                    </button>
                    <button type="button" class="nav-link btn btn-sm btn-doc-tab font-weight-bold mb-1 shadow-sm"
                        id="tabDocAKTA" data-type="akta" style="border-radius: 8px;">
                        <i class="fas fa-scroll mr-1"></i> Akta Kelahiran
                        <span id="modalBadgeAkta" class="ml-1"></span>
                    </button>
                </div>
            </div>

            {{-- Body Modal: Area Pratinjau --}}
            <div class="modal-body p-3 text-center" style="background-color: #f8fafc; min-height: 420px; display: flex; align-items: center; justify-content: center;">
                <div id="modalDocPreview" class="w-100 d-flex align-items-center justify-content-center">
                    {{-- Dinamis diisi via JavaScript --}}
                    <div class="text-muted py-5">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <p class="small mb-0">Memuat pratinjau dokumen...</p>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="modal-footer bg-white px-4 py-3 d-flex justify-content-between align-items-center border-top" style="border-color: #f1f5f9 !important;">
                <div class="small text-muted d-none d-sm-block">
                    <i class="fas fa-info-circle mr-1 text-info"></i>
                    Sedang melihat: <strong id="modalDocLabel" class="text-dark">Foto Siswa</strong>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <a href="#" target="_blank" class="btn btn-sm btn-outline-secondary font-weight-bold px-3 py-2 shadow-sm"
                        id="btnModalExternal" style="border-radius: 8px;" title="Buka berkas di tab baru">
                        <i class="fas fa-external-link-alt mr-1"></i> Buka Penuh
                    </a>
                    <a href="#" class="btn btn-sm btn-success font-weight-bold px-3 py-2 shadow-sm"
                        id="btnModalDownload" style="border-radius: 8px;" title="Download berkas dokumen ini">
                        <i class="fas fa-download mr-1"></i> Download Dokumen
                    </a>
                    <button type="button" class="btn btn-sm btn-light border px-3 py-2 font-weight-bold"
                        data-dismiss="modal" style="border-radius: 8px;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof jQuery === 'undefined') return;

        var activeDocType = 'foto'; // 'foto', 'kk', 'akta'
        var currentData = {
            nama: '',
            info: '',
            foto: '',
            fotoFile: '',
            kk: '',
            kkFile: '',
            akta: '',
            aktaFile: ''
        };

        function getCategoryLabel(type) {
            if (type === 'foto') return 'Foto Siswa';
            if (type === 'kk') return 'Kartu Keluarga (KK)';
            if (type === 'akta') return 'Akta Kelahiran';
            return 'Dokumen';
        }

        function renderActiveDocument() {
            var url = currentData[activeDocType];
            var file = currentData[activeDocType + 'File'];
            var label = getCategoryLabel(activeDocType);

            // Update tab styles
            $('.btn-doc-tab').removeClass('btn-primary active text-white').addClass('btn-light text-dark');
            $('#tabDoc' + activeDocType.toUpperCase()).removeClass('btn-light text-dark').addClass('btn-primary active text-white');

            $('#modalDocLabel').text(label);

            if (!url || url === '#' || url === 'null') {
                // Empty state
                $('#modalDocPreview').html(`
                    <div class="text-center py-5">
                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-file-excel fa-2x text-muted" style="opacity: 0.6;"></i>
                        </div>
                        <h6 class="font-weight-bold text-gray-800 mb-1">Berkas ${label} Belum Tersedia</h6>
                        <p class="text-muted small mb-0">Belum ada file ${label} yang diunggah untuk data ini.</p>
                    </div>
                `);

                $('#btnModalDownload')
                    .attr('href', '#')
                    .addClass('disabled')
                    .css('pointer-events', 'none')
                    .html('<i class="fas fa-download mr-1"></i> Download Dokumen');

                $('#btnModalExternal')
                    .attr('href', '#')
                    .addClass('disabled')
                    .css('pointer-events', 'none');
            } else {
                // Cek apakah berkas bertipe PDF
                var isPdf = url.toLowerCase().indexOf('.pdf') > -1;

                if (isPdf) {
                    $('#modalDocPreview').html(`
                        <div class="w-100" style="height: 480px;">
                            <iframe src="${url}" class="w-100 h-100" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff;"></iframe>
                        </div>
                    `);
                } else {
                    $('#modalDocPreview').html(`
                        <div class="text-center w-100 p-2" style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <img src="${url}" alt="${label}" class="img-fluid rounded" style="max-height: 460px; max-width: 100%; object-fit: contain;">
                        </div>
                    `);
                }

                // URL Download aman via route Laravel
                var downloadParamFile = file || url.substring(url.lastIndexOf('/') + 1);
                var downloadUrl = '{{ route("dokumen.download") }}?file=' + encodeURIComponent(downloadParamFile) +
                                  '&kategori=' + encodeURIComponent(label) +
                                  '&nama=' + encodeURIComponent(currentData.nama);

                $('#btnModalDownload')
                    .attr('href', downloadUrl)
                    .attr('download', label + '_' + currentData.nama)
                    .removeClass('disabled')
                    .css('pointer-events', 'auto')
                    .html('<i class="fas fa-download mr-1"></i> Download ' + label);

                $('#btnModalExternal')
                    .attr('href', url)
                    .removeClass('disabled')
                    .css('pointer-events', 'auto');
            }
        }

        // Event listener saat tombol Lihat Dokumen diklik
        $(document).on('click', '.btn-lihat-dokumen', function (e) {
            var btn = $(this);
            currentData.nama = btn.data('nama') || 'Siswa';
            currentData.info = btn.data('info') || '';
            currentData.foto = btn.data('foto') || '';
            currentData.fotoFile = btn.data('foto-file') || '';
            currentData.kk = btn.data('kk') || '';
            currentData.kkFile = btn.data('kk-file') || '';
            currentData.akta = btn.data('akta') || '';
            currentData.aktaFile = btn.data('akta-file') || '';

            // Tentukan tab aktif
            var requestedActive = btn.data('active');
            if (requestedActive && ['foto', 'kk', 'akta'].indexOf(requestedActive) > -1) {
                activeDocType = requestedActive;
            } else if (currentData.foto && currentData.foto !== '#' && currentData.foto !== 'null') {
                activeDocType = 'foto';
            } else if (currentData.kk && currentData.kk !== '#' && currentData.kk !== 'null') {
                activeDocType = 'kk';
            } else if (currentData.akta && currentData.akta !== '#' && currentData.akta !== 'null') {
                activeDocType = 'akta';
            } else {
                activeDocType = 'foto';
            }

            $('#modalDokNama').text(currentData.nama);
            $('#modalDokInfo').html(currentData.info || 'Berkas Dokumen');

            // Update badge ketersediaan pada masing-masing tab
            function setBadge(id, url) {
                if (url && url !== '#' && url !== 'null') {
                    $(id).html('<i class="fas fa-check-circle text-success" title="Berkas tersedia"></i>');
                } else {
                    $(id).html('<i class="fas fa-times-circle text-muted" title="Tidak ada berkas"></i>');
                }
            }

            setBadge('#modalBadgeFoto', currentData.foto);
            setBadge('#modalBadgeKk', currentData.kk);
            setBadge('#modalBadgeAkta', currentData.akta);

            renderActiveDocument();
        });

        // Event listener ganti tab dokumen
        $(document).on('click', '.btn-doc-tab', function () {
            activeDocType = $(this).data('type');
            renderActiveDocument();
        });
    });
</script>
