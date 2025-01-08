const GlobalHelper = {
    toastSuccess: (message) => {
        Toastify({
            text: message,
            duration: 3000,
            // destination: "https://github.com/apvarun/toastify-js",
            // newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: false, // Prevents dismissing of toast on hover
            style: {
              background: "#2dce89",
            },
            // onClick: function(){} // Callback after click
          }).showToast();
    },

    toastError: (message) => {
        Toastify({
            text: message,
            duration: 3000,
            // destination: "https://github.com/apvarun/toastify-js",
            // newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: false, // Prevents dismissing of toast on hover
            style: {
              background: "#f5365c",
            },
            // onClick: function(){} // Callback after click
          }).showToast();
    },

    initTinyEditor: function(selector) {
      $(selector).tinymce({
        height: 500,
        menubar: false,
        forced_root_block: false, // Ngăn không cho TinyMCE tự động thêm <p>
        force_br_newlines: true,   // Sử dụng <br> khi nhấn Enter
        force_p_newlines: false, 
        plugins: [
          'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
          'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
          'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic backcolor | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist outdent indent | removeformat | help'
      });
    },

    initValueSearchForm: function() {
      const searchParam = new URLSearchParams(window.location.search);

      searchParam.forEach((value, key) => {
        if(key) {
          $(`[name="${key}"]`).val(value);
        }
      })
    }
}