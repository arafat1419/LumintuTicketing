let optionTicket = [{ nama: 'No Selected Ticket', harga: 0, capacity: 0 }]; //Array Jenis Ticket
let sumTicket = [0]; // Array Jumlah Data Penjualan per Ticket
let statusPemesanan = []; // Array Status Invitation
let pembelian = []; // Array menampung harga tiket pilihan
let ip = 'api-ticket.arisukarno.xyz'; // IP API




$(`#useVoucher`).click(() => {
  const voucherCode = $(`#voucherCode`).val();
  let voucherAvailable = false;

  $.ajax({
    url: `https://${ip}/items/voucher`,
    type: 'GET',
    dataType : 'json',
    success: function (data, textStatus, xhr) {
      // data.data.map((item) => {
      //   if(item.voucher_code == voucherCode) {
      //     voucherAvailable = true;
      //     console.log(item.voucher_id);
      //     // http://api-ticket.arisukarno.xyz:8055/items/ticket_x_voucher?fields=voucher_id.*,ticket_id.ticket_type,ticket_id.ticket_price&filter[voucher_id.voucher_id]=1

      //       $.ajax({
      //         url: `https://${ip}/items/order?fields=voucher_id.*,invoice_id.invoice_id,invoice_id.invoice_status&filter[voucher_id.voucher_id]=${item.voucher_id}`,
      //         type: 'GET',
      //         dataType : 'json',
      //         success: function (data, textStatus, xhr) {
      //           if (data.data.length > 0) {
                  
      //             let voucherStock = data.data[0].voucher_id.voucher_stock;
      //             let invoiceExipired = 0;
      //             let invoiceId = [];

      //             data.data.map((item2) => {
      //               // console.log(item2.invoice_id);
      //               if(!(invoiceId.includes(item2.invoice_id.invoice_id))) {
      //                 if(item2.invoice_id.invoice_status == 2) {
      //                   invoiceExipired++;
      //                 }
      //                 invoiceId.push(item2.invoice_id.invoice_id);
      //               }
      //             })

      //             let stokVoucher = voucherStock - invoiceId.length + invoiceExipired;
      //             console.log("Stok Voucher - " + stokVoucher);

      //             if (stokVoucher > 0) {
      //               addTicket(item.voucher_id, voucherCode); // ADD TICKET IF VOUCHER HAS BEEN USED
      //             } else {
      //               alert("Voucher telah habis");
      //             }
      //           } else {
      //             addTicket(item.voucher_id, voucherCode); // ADD TICKET IF VOUCHER NEVER USED
      //           }
      //         },
      //         error: function (xhr, textStatus, errorThrown) {
      //           console.log('Error in Database');
      //         }
      //       })
      //   }
      // })
      data.data.map((item) => {
        if(item.voucher_code == voucherCode) {
          voucherAvailable = true;
          $.ajax({
            url: `https://${ip}/items/voucher?filter[voucher_id]=${item.voucher_id}`,
            type: 'GET',
            dataType : 'json',
            success: function (data, textStatus, xhr) {
              console.log(data);
              if (data.data.length > 0) {
                let voucherAvailable = data.data[0].voucher_available;
                console.log(voucherAvailable);  
                if (voucherAvailable > 0) {
                  addTicket(item.voucher_id, voucherCode); // ADD TICKET IF VOUCHER HAS BEEN USED
                } else {
                  alert("Voucher telah habis");
                }
              } else {
                addTicket(item.voucher_id, voucherCode); // ADD TICKET IF VOUCHER NEVER USED
              }
            },
            error: function (xhr, textStatus, errorThrown) {
              console.log('Error in Database');
            }
          })
        }
      })
      if(!voucherAvailable) {
        alert("Kode Voucher salah");
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log('Error in Database');
    }
  })
  
});

function addTicket(voucherId, voucherCode) {
  $.ajax({
    url: `https://api-ticket.arisukarno.xyz/items/ticket_x_voucher?fields=voucher_id.*,ticket_id.ticket_type,ticket_id.ticket_price,ticket_id.ticket_seat&filter[voucher_id.voucher_id]=${voucherId}`,
    type: 'GET',
    dataType : 'json',
    success: function (data, textStatus, xhr) {
      // optionTicket=[];
      optionTicket = [{ nama: 'No Selected Ticket', harga: 0, capacity: 0 }];
      data.data.map((item) => {
        console.log(item.ticket_id.ticket_type);
        optionTicket.push({
                nama: item.ticket_id.ticket_type,
                harga: item.ticket_id.ticket_price,
                capacity: item.ticket_id.ticket_seat,
              });
      })
      $('.custom-select').find('option')
      .remove()
      .end();
      optionTicket.map((item, index) => {
        if (optionTicket[index].capacity != null) {
          if (optionTicket[index].capacity == 0) {
            $('.custom-select').append(`<option value="${index}">${item.nama}</option>`);
          } else {
            $('.custom-select').append(`<option value="${index}">${item.nama} (${item.capacity - sumTicket[index]})</option>`);
          }
        }
      });
      alert(`Use Voucher : ${voucherCode}`);
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log('Error in Database');
    }
  })
}

// $.ajax({
//   url: `https://${ip}/items/voucher`,
//   type: 'GET',
//   dataType : 'json',
//   success: function (data, textStatus, xhr) {
//     console.log("Voucher" + data);
//     data.data.map((item) => {
//       console.log(optionTicket)
//     })
//   },
//   error: function (xhr, textStatus, errorThrown) {
//     console.log('Error in Database');
//   }
// })

// AJAX untuk mengambil Jumlah Data Penjualan per Ticket
$.ajax({
  url: `https://${ip}/items/order?aggregate[sum]=order_quantity&groupBy[]=ticket_id`,
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
    url: `https://${ip}/items/ticket/`,
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
        url: `https://${ip}/items/invitation?fields=invitation_id,customer_id.customer_email,customer_id.customer_name,customer_inviter_id.customer_email,invitation_status&filter[customer_inviter_id][customer_code]=${params}`,
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
