<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTS Pemrograman Basis Data - Dashboard Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
</head>
<body style="background-color: #F5F5F5;">
<div class="container mt-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <div>
            <h2 class="mb-1">Dashboard Transaksi Pelanggan</h2>
            <h5 class="text-secondary mb-0">
                411241145 - Kevin Gunawan
            </h5>
        </div>

        <img 
            src="https://kuliahonline.undira.ac.id/pluginfile.php/1/core_admin/logo/0x200/1757983608/LOGO%20UNDIRA.png" 
            alt="Logo UNDIRA" 
            style="max-height: 50px;" 
            class="d-none d-sm-block"
        >
    </div>
    
    {{-- KODE POPUP SUKSES --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000 
        })
    </script>
    @endif
    
    {{-- KODE ALERT VALIDASI ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Validasi Gagal!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <hr>

    <h3 class="mt-4 mb-3">Ringkasan Data</h3>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Jumlah Pelanggan</h5>
                    <p class="card-text h1">{{ $totalPelanggan }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Jumlah Transaksi</h5>
                    <p class="card-text h1">{{ $totalTransaksi }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    
    <h3 class="mt-4">Grafik Transaksi Pelanggan (Bar Chart)</h3>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <canvas id="transaksiChart" style="max-height: 400px;"></canvas>
            <script>
                // Script Chart.js Anda
                const ctx = document.getElementById('transaksiChart');
                const labels = @json($grafikData->pluck('nama_pelanggan'));
                const dataValues = @json($grafikData->pluck('total')); 

                new Chart(ctx, {
                    type: 'bar', 
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Transaksi (Rp)',
                            data: dataValues,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)', 
                                'rgba(255, 99, 132, 0.7)', 
                                'rgba(255, 206, 86, 0.7)', 
                                'rgba(75, 192, 192, 0.7)', 
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)'
                            ],
                            borderColor: 'rgba(0, 0, 0, 0.5)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total Transaksi (Rp)'
                                },
                                ticks: {
                                    callback: function(value, index, ticks) {
                                        return 'Rp ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>

    <hr>

    <h3 class="mb-3">Tambah Data Baru</h3>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">Input Data</div>
        <div class="card-body p-0">
            <ul class="nav nav-tabs" id="dataTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi-pane" type="button" role="tab" aria-controls="transaksi-pane" aria-selected="true">
                        Tambah Transaksi Baru
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pelanggan-tab" data-bs-toggle="tab" data-bs-target="#pelanggan-pane" type="button" role="tab" aria-controls="pelanggan-pane" aria-selected="false">
                        Tambah Pelanggan Baru
                    </button>
                </li>
            </ul>

            <div class="tab-content border border-top-0 p-3" id="dataTabContent">
                
                {{-- Panel Transaksi --}}
                <div class="tab-pane fade show active" id="transaksi-pane" role="tabpanel" aria-labelledby="transaksi-tab">
                    <div class="card-body p-0">
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="id_pelanggan" class="form-label">Pilihan Pelanggan</label>
                                <select class="form-select" id="id_pelanggan" name="id_pelanggan" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach ($pelanggan as $p)
                                        <option value="{{ $p->id_pelanggan }}" {{ old('id_pelanggan') == $p->id_pelanggan ? 'selected' : '' }}>
                                            {{ $p->nama_pelanggan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                                <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="total_transaksi" class="form-label">Total Transaksi</label>
                                <input type="number" class="form-control" id="total_transaksi" name="total_transaksi" value="{{ old('total_transaksi') }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        </form>
                    </div>
                </div>

                {{-- Panel Pelanggan --}}
                <div class="tab-pane fade" id="pelanggan-pane" role="tabpanel" aria-labelledby="pelanggan-tab">
                    <div class="card-body p-0">
                        <form action="{{ route('pelanggan.store') }}" method="POST">
                            @csrf 
                            <div class="mb-3"><label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                                <input type="text" class="form-control" name="nama_pelanggan" required></div>
                            <div class="mb-3"><label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required></div>
                            <div class="mb-3"><label for="no_hp" class="form-label">No. HP</label>
                                <input type="text" class="form-control" name="no_hp"></div>
                            <div class="mb-3"><label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat"></div>
                            <button type="submit" class="btn btn-warning">Simpan Pelanggan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <h3 class="mt-4">Daftar Transaksi</h3>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarTransaksi as $transaksi)
                    <tr>
                        <td>{{ $transaksi->id_transaksi }}</td>
                        <td>{{ $transaksi->nama_pelanggan }}</td>
                        <td>{{ $transaksi->email }}</td>
                        <td>{{ $transaksi->tanggal_transaksi }}</td>
                        <td>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var dataTabEl = document.getElementById('dataTab');
        var dataTab = new bootstrap.Tab(dataTabEl);
        
        dataTabEl.addEventListener('shown.bs.tab', function (event) {
            localStorage.setItem('activeTab', event.target.id);
        });

        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            var triggerEl = document.getElementById(activeTab);
            if (triggerEl) {
                 bootstrap.Tab.getInstance(triggerEl).show();
            }
        }
    </script>

</div>
</body>
</html>