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
      // Check if TinyMCE is loaded
      if(typeof tinymce === 'undefined') {
        console.error('TinyMCE is not loaded. Please include TinyMCE script.');
        return;
      }

      // Check if element exists
      const element = document.querySelector(selector);
      if(!element) {
        console.error(`Element ${selector} not found for TinyMCE`);
        return;
      }

      // Remove existing instance if any
      const existingEditor = tinymce.get(element.id);
      if(existingEditor) {
        existingEditor.remove();
      }

      // Initialize TinyMCE
      tinymce.init({
        selector: selector,
        height: 500,
        menubar: false,
        forced_root_block: false,
        force_br_newlines: true,
        force_p_newlines: false,
        plugins: [
          'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
          'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
          'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic backcolor | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist outdent indent | removeformat | help',
        setup: function(editor) {
          editor.on('init', function() {
            console.log('✅ TinyMCE editor initialized:', selector);
          });
        }
      });
    },

    initValueSearchForm: function() {
      const searchParam = new URLSearchParams(window.location.search);

      searchParam.forEach((value, key) => {
        if(key) {
          $(`[name="${key}"]`).val(value);
        }
      })
    },

    urlImgToFile: async function(urlImg, outputFileName) {
      const response = await fetch(urlImg);
      const blob = response.blob();
      const file = new File([blob], outputFileName, {type: blob.type});

      return file;
    },

    showLoading: function(text = 'Đang tải') {
      const loadingOverlay = $('#globalLoading');
      const loadingText = loadingOverlay.find('.loading-text');
      
      // Update text if provided
      if(text) {
        loadingText.html(`
          ${text}
          <span class="loading-dots">
            <span></span>
            <span></span>
            <span></span>
          </span>
        `);
      }
      
      // Show loading with smooth animation
      loadingOverlay.addClass('show');
      $('body').css('overflow', 'hidden'); // Prevent scrolling
    },

    hideLoading: function() {
      const loadingOverlay = $('#globalLoading');
      
      // Hide loading with smooth animation
      setTimeout(() => {
        loadingOverlay.removeClass('show');
        $('body').css('overflow', ''); // Restore scrolling
      }, 300); // Small delay for better UX
    }
}