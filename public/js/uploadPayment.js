const DROP_AREA = $(".drag-area")
const DRAG_TEXT = $(".h5")
const BUTTON_CHECKOUT = $('.btn-checkout')
const BUTTON_DELETE = $('.btn-hapus')
const IMAGE = $('.img-tag')
const VALID_EXTENSIONS = ["image/jpeg", "image/jpg", "image/png"]

// Event saat drag di dalam dropArea
DROP_AREA.on("dragover", (event) => {
  event.preventDefault()
  DROP_AREA.addClass("active")
  DRAG_TEXT.text("Release to Upload File")
})

// Event saat drag di luar dropArea
DROP_AREA.on("dragleave", () => {
  DROP_AREA.removeClass("active")
  DRAG_TEXT.text("Drag & Drop to Upload File")
})

// Event saat telah drop file/image di dropArea
DROP_AREA.on("drop", (event) => {
  event.preventDefault()
  file = event.originalEvent.dataTransfer.files[0]
  showFile()
})


$('#inputGroupFile').on("change", function () {
  file = this.files[0];
  showFile()
  DROP_AREA.addClass("active")
})

let deleteImage = () => {
  let imgTag =
    `
    <div class="container-petunjuk">
      <i class="fa fa-cloud-upload icon"></i>
      <p class="h5">Drag & Drop to Upload File</p>
    </div>
  `
  DROP_AREA.html(imgTag)
  DROP_AREA.removeClass("active")
}

// Function untuk menampilkan file di dropArea
let showFile = () => {
  let fileType = file.type
  if (VALID_EXTENSIONS.includes(fileType)) {
    let fileReader = new FileReader()
    fileReader.onload = () => {
      let fileURL = fileReader.result
      if (IMAGE.length == 0) {
        let imgTag = `<img src="${fileURL}" alt="" class="img-tag">`
        DROP_AREA.html(imgTag)
      } else {
        IMAGE.attr('src', fileURL)
      }
    }
    BUTTON_CHECKOUT.attr('disabled', false)
    BUTTON_DELETE.attr('disabled', false)
    fileReader.readAsDataURL(file)
  } else {
    alert("This is not an Image File")
    DROP_AREA.classList.remove("active")
  }
}
