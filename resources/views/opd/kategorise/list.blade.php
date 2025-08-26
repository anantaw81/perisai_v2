@extends('adminlte::page')

@section('title', 'Kategori SE ' . strtoupper($kategori))

@section('content_header')
    <h1>Sistem Elektronik Kategori {{ strtoupper($kategori) }}</h1>
@endsection

@section('content_top_nav_left')
    <li class="nav-item d-none d-sm-inline-block">
        <span class="nav-link font-weight-bold">
            Tahun Aktif: {{ $tahunAktifGlobal ?? '-' }}
            @if ($kunci === 'locked')
                <i class="fas fa-lock text-danger ml-1" title="Terkunci"></i>
            @endif
            :: {{ strtoupper($namaOpd) }}
        </span>
    </li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <div class="d-flex mb-3" style="gap: 10px;">
                <a href="{{ route('opd.kategorise.index') }}" class="btn btn-secondary mb-3 me-2">
                    ← Kembali
                </a>
                <a href="{{ route('opd.kategorise.export_rekap_kategori', ['kategori' => $kategori]) }}"
                    class="btn btn-danger mb-3">

                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
            <table id="kategoriseTable" class="table table-bordered table-hover" style="width:100%; table-layout: fixed;">

                <thead>
                    <tr>
                        <th>Nama Aset</th>
                        <th>Sub Klasifikasi</th>
                        <th>Lokasi</th>
                        <th>Skor Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $aset)
                        <tr>
                            <td>{{ $aset->nama_aset }}</td>
                            <td>{{ $aset->subklasifikasiaset->subklasifikasiaset ?? '-' }}</td>
                            <td>{{ $aset->lokasi }}</td>
                            <td>
                                {{-- {{ $aset->kategoriSe->skor_total ?? 'BELUM DINILAI' }} --}}
                                @php
                                    $skor = $aset->kategoriSe->skor_total ?? null;

                                    if ($skor === null) {
                                        echo '<span class="badge badge-secondary">BELUM DINILAI</span>';
                                    } else {
                                        $range = $rangeSes->first(function ($r) use ($skor) {
                                            return $skor >= $r->nilai_bawah && $skor <= $r->nilai_atas;
                                        });

                                        $warna = $range->warna_hexa ?? '#999';
                                        $label = $range->nilai_akhir_aset ?? 'TIDAK DIKETAHUI';

                                        echo '<span class="badge" style="background-color: ' .
                                            $warna .
                                            '; color: #fff;">' .
                                            $skor .
                                            ' (' .
                                            strtoupper($label) .
                                            ')</span>';
                                    }
                                @endphp
                            </td>

                            <td>

                                <a href="{{ route('opd.kategorise.exportPdf', $aset->uuid) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @if ($kunci !== 'locked')
                                    <a href="{{ route('opd.kategorise.edit', ['aset' => $aset, 'kategori' => $kategori]) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function() {
            $('#kategoriseTable').DataTable({
                autoWidth: false,
                columnDefs: [{
                        width: "auto",
                        targets: 0
                    },
                    {
                        width: "auto",
                        targets: 1
                    },
                    {
                        width: "100px",
                        targets: 2
                    },
                    {
                        width: "100px",
                        targets: 3
                    },
                    {
                        width: "100px",
                        targets: 4
                    },
                ]
            });
        });
    </script>
@endsection
