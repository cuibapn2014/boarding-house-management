const Dropzone = {
    files: [],
    index: 1,
    maxImages: 5,  // Default for free plan
    maxVideos: 1,  // Default for free plan
    maxImageBytes: 10 * 1024 * 1024, // 10MB
    maxVideoBytes: 50 * 1024 * 1024, // 50MB
    userPlan: 'free', // Default plan
    isAdmin: false, // Default not admin
    destroy: function(selector) {
        $(document).off('click', selector);
        $(document).off('change', '.dropzone-input-file');
        $(document).off('click', '.btn-remove-preview-item');
        
        // Remove drag & drop events
        const dropZoneElement = $(selector)[0];
        if(dropZoneElement) {
            dropZoneElement.removeEventListener('dragover', Dropzone._handleDragOver);
            dropZoneElement.removeEventListener('dragleave', Dropzone._handleDragLeave);
            dropZoneElement.removeEventListener('drop', Dropzone._handleDrop);
        }
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

        // Click to select files
        $(document).on('click', selector, function (e) {
            if ($(e.target).hasClass('dropzone') || $(e.target).hasClass('none-file'))
                inputFile.click();
        });

        // Drag & Drop Events
        const dropZoneElement = dropZone[0];
        if(dropZoneElement) {
            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZoneElement.addEventListener(eventName, Dropzone._preventDefaults, false);
                document.body.addEventListener(eventName, Dropzone._preventDefaults, false);
            });

            // Highlight drop zone when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZoneElement.addEventListener(eventName, Dropzone._handleDragOver, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZoneElement.addEventListener(eventName, Dropzone._handleDragLeave, false);
            });

            // Handle dropped files
            dropZoneElement.addEventListener('drop', Dropzone._handleDrop, false);
        }

        $(document).on('click', '.btn-remove-preview-item', function(e) {
            e.stopPropagation();
            const target = $(this);
            const fileName = target.data('file-name');

            Dropzone.files = Dropzone.files.filter(item => item.name !== fileName);
            
            if(Dropzone.files.length <= 0) {
                $('.none-file').show();
            }

            $(this).parent().fadeOut(300, function() {
                $(this).remove();
                Dropzone._updateFileCount(target.closest('.dropzone'));
            });
        })

        $(document).on('change', '.dropzone-input-file', function (e) {
            const files = e.target.files;
            const dropZone = $(this).closest('.dropzone');

            if(!files || files.length === 0) return;

            // Process each file
            Array.from(files).forEach(file => {
                Dropzone._createPreview(file, dropZone);
            });

            // Clear input value to allow selecting the same files again
            $(this).val('');
        });
    },

    // Drag & Drop Helper Functions
    _preventDefaults: function(e) {
        e.preventDefault();
        e.stopPropagation();
    },

    _handleDragOver: function(e) {
        const dropZone = $(e.currentTarget);
        dropZone.addClass('dropzone-active');
        dropZone.css({
            'background-color': 'rgba(94, 114, 228, 0.05)',
            'border-color': '#5e72e4',
            'transform': 'scale(1.02)',
            'transition': 'all 0.3s ease'
        });
    },

    _handleDragLeave: function(e) {
        const dropZone = $(e.currentTarget);
        dropZone.removeClass('dropzone-active');
        dropZone.css({
            'background-color': '',
            'border-color': '',
            'transform': 'scale(1)',
            'transition': 'all 0.3s ease'
        });
    },

    _handleDrop: function(e) {
        const dropZone = $(e.currentTarget);
        const dt = e.dataTransfer;
        const files = dt.files;

        // Reset dropzone styling
        Dropzone._handleDragLeave(e);

        // Process dropped files
        if(files && files.length > 0) {
            const inputFile = dropZone.find('.dropzone-input-file')[0];
            
            // Create a new FileList-like object
            const dataTransfer = new DataTransfer();
            for(let file of files) {
                dataTransfer.items.add(file);
            }
            
            // Set files to input and trigger change event
            inputFile.files = dataTransfer.files;
            $(inputFile).trigger('change');
        }
    },

    // Process files (common function for both click and drag & drop)
    _processFiles: function(files, dropZone) {
        const accepts = [
            "image/png",
            "image/jpeg",
            "image/jpg",
            "image/webp",
            "video/mp4",
        ];

        for(const file of files) {
            if (!accepts.includes(file.type)) {
                GlobalHelper.toastError(`File "${file.name}" không được hỗ trợ!`);
                continue;
            }

            Dropzone._createPreview(file, dropZone);
        }
    },

    _createPreview: function(file, dropZone) {
        // Validate file type
        const accepts = [
            "image/png",
            "image/jpeg",
            "image/jpg",
            "image/webp",
            "video/mp4",
        ];

        if (!accepts.includes(file.type)) {
            const msg = `File "${file.name}" không được hỗ trợ! (Chỉ PNG/JPG/JPEG/WEBP hoặc MP4)`;
            GlobalHelper.toastError(msg);
            Dropzone._appendError(dropZone, msg);
            return;
        }

        // Validate file size
        if (file.type.includes('image') && file.size > Dropzone.maxImageBytes) {
            const msg = `Ảnh "${file.name}" vượt quá 10MB.`;
            GlobalHelper.toastError(msg);
            Dropzone._appendError(dropZone, msg);
            return;
        }
        if (file.type.includes('video') && file.size > Dropzone.maxVideoBytes) {
            const msg = `Video "${file.name}" vượt quá 50MB.`;
            GlobalHelper.toastError(msg);
            Dropzone._appendError(dropZone, msg);
            return;
        }

        // Admin không bị giới hạn
        if (!Dropzone.isAdmin) {
            // Check file limits for free plan
            if (Dropzone.userPlan === 'free') {
                const currentImages = Dropzone.files.filter(f => f.type.includes('image')).length;
                const currentVideos = Dropzone.files.filter(f => f.type.includes('video')).length;

                if (file.type.includes('image') && currentImages >= Dropzone.maxImages) {
                    const msg = `Gói Free chỉ được phép tải lên tối đa ${Dropzone.maxImages} ảnh!`;
                    GlobalHelper.toastError(msg);
                    Dropzone._appendError(dropZone, msg);
                    return;
                }

                if (file.type.includes('video') && currentVideos >= Dropzone.maxVideos) {
                    const msg = `Gói Free chỉ được phép tải lên tối đa ${Dropzone.maxVideos} video!`;
                    GlobalHelper.toastError(msg);
                    Dropzone._appendError(dropZone, msg);
                    return;
                }
            }
        }

        const fileUrl = URL.createObjectURL(file);
        const previewBox = dropZone.find(".dropzone-preview-files");
        const extFile = file.name.split('.').reverse()[0];
        const fileName = file.name.split('.').slice(0, -1).join('') + `_${Dropzone.index}`;

        // Create elements
        const div = document.createElement('div');
        const img = document.createElement("img");
        const removeBtn = document.createElement('a');
        const tag = document.createElement('span');
        const loader = document.createElement('div');

        // Setup classes and attributes
        div.classList.add("dropzone__item-preivew", "rounded", "position-relative");
        img.classList.add("dropzone__item-preivew-img");
        removeBtn.classList.add('btn-remove-preview-item', 'text-danger');
        removeBtn.innerHTML = `<i class="fa-solid fa-circle-xmark"></i>`;
        removeBtn.setAttribute('data-file-name', `${fileName}.${extFile}`);
        
        // Loading spinner
        loader.classList.add('dropzone-loader');
        loader.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>';
        
        div.appendChild(loader);
        div.appendChild(removeBtn);
        div.appendChild(img);

        $('.none-file').hide();

        if (file.type.includes("image")) {
            img.onload = function() {
                // Remove loader when image is loaded
                loader.remove();
            };
            img.src = fileUrl;
            tag.innerHTML = `<i class="fa-solid fa-image"></i>`;
            tag.classList.add('dropzone__item-tag', 'bg-dark');
            div.appendChild(tag);
            previewBox.append(div);
        }

        if (file.type.includes("video")) {
            const video = document.createElement("video");
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            video.src = fileUrl;
            tag.innerHTML = `<i class="fa-solid fa-video fa-sm"></i>`;
            tag.classList.add('dropzone__item-tag', 'bg-dark');
            div.appendChild(tag);

            video.addEventListener("loadeddata", () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                video.currentTime = 1;
            });

            video.addEventListener("seeked", () => {
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const thumbnailUrl = canvas.toDataURL("image/png");
                img.src = thumbnailUrl;
                loader.remove(); // Remove loader
                previewBox.append(div);
                URL.revokeObjectURL(video.src);
            });
        }

        Dropzone.index++;
        Dropzone.files.push(new File([file], `${fileName}.${extFile}`, {
            type: file.type, 
            endings: file.endings, 
            lastModified: file.lastModified
        }));

        // Update file count
        Dropzone._updateFileCount(dropZone);
    },

    _appendError: function(dropZone, message) {
        try {
            const dz = $(dropZone);
            const box = dz.find('.dropzone-errors');
            if(!box.length) return;
            box.append(`<div class="text-danger text-sm input-error-message"><i class="fas fa-exclamation-circle me-1"></i>${message}</div>`);
        } catch(e) {
            // ignore
        }
    },

    _updateFileCount: function(dropZone) {
        const count = Dropzone.files.length;
        let countBadge = dropZone.find('.dropzone-file-count');

        if(count > 0) {
            if(countBadge.length === 0) {
                dropZone.append(`<span class="dropzone-file-count">${count} file</span>`);
            } else {
                countBadge.text(`${count} file${count > 1 ? 's' : ''}`);
            }
        } else {
            countBadge.remove();
        }
    }
};
