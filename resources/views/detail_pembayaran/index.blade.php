@extends('main')

@section('title', 'Pembayaran')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <a class="btn btn-info btn-sm mt-2" href="/pembayaran" style="border-radius:20px;"><i class="fa fa  fa-arrow-circle-o-left" style="margin-right: 7px"></i>Kembali || <span>Detail Pembayaran</span></a>

        </div>
    </div>
</div>

@endsection

@section('content')
<div class="animated fadeIn">
    <div class="card">
        <div class="card-header">
            <div class="pull-left pl-3">
                @if($resultLastPayment == 0 || $resultLastPayment <= 0)
                <button type="button" class="btn btn-success" id="addData" data-toggle="modal" data-target="#modal-add-pembayaran" data-idpem="{{ $idPembayaran }}" data-idpi="{{ $idPiutang }}" disabled>
                    <i class="fa fa-plus"></i> Pembayaran
                </button>
                @else                <button type="button" class="btn btn-success" id="addData" data-toggle="modal" data-target="#modal-add-pembayaran" data-idpem="{{ $idPembayaran }}" data-idpi="{{ $idPiutang }}">
                    <i class="fa fa-plus"></i> Pembayaran
                </button>
                @endif
            </div>
            <div class="card-body table-responsive" style="overflow-y: scroll; height:400px;">
                <table class="table table-bordered table-sm" id="service-table">
                    <thead>
                        <tr>
                            <th>No. Pembayaran</th>
                            <th>Tanggal Pembayaran</th>
                            <th>No. Invoice</th>
                            <th>Nama Debitur</th>
                            <th>Total Tagihan</th>
                            <th>Total Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center" colspan="2">Aksi</th>
                            {{-- <th class="text-center">Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($getPembayaran as $item)
                        @if ($loop->first) @continue @endif
                        <tr id="row-data">
                            <td>{{ $item->no_pembayaran }}</td>
                            <td>{{ $item->tgl_pembayaran->isoFormat('D MMMM Y') }}</td>
                            <td>{{ $item->no_invoice }}</td>
                            <td>{{ $item->nm_debitur}}</td>
                            <td>@currency($item->sisa_tagihan)</td>
                            <td class="text-center">@currency($item->total_pembayaran)</td>
                            <td>{{$item->status}}</td>
                            <td>
                                <form action="/hapus-detail-pembayaran" method="POST">
                                    @csrf
                                    <input type="text" name="id" id="id" value="{{$item->id}}" hidden>
                                    <input type="text" name="id_piutang" value="{{$idPiutang}}" hidden>
                                    <input type="text" name="total_tagihan" id="" value="{{$tagihan}}" hidden>
                                    <input type="text" name="id_pembayaran" value="{{$item->id_pembayaran}}" hidden>
                                    <input type="text" name="total_pembayaran" value="{{$item->total_pembayaran}}" hidden>
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            </td>

                            <td>
                                <button type="button" class="btn btn-outline-info btn-sm" id="btn-cetak-kwitansi" data-toggle="modal" data-target="#modal-cetak-kwitansi"
                                    data-no-pembayaran="{{ $item->no_pembayaran }}"
                                    data-total-pembayaran="{{$item->total_pembayaran}}"
                                    data-nm-debitur="{{ $item->nm_debitur }}"
                                    data-tgl-pembayaran="{{ $item->tgl_pembayaran->isoFormat('D MMMM Y') }}"

                                    data-tgl-pengajuan="{{ $item->tgl_pengajuan->isoFormat('MMMM Y') }}">
                                    <i class="fa fa-print"></i> Cetak Kwitansi
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pull-right bg-secondary pl-2 pr-2 text-white rounded">
                    @if($resultLastPayment < 0)
                    <h4 class="pt-2 pb-2">Rp. 0</h4>
                    @else
                    <h4 class="pt-2 pb-2">Sisa Tagihan: @currency($resultLastPayment)</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-cetak-kwitansi" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Print Properties</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/cetak-kwitansi" method="POST" target="_blank">
                    @csrf
                    <div class="form-group">
                        <label for="no_pembayaran" class=" form-control-label">No. Pembayaran</label>
                        <input type="text" id="no_pembayaran" class="form-control" name="no_pembayaran" readonly>
                    </div>
                    <div class="form-group">
                        <label for="total_pembayaran" class=" form-control-label">Total Pembayaran</label>
                        <input type="number" id="total_pembayaran" class="form-control" name="total_pembayaran" readonly/>
                    </div>
                    <div class="form-group">
                        <label for="nm_debitur" class=" form-control-label">Nama Debitur</label>
                        <input type="text" id="nm_debitur" class="form-control" name="nm_debitur" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tgl_pembayaran" class=" form-control-label">Tanggal Pembayaran</label>
                        <input type="text" id="tgl_pembayaran" class="form-control" name="tgl_pembayaran" readonly>
                    </div>
                    <div class="form-group">
                        <label for="periode" class=" form-control-label">Periode</label>
                        <input type="text" id="periode" class="form-control" name="periode" placeholder="Misal: Satu Juta Rupiah" readonly>
                    </div>
                    <div class="form-group">
                        <label for="uang_sejumlah" class=" form-control-label">Uang Sejumlah</label>
                        <input type="text" id="uang_sejumlah" class="form-control" name="uang_sejumlah" placeholder="Misal: Satu Juta Rupiah" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class=" form-control-label">Keterangan</label>
                        <textarea type="text" id="keterangan" class="form-control" name="keterangan" maxlength="50" placeholder="Maksimal 50 Karakter (termasuk spasi)" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Cetak Kwitansi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



{{-- MODAL TAMBAH PEMBAYARAN --}}
<div class="modal fade" id="modal-add-pembayaran" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Pembayaran </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/pay-transaction" method="POST">
                    @csrf
                    <p id="nama"></p>
                        <input type="number" id="idPembayaranModal" class="form-control" name="id_pembayaran" hidden>
                        <input type="number" id="idPiutangModal" class="form-control" name="id_piutang"hidden>
                        <input type="number" id="total_tagihan" class="form-control" name="total_tagihan" value="{{ $totalTagihan }}" hidden>
                    <div class="form-group">
                        <label for="nama" class=" form-control-label">Tanggal Pembayaran</label>
                        <input type="date" id="tanggal_pembayaran" class="form-control" name="tanggal_pembayaran" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="nama" class=" form-control-label">Nominal Pembayaran</label>
                        <input type="number" id="total_pembayaran" class="form-control" name="total_pembayaran">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
        $(document).on('click', '#btn-cetak-kwitansi', function() {
            var no_pembayaran = $(this).data('no-pembayaran');
            var total_pembayaran = $(this).data('total-pembayaran');
            var nm_debitur = $(this).data('nm-debitur');
            var tgl_pembayaran = $(this).data('tgl-pembayaran');
            let periode = $(this).data('tgl-pengajuan');

            $('#no_pembayaran').val(no_pembayaran);
            $('#total_pembayaran').val(total_pembayaran);
            $('#nm_debitur').val(nm_debitur);
            $('#tgl_pembayaran').val(tgl_pembayaran);
            $('#periode').val(periode);
            })
        })

    $(document).ready(function() {
        $(document).on('click', '#addData', function() {
            var id_pembayaran = $(this).data('idpem');
            var id_piutang = $(this).data('idpi');

            $('#idPembayaranModal').val(id_pembayaran);
            $('#idPiutangModal').val(id_piutang);
            })
        })
</script>
@endsection
