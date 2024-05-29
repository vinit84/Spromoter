"use strict";
(function () {
    const fullToolbar = [
        ['bold', 'italic', 'underline', 'strike'],
        [
            {
                color: []
            },
            {
                background: []
            }
        ],
        [
            {
                list: 'ordered'
            },
            {
                list: 'bullet'
            },
            {
                indent: '-1'
            },
            {
                indent: '+1'
            }
        ],
        [{ direction: 'rtl' }],
        ['clean']
    ];
    const fullEditor = new Quill('#body', {
        bounds: '#body',
        placeholder: trans('Type Something...'),
        modules: {
            toolbar: fullToolbar
        },
        theme: 'snow'
    });

    fullEditor.root.innerHTML = emailBody;

    // Shortcode
    $('.shortcode-btn').on('click', function () {
        let shortcode = $(this).data('shortcode');
        let range = fullEditor.getSelection(true);
        fullEditor.insertText(range.index, shortcode);
    });

    $('#reviewRequestForm').on('formSubmitBefore', function (e, formData, form, options) {
        formData.push({
            name: 'body',
            value: fullEditor.root.innerHTML
        })
    });

    $('#reviewRequestForm').on('formSubmitSuccess', function (){
        loadPreview();
    });

    $('#reviewRequestForm').on('formSubmitComplete', function (){
        $('#previewCard').unblock();
    });

    function setIframeHeight() {
        let iframe = document.getElementById('previewIframe');
        if (iframe) {
            iframe.style.height = (iframe.contentWindow.document.body.offsetHeight - 400) + 'px';
        }
    }

    // Call the function initially and whenever the content changes
    setIframeHeight();
    document.getElementById('previewIframe').onload = setIframeHeight;

    function loadPreview() {
        $.ajax({
            url: route('user.emails.email-setup.review-request-email-preview'),
            type: 'GET',
            dataType: 'json',
            beforeSubmit: function () {
                previewSpinner();
            },
            success: function (response) {
                document.getElementById('previewIframe').srcdoc = response.preview;
                document.getElementById('previewIframe').onload = setIframeHeight;
                $('#previewCard').unblock();
                blockDefault();
            },
        })
    }
    loadPreview();

    function previewSpinner() {
        $('#previewCard').block({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: {
                backgroundColor: 'transparent',
                color: '#fff',
                border: '0',
                cursor: 'default'
            },
            overlayCSS: {
                opacity: 0.2
            }
        });
    }

    function blockDefault() {
        // $('#previewCard').block({
        //     message: '',
        //     css: {
        //         backgroundColor: 'transparent',
        //         color: '#fff',
        //         border: '0',
        //         cursor: 'default'
        //     },
        //     overlayCSS: {
        //         opacity: 0,
        //         cursor: 'default'
        //     }
        // });
    }

    $('#testEmailForm').on('ajaxFormSuccess', function (){
        $('#test-email-modal').modal('hide');
    });
})();
