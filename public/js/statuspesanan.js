let optionTicket = [{ nama: 'No Selected Ticket', harga: 0, capacity: 0 }]; //Array Jenis Ticket
let sumTicket = [0]; // Array Jumlah Data Penjualan per Ticket
let statusPemesanan = []; // Array Status Invitation
let pembelian = []; // Array menampung harga tiket pilihan
let ip = 'arisukarno.xyz:8055'; // IP API

// AJAX untuk mengambil Jumlah Data Penjualan per Ticket
$.ajax({
  url: `http://${ip}/items/order?aggregate[sum]=order_quantity&groupBy[]=ticket_id`,
  type: 'GET',
  dataType: 'json',
  success: function (data, textStatus, xhr) {
    data.data.map((item) => {
      sumTicket.push(item.sum.order_quantity);
    });
  },
  error: function (xhr, textStatus, errorThrown) {
    console.log('Error in Database');
  },
});

// Memunculkan Harga
function priceShow(idClass, value) {
  $(`.price${idClass}`).html(optionTicket[value].harga);
  checkStatus();
  pembelian[idClass - 1] = optionTicket[value].harga;
  total(); // Memanggil Function Harga
}

// Mencari Total Pembelian
function total() {
  let total = 0;
  pembelian.map((item) => {
    total += item;
  });
  $('#total-harga').val(total);
}

// Switch untuk Button berdasarkan input
function checkStatus() {
  if (statusPemesanan.indexOf(false) == -1) {
    for (i = 1; i <= $('.custom-select').length; i++) {
      if ($(`#${i}`).val() == 0) {
        $('.btn-checkout').prop('disabled', true);
        break;
      } else {
        $('.btn-checkout').prop('disabled', false);
      }
    }
  } else {
    $('.btn-checkout').prop('disabled', true);
  }
}

$(document).ready(function () {
  let link = window.location.href;
  const url = new URL(link);

  let params = url.searchParams.get('m');

  // AJAX jenis Tiket
  $.ajax({
    url: `http://${ip}/items/ticket/`,
    type: 'GET',
    dataType: 'json',
    success: function (data, textStatus, xhr) {
      data.data.map((item) => {
        //Menyimpan Jenis Tiket ke Array
        if (item.ticket_seat != null) {
          optionTicket.push({
            nama: item.ticket_type,
            harga: item.ticket_price,
            capacity: item.ticket_seat,
          });
        }
      });

      let panjangOpsi = optionTicket.length - 1; //Mencari Length dari Array Jenis Ticket
      let penunjuk = panjangOpsi;
      for (i = 1; i < panjangOpsi; i++) {
        for (j = i + 1; j < panjangOpsi; j++) {
          optionTicket.splice(penunjuk, 0, {
            nama: `${optionTicket[i].nama} & ${optionTicket[j].nama}`,
            harga: optionTicket[i].harga + optionTicket[j].harga,
            capacity: Math.min(optionTicket[i].capacity, optionTicket[j].capacity),
          });

          sumTicket.splice(penunjuk, 0, Math.max(sumTicket[i], sumTicket[j]));
          penunjuk++;
          console.log(`${optionTicket[i].nama} ${optionTicket[j].nama}`);
        }
      }

      $.ajax({
        url: `http://${ip}/items/invitation?fields=invitation_id,customer_id.customer_email,customer_id.customer_name,customer_inviter_id.customer_email,invitation_status&filter[customer_inviter_id][customer_code]=${params}`,
        type: 'GET',
        dataType: 'json',
        success: function (data, textStatus, xhr) {

          console.log("Data jalan");
          console.log(data.data.length);

          data.data.map((item, index) => {
            pembelian.push(0);
            tableRow = `
                    <tr>
                        <td>
                            ${item.customer_id.customer_email}
                        </td>
                        <td>${item.customer_id.customer_name == null ? 'Belum Mengisi' : `${item.customer_id.customer_name}`}
                        </td>
                        ${
                          item.invitation_status == 1
                            ? `<td>
                            <select class="custom-select" id="${index + 1}" name="tiket-peserta-${index}" onchange="priceShow(this.id, this.value)"></select>
                          </td>
                          <td class= "price${index + 1}">${0}</td>
                          <td>
                              <div class="card shadow" style="width: 32px; height: 32px;">
                              <img src="../public/img/true.svg" alt=""></div>
                          </td>`
                            : `<td>
                            <select class="custom-select" id="${index + 1}" onchange="priceShow(this.id, this.value)" disabled></select></td>
                          <td class= "price${index + 1}">${0}</td>
                          <td>
                              <div class="card shadow" style="width: 32px; height: 32px;">
                              <img src="../public/img/false.svg" alt=""></div>
                          </td>
                          `
                        }
                    </tr>    
                    `;
            $('tbody').append(tableRow);
            if (item.invitation_status == 1) {
              statusPemesanan.push(true);
            } else {
              statusPemesanan.push(false);
            }
            console.log(statusPemesanan);
          });

          optionTicket.map((item, index) => {
            if (optionTicket[index].capacity != null) {
              if (optionTicket[index].capacity == 0) {
                $('.custom-select').append(`<option value="${index}">${item.nama}</option>`);
              } else {
                $('.custom-select').append(`<option value="${index}">${item.nama} (${item.capacity - sumTicket[index]})</option>`);
              }
            }
          });

          checkStatus();

          if (data.data.length == 1) {
            $('.voucher').removeClass('d-none');
            $('.table-status').DataTable({
              paging: false,
              searching: false,
              info: false,
              ordering: true,
              columnDefs: [
                {
                  orderable: false,
                  targets: 'no-sort',
                },
              ],
            });
          } else {
            $('.voucher').addClass('d-none');
            $('.table-status').DataTable({
              ordering: true,
              columnDefs: [
                {
                  orderable: false,
                  targets: 'no-sort',
                },
              ],
            });
          }
        },
        complete: function (data) {
          // Hide image container
          $('#loader').addClass('d-none');
        },
        error: function (xhr, textStatus, errorThrown) {
          console.log('Error in Database');
        },
      });
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log('Error in Database');
    },
  });

  // AJAX data Table
});

// function confirmOrder(){
//   swal.fire({
//     title: "Are you sure?",
//     text: "You will not be able to recover this imaginary file!",
//     icon: "warning",
//     buttons: [
//       'No, cancel it!',
//       'Yes, I am sure!'
//     ],
//     dangerMode: true,
//   })
// }

const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger',
  },
  buttonsStyling: false,
});

document.querySelector('#formPesanan').addEventListener('submit', function (e) {
  var form = this;

  e.preventDefault(); // <--- prevent form from submitting

  swal
    .fire({
      title: 'Are you sure?',
      text: 'You will not be able to change your choice!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, I am Sure',
      cancelButtonText: 'No, cancel it!',
      dangerMode: true,
    })
    .then(function (isConfirm) {
      if (isConfirm) {
        swal
          .fire({
            title: 'Success',
            text: 'Your Order is Completed!',
            icon: 'success',
          })
          .then(function () {
            form.submit();
          });
      } else {
        swal.fire('Cancelled', 'Make your better choice!', 'error');
      }
    });
});
