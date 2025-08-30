@extends('adminlte::page')

@section('title', 'PTKKA')

@section('content_header')
    <h1>Penilaian Tingkat Kepatuhan Keamanan Aplikasi (PTKKA)</h1>
    <div style="line-height:1.2; font-size: 0.9em">
        Kepgub Bali No. 584/03-E/HK/2024 tentang Pedoman Manajemen Aset Keamanan Informasi dan Standar Teknis
        dan Prosedur Keamanan SPBE di Lingkungan Pemerintah Provinsi Bali
        </p>
    @endsection

    @section('content_top_nav_left')
        <li class="nav-item d-none d-sm-inline-block">
            <span class="nav-link font-weight-bold">
                Tahun Aktif: {{ $tahunAktifGlobal ?? '-' }}
                @if ($kunci === 'locked')
                    <i class="fas fa-lock text-danger ml-1" title="Terkunci"></i>
                @endif
                :: {{ strtoupper($namaOpd ?? '-') }}
            </span>
        </li>
    @endsection

    @section('content')
        <div class="card">
            <div class="card-body">
                <table id="ptkkaTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="100px">Kode Aset</th>
                            <th>Nama Aset</th>
                            <th width="200px">Skor PTKKA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($asets as $aset)
                            @php
                                $session = $aset->ptkkaTerakhir;
                                $skor = $session ? $session->jawabans->sum('jawaban') : null;
                            @endphp
                            <tr>
                                <td>
                                    {{-- <a href="{{ route('opd.ptkka.riwayat', $aset->id) }}"
                                        class="text-primary font-weight-bold">
                                        {{ $aset->kode_aset }}
                                    </a> --}}
                                    <a href="{{ route('opd.ptkka.riwayat', $aset) }}" class="text-primary font-weight-bold">
                                        {{ $aset->kode_aset }}
                                    </a>
                                </td>
                                <td>{{ $aset->nama_aset }}</td>
                                <td>
                                    @if ($aset->ptkkaTerakhir)
                                        <div
                                            class="badge badge-{{ $aset->ptkkaTerakhir->kategori_kepatuhan === 'TINGGI'
                                                ? 'success'
                                                : ($aset->ptkkaTerakhir->kategori_kepatuhan === 'SEDANG'
                                                    ? 'warning'
                                                    : 'danger') }} p-2">
                                            {{ $aset->ptkkaTerakhir->kategori_kepatuhan }}
                                            {{ $aset->ptkkaTerakhir->persentase }}%
                                        </div>
                                    @else
                                        <span class="text-muted">Belum Pernah</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada aset ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
    @section('js')
        <script>
            $(function() {
                $('#ptkkaTable').DataTable({
                    autoWidth: false,
                    pageLength: 50
                });
            });
        </script>
    @endsection
