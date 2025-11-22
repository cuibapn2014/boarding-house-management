const ApiHelper = {
    callApi: async function(__url, __method, __data = {}, __headers = {}, additional = {},__beforeSend = null, __success = null, __error = null, __complete = null, showLoading = false, loadingText = 'Đang tải') {
        // Show loading if enabled
        if(showLoading && typeof GlobalHelper !== 'undefined' && GlobalHelper.showLoading) {
            GlobalHelper.showLoading(loadingText);
        }

        return await $.ajax({
            url: __url,
            method: __method,
            ...additional,
            headers: __headers,
            data: __data,
            beforeSend: function(xhr) {
                if(__beforeSend) {
                    __beforeSend(xhr);
                } else {
                    ApiHelper._beforeSend(xhr);
                }
            },
            success: __success ?? ApiHelper._success,
            error: __error ?? ApiHelper._error,
            complete: function(xhr, status) {
                // Hide loading if enabled
                if(showLoading && typeof GlobalHelper !== 'undefined' && GlobalHelper.hideLoading) {
                    GlobalHelper.hideLoading();
                }
                
                if(__complete) {
                    __complete(xhr, status);
                } else {
                    ApiHelper._complete(xhr, status);
                }
            }
        });
    },

    _beforeSend: function(e) {

    },

    _success: function(result) {

    },

    _error: function(err) {
        let status = err.status;
        let errJSON = err?.responseJSON?.errors;
        
        if(status === 422) {
            GlobalHelper.toastError('Xảy ra lỗi vui lòng kiểm tra lại!');
            for(const key in errJSON) {
                $(`#${key}`).after(`<span class="input-error-message text-danger text-sm">${errJSON[key][0]}<span>`);
            }
        } 

        if(status === 500) {
            GlobalHelper.toastError('Lỗi không xác định');
        }
    },

    _complete: function() {

    }
}