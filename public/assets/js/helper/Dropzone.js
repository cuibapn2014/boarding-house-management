const Dropzone = {
    files: [],
    index: 1,
    destroy: function(selector) {
        $(document).off('click', selector);
        $(document).off('change', '.dropzone-input-file');
        $(document).off('click', '.btn-remove-preview-item')
    },
    init: function (selector) {
        const dropZone = $(selector);
        const inputFile = dropZone.find("input.dropzone-input-file");

        const accepts = [
            "image/png",
            "image/jpeg",
            "image/jpg",
            "image/webp",
            "video/mp4",
        ];

        $(document).on('click', selector, function (e) {
            if ($(e.target).hasClass('dropzone') || $(e.target).hasClass('none-file'))
                inputFile.click();
        });

        $(document).on('click', '.btn-remove-preview-item', function(e) {
            const target = $(this);

            Dropzone.files = Dropzone.files.filter(item => item.name !== target.data('file-name'));
            if(Dropzone.files.length <= 0) {
                $('.none-file').show();
            }

            $(this).parent().remove();
        })

        $(document).on('change', '.dropzone-input-file', function (e) {
            const file = e.target.files[0];
            const fileUrl = URL.createObjectURL(file);
            const previewBox = $(this)
                .closest(".dropzone")
                .find(".dropzone-preview-files");
            var img = document.createElement("img");
            var div = document.createElement('div');
            var removeBtn = document.createElement('a');
            var tag = document.createElement('span');
            var extFile = file.name.split('.').reverse()[0];
            var fileName = file.name.split('.').slice(0, -1).join('') + `_${Dropzone.index}`;
            
            div.classList.add("dropzone__item-preivew");
            div.classList.add("rounded");
            img.classList.add("dropzone__item-preivew-img");
            removeBtn.classList.add('btn-remove-preview-item');
            removeBtn.classList.add('text-danger');
            removeBtn.innerHTML = `<i class="fa-solid fa-circle-xmark"></i>`;
            div.appendChild(removeBtn);
            div.appendChild(img);

            if (!accepts.includes(file.type)) {
                GlobalHelper.toastError("File upload không được hỗ trợ!");
                inputFile.val("");

                return;
            }

            $('.none-file').hide();

            if (file.type.includes("image")) {
                img.src = fileUrl;
                tag.innerHTML = `<i class="fa-solid fa-image"></i>`;
                tag.classList.add('dropzone__item-tag');
                tag.classList.add('bg-dark');

                removeBtn.setAttribute('data-file-name', `${fileName}.${extFile}`)

                div.appendChild(tag);
                previewBox.append(div);
            }

            if (file.type.includes("video")) {
                var video = document.createElement("video");
                var canvas = document.createElement("canvas");
                var ctx = canvas.getContext("2d");

                const url = URL.createObjectURL(file);
                video.src = url;

                tag.innerHTML = `<i class="fa-solid fa-video fa-sm"></i>`;
                tag.classList.add('dropzone__item-tag');
                tag.classList.add('bg-dark');
                div.appendChild(tag);
                removeBtn.setAttribute('data-file-name', `${fileName}.${extFile}`)

                video.addEventListener("loadeddata", () => {
                    // Đặt kích thước canvas theo kích thước video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Đặt thời gian mong muốn để lấy thumbnail (ví dụ: 1 giây)
                    video.currentTime = 1;
                });

                video.addEventListener("seeked", () => {
                    // Vẽ khung hình lên canvas
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Chuyển canvas thành hình ảnh
                    const thumbnailUrl = canvas.toDataURL("image/png");
                    img.src = thumbnailUrl;

                    previewBox.append(div);

                    // Giải phóng bộ nhớ
                    URL.revokeObjectURL(video.src);
                });
            }

            Dropzone.index++;
            Dropzone.files.push(new File([file], `${fileName}.${extFile}`, {type: file.type, endings: file.endings, lastModified: file.lastModified}));
            inputFile.val('');
        });
    },
};
