let ip = 'api-ticket.arisukarno.xyz';

$(document).ready(function () {
  $.ajax({
    url: `https://${ip}/items/event`,
    type: 'GET',
    dataType: 'json',
    beforeSend: function () {
      $('#loader').removeClass('d-none');
    },
    success: function (data, textStatus, xhr) {
      const event = data.data;
      console.log(event);
      event.map((item) => {
        let eventItem = `
            <div class="item item${item.event_id} rounded d-flex align-items-center" onmouseenter="tes(this)">
                <div class="container deskripsi p-2 rounded">
                    <div class="my-auto">
                        <p class="h5 nama-event text-center" onclick="tes(this)">${item.event_name}</p>
                        <p class="tanggal text-center">${moment(new Date(item.event_date_start)).format('D MMMM YYYY')} - ${moment(new Date(item.event_date_finished)).format('D MMMM YYYY')}</p>
                        <div class="mt-3 text-center more">
                            <a href="details.php" class="btn btn-more w-75 text-white">Click More</a>
                        </div>
                    </div>
                    
                </div>
            </div>
            `;
        $('.owl-carousel').append(eventItem);
        $(`.item${item.event_id}`).css('background-image', `url("https://${ip}/assets/${item.event_image}")`);
        console.log(item.event_id);
      });
      $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 50,
        nav: true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        responsive: {
          0: {
            items: 1,
          },
          600: {
            items: 2,
          },
          1000: {
            items: 3,
          },
        },
      });
    },
    complete: function (data) {
      // Hide image container
      $('#loader').addClass('d-none');
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log('Error in Database');
    },
  });

  $('.item .h5')
    .mouseenter(function () {
      console.log('Masuk');
    })
    .mouseleave(function () {
      console.log('Keluar');
    });
});

function tes(x) {
  console.log(x.className.split(' ')[0]);
}
