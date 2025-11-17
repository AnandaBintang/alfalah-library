import './bootstrap';
import 'preline'
import Swal from 'sweetalert2'
window.Swal = Swal


import { Html5QrcodeScanner } from "html5-qrcode";

document.addEventListener('DOMContentLoaded', () => {
  const readerElement = document.getElementById('reader');

  if (!readerElement) return;

  let isScanning = true;

  function onScanSuccess(decodedText, decodedResult) {
    if (!isScanning) return;
    isScanning = false;

    console.log("Scan berhasil:", decodedText);

    window.Livewire.dispatch('qrCodeScanned', { qrCode: decodedText });

    setTimeout(() => isScanning = true, 3000);
  }

  function onScanFailure(error) {
    window.Livewire.dispatch('qrErrorScanned', {message: error.message});
  }

  const scanner = new Html5QrcodeScanner(
    "reader",
    { fps: 10, qrbox: { width: 250, height: 250 } },
    false
  );

  scanner.render(onScanSuccess, onScanFailure);
});
