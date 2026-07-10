import http from 'k6/http';
import { check, sleep } from 'k6';

export default function () {
  // 1. Akses halaman login Laravel untuk mengambil CSRF Token
  // Ganti URL di bawah jika port atau domain project Laravel Anda berbeda
  let loginPage = http.get('http://localhost:8000/admin/login');
  
  // Memastikan halaman login terbuka (status 200)
  check(loginPage, {
    'halaman login berhasil dibuka': (r) => r.status === 200,
  });

  // 2. Mengambil CSRF Token otomatis dari form login Laravel
  let csrfToken = loginPage.html().find('input[name="_token"]').attr('value');

  // 3. Data login yang akan dikirim ke Laravel
  let loginPayload = {
    _token: csrfToken,
    email: 'admin@mail.com', // Gantilah dengan email yang terdaftar di database Laravel Anda
    password: '12345678',   // Gantilah dengan password akun tersebut
  };

  let loginParams = {
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  };

  // 4. Proses menembak login (POST request)
  let response = http.post('http://localhost:8000/admin/login', loginPayload, loginParams);

  // 5. Validasi: Laravel biasanya me-redirect (status 302) ke dashboard jika sukses login
  check(response, {
    'login berhasil': (r) => r.status === 302 || r.status === 200,
  });

  // Jeda 1 detik sebelum user melakukan request lagi
  sleep(1);
}