<?php
/**
 * @var $getUrl string
 * @var $uploadUrl string
 * @var $deleteUrl string
 * @var $visible bool
 */
?>

@props([
    'getUrl',
    'uploadUrl',
    'deleteUrl',
    'visible',
])


@if($visible)


@pushOnce('styles')
    <style>
        .CodeMirror.dragover {
            border-color: transparent;
        }

        .CodeMirror.dragover:after {
            display: block;
            content: '+ Drop image here';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(246,246,246,.45);
            z-index: 99;
            text-align: center;
            border: 2px dashed #666;
            line-height: 250px;
            font-size: 20px;
            pointer-events: none;
        }

        .get-uploaded-images {
            cursor: pointer;
            margin-top: 3px;
        }

        .uploaded-images-container {
            display: none;
            position: absolute;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-height: 174px;
            min-width: 180px;
            margin-top: -170px;
            margin-left: 120px;
            background: #fff;
            z-index: 150;
            padding: 16px;
            box-shadow: 0 0 50px rgba(0,0,0,.1);
        }

        .uploaded-images-container .thumb {
            float: left;
            position: relative;
            width: 140px;
            height: 140px;
            margin-right: 10px;
            padding: 6px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            border: 1px solid #ccc;
            cursor: move;
        }

        .uploaded-images-container .thumb img {
            max-width: 100%;
            pointer-events: none;
        }

        .uploaded-images-container .thumb .info {
            position: absolute;
            bottom: 6px;
            left: 0;
            width: 100%;
            background: #fff;
        }
        .uploaded-images-container .thumb .info .title {
            font-size: 11px;
            cursor: auto;
        }

        .uploaded-images-container .thumb .info .delete {
            display: inline-block;
            margin-left: 10px;
            overflow: hidden;
            font-size: 16px;
            color: red;
            cursor: pointer;
            background: #f8f8f8;
            width: 16px;
            height: 16px;
            line-height: 15px;
            border-radius: 2px;
        }
    </style>
@endPushOnce
@pushOnce('scripts')
    <script>
        // make code scoped
        ;(() => {

            // run init when codemirror was initialized
            const interval = setInterval(() => {
                document.querySelectorAll('[data-codemirror-wrapper]').forEach(cmWrapperEl => {
                    const cmEl = cmWrapperEl.querySelector('.CodeMirror')
                    if (cmEl) {
                        new CmUploader(cmWrapperEl)
                        clearInterval(interval)
                    }
                })
            }, 250)

            // cm uploader class
            class CmUploader {

                constructor(cmWrapperEl) {
                    // define objects
                    this.cmElement = cmWrapperEl.querySelector('.CodeMirror')
                    this.cm = this.cmElement.CodeMirror
                    const uploaderWrapperEl = cmWrapperEl.querySelector('[data-uploader-wrapper]')
                    this.options = {
                        getUrl: uploaderWrapperEl.getAttribute('data-uploader-get-url'),
                        uploadUrl: uploaderWrapperEl.getAttribute('data-uploader-upload-url'),
                        deleteUrl: uploaderWrapperEl.getAttribute('data-uploader-delete-url'),
                    }

                    this.addBtnShowGalleryHandler(
                        cmWrapperEl.querySelector('.btn.get-uploaded-images'),
                        cmWrapperEl.querySelector('.uploaded-images-container')
                    )

                    this.addDragHandlers()
                }


                addBtnShowGalleryHandler(btn, gallery) {
                    gallery.style.display = 'none'
                    btn.addEventListener('click', e => {
                        if (gallery.style.display === 'none') {
                            gallery.style.display = 'block'
                            this.getUploadedImages(gallery)
                        } else {
                            gallery.style.display = 'none'
                        }
                    })
                }


                // get uploaded images ad add to image gallery
                getUploadedImages(gallery) {
                    gallery.innerHTML = 'Loading...'

                    axios.get(this.options.getUrl)
                        .then(res => {
                            const images = res.data
                            if (images.length) {
                                gallery.innerHTML = ''
                                images.forEach(img => {
                                    const thumbEl = document.createElement('div')
                                    thumbEl.innerHTML = `
                        <div class="thumb" draggable="true" data-title="${img.title}">
                            <img class="thumb-image" src="${img.thumb}" />
                            <div class="info">
                                <span class="title">${img.title}</span>
                                <span class="delete" title="Delete image">&times</span>
                            </div>
                        </div>`
                                    gallery.append(thumbEl.querySelector('div'))
                                })
                                this.bindDeleteActionBtns(gallery)
                            } else {
                                gallery.innerHTML = 'No images'
                            }
                        })
                        .catch(err => {
                            window.notify({ type: 'danger', message: err.message })
                        })
                }


                // bind action for delete image button
                bindDeleteActionBtns(gallery) {
                    gallery.querySelectorAll('.thumb').forEach(thumb => {
                        const filename = thumb.getAttribute('data-title')
                        thumb.querySelector('.delete').addEventListener('click', e => {
                            axios.patch(this.options.deleteUrl, { files: [filename] })
                                .then(res => {
                                    thumb.style.display = 'none'
                                    if (res.data.filesCount === 0) {
                                        gallery.innerHTML = 'No images'
                                    }
                                })
                                .catch(err => {
                                    window.notify({ type: 'danger', message: err.message })
                                })
                        })
                    })
                }


                // paste fileurl to cursor at codemirror area
                insertImgToCodemirror(fileurl) {
                    const imgString = '<img src="' + fileurl + '" />'

                    const doc = this.cm.getDoc()
                    const cursor = doc.getCursor() // cursor position in textarea
                    this.cm.replaceRange(imgString, cursor) // insert link to textarea
                    this.cm.focus()
                }


                // upload image
                uploadFile(data, callback) {
                    axios({
                        url: this.options.uploadUrl,
                        method: 'post',
                        data,
                    })
                        .then(res => {
                            callback(res.data.file_url)
                        })
                        .catch(err => {
                            window.notify({ type: 'danger', message: err.message })
                        })
                }


                // add handlers on drag&drop over codemirror area
                // todo: try to add listener on cm
                addDragHandlers() {
                    let dragThumb = null

                    // on dragstart: if dragged gallery thumb, keep them
                    document.addEventListener('dragstart', e => {
                        if (e.target.classList.contains('thumb')) {
                            dragThumb = e.target
                        } else {
                            dragThumb = null
                        }
                    }, false)


                    // on dragover: set codemirror cursor position from mouse pointer
                    document.addEventListener('dragover', e => {
                        e.preventDefault() // prevent open link (for some elements)
                        const xy = { left: e.x, top: e.y }
                        const pos = this.cm.coordsChar(xy, 'string')
                        this.cm.setCursor(pos)
                    }, false)


                    // on dragenter: add style
                    document.addEventListener('dragenter', e => {
                        const cmTarget = e.target.classList.contains('CodeMirror')
                            ? e.target
                            : e.target.closest('.CodeMirror')

                        if (cmTarget) {
                            this.cmElement.classList.add('dragover')
                        } else {
                            this.cmElement.classList.remove('dragover')
                        }
                    }, false)


                    // on dragend: remove style
                    document.addEventListener('dragend', e => {
                        this.cmElement.classList.remove('dragover')
                    }, false)


                    /*document.addEventListener('dragleave', function(e) {}, false)*/


                    // on drop: if file -> upload file, if thumb -> add thumb url
                    document.addEventListener('drop', e => {

                        const cmTarget = e.target.classList.contains('CodeMirror')
                            ? e.target
                            : e.target.closest('.CodeMirror')

                        // if target is codemirror
                        if (cmTarget) {

                            e.preventDefault()

                            const files = e.dataTransfer.files // FileList object

                            // if drop files, upload them
                            if (files.length > 0) {

                                // console.log('upload files', files)
                                for (let i = 0; i < files.length; i++) {
                                    const data = new FormData() // FormData class for collect files
                                    data.append('file', files[i]) // add first file to FormData object
                                    this.uploadFile(data, fileurl => {
                                        this.insertImgToCodemirror(fileurl)
                                    })
                                }

                                // if drop thumb, add thumb url
                            } else if (dragThumb.nodeType) {

                                // insert image
                                const fileurl = dragThumb.querySelector('img').getAttribute('src')
                                this.insertImgToCodemirror(fileurl)
                            }
                        }

                        this.cmElement.classList.remove('dragover')

                    }, false)

                }

            }

        })()

    </script>
@endPushOnce



<div class="mb-3" style="margin-top: -12px"
     data-uploader-wrapper
     data-uploader-get-url="{{ $getUrl }}"
     data-uploader-upload-url="{{ $uploadUrl }}"
     data-uploader-delete-url="{{ $deleteUrl }}"
>
    <div class="get-uploaded-images btn btn-info btn-sm text-white">Show uploaded images</div>
    <div class="uploaded-images-container"></div>
</div>


@endif

