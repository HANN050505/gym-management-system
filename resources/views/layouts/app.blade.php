<!DOCTYPE html>
<html>
<head>
    <title>Gym Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">

    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 bg-dark text-white min-vh-100">

            <h3 class="mt-3 text-center">
                GYM APP
            </h3>

            <hr>

            <ul class="nav flex-column">

                <li class="nav-item mb-2">
                    <a
                        href="/dashboard-gym"
                        class="nav-link text-white"
                    >
                        🏠 Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a
                        href="/members"
                        class="nav-link text-white"
                    >
                        👤 Member
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a
                        href="/paket-memberships"
                        class="nav-link text-white"
                    >
                        📦 Paket Membership
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a
                        href="/transaksi-membership"
                        class="nav-link text-white"
                    >
                        💳 Transaksi
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a
                        href="/absensi"
                        class="nav-link text-white"
                    >
                        📷 Absensi QR
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a
                        href="/riwayat-absensi"
                        class="nav-link text-white"
                    >
                        📋 Riwayat Absensi
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a
                        href="/laporan-member"
                        class="nav-link text-white"
                    >
                        📊 Laporan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a
                        href="/notifikasi"
                        class="nav-link text-white"
                    >
                        🔔 Notifikasi
                    </a>
                </li>

            </ul>

        </div>

        <!-- Content -->
        <div class="col-md-10 p-4">

            @yield('content')

        </div>

    </div>

</div>

</body>
</html>