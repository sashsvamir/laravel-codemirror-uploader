<?php
/**
 * @var $model \Illuminate\Database\Eloquent\Model
 */
?>

@props([
    'model',
])


@if(!!$model->getKey())

@php
    $model_alias = \Sashsvamir\LaravelCodemirrorUploader\Config::getModelAlias($model::class);
    $route_name = \Sashsvamir\LaravelCodemirrorUploader\Config::getRouteName($model_alias);
    $route_url = route($route_name);
    $model_id = $model->getKey();
@endphp


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

        .btn-get-uploaded-images {
            position: absolute;
            right: 12px;
            bottom: 17px;
            width: 32px;
            height: 30px;
            background: white center no-repeat;
            background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGlkPSJMYXllcl8xIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA2NCA2NDsiIHZlcnNpb249IjEuMSIgdmlld0JveD0iMCAwIDY0IDY0IiB4bWw6c3BhY2U9InByZXNlcnZlIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOiMxMzQ1NjM7fQo8L3N0eWxlPjxnPjxnIGlkPSJJY29uLUltYWdlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyNzguMDAwMDAwLCAyMzIuMDAwMDAwKSI+PHBhdGggY2xhc3M9InN0MCIgZD0iTS0yMjYuMi0xODEuNmgtMzkuNWMtMi4zLDAtNC4yLTEuOS00LjItNC4ydi0yOC4yYzAtMi4zLDEuOS00LjIsNC4yLTQuMmgzOS41ICAgICBjMi4zLDAsNC4yLDEuOSw0LjIsNC4ydjI4LjJDLTIyMi0xODMuNS0yMjMuOS0xODEuNi0yMjYuMi0xODEuNkwtMjI2LjItMTgxLjZ6IE0tMjY1LjgtMjE1LjVjLTAuOCwwLTEuNCwwLjYtMS40LDEuNHYyOC4yICAgICBjMCwwLjgsMC42LDEuNCwxLjQsMS40aDM5LjVjMC44LDAsMS40LTAuNiwxLjQtMS40di0yOC4yYzAtMC44LTAuNi0xLjQtMS40LTEuNEgtMjY1LjhMLTI2NS44LTIxNS41eiIgaWQ9IkZpbGwtMTIiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNLTIzOC45LTIwMS41Yy0zLjEsMC01LjUtMi41LTUuNS01LjVzMi41LTUuNSw1LjUtNS41czUuNSwyLjUsNS41LDUuNSAgICAgUy0yMzUuOS0yMDEuNS0yMzguOS0yMDEuNUwtMjM4LjktMjAxLjV6IE0tMjM4LjktMjEwYy0xLjYsMC0yLjksMS4zLTIuOSwyLjljMCwxLjYsMS4zLDIuOSwyLjksMi45YzEuNiwwLDIuOS0xLjMsMi45LTIuOSAgICAgQy0yMzYtMjA4LjctMjM3LjMtMjEwLTIzOC45LTIxMEwtMjM4LjktMjEweiIgaWQ9IkZpbGwtMTMiLz48cG9seWxpbmUgY2xhc3M9InN0MCIgaWQ9IkZpbGwtMTQiIHBvaW50cz0iLTIzMS40LC0xODIuMSAtMjU0LjUsLTIwMy44IC0yNjcuNywtMTkxLjYgLTI2OS41LC0xOTMuNSAtMjU0LjUsLTIwNy40IC0yMjkuNiwtMTg0ICAgICAgLTIzMS40LC0xODIuMSAgICAiLz48cG9seWxpbmUgY2xhc3M9InN0MCIgaWQ9IkZpbGwtMTUiIHBvaW50cz0iLTIyNC4yLC0xODkuMyAtMjMxLjksLTE5NS41IC0yMzguMywtMTkwLjIgLTI0MCwtMTkyLjMgLTIzMS45LC0xOTguOSAtMjIyLjYsLTE5MS4zICAgICAgLTIyNC4yLC0xODkuMyAgICAiLz48L2c+PC9nPjwvc3ZnPg==');
            background-size: contain;
            border: 1px solid #ccc;
            border-radius: 4px;
            opacity: .4;
            transition: opacity .3s;
            cursor: pointer;
        }
        .btn-get-uploaded-images:hover {
            opacity: 1;
        }

        .uploaded-images-container {
            display: none;
            position: absolute;
            right: 50px;
            bottom: -30px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-height: 174px;
            min-width: 180px;
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
            max-height: 100%;
            pointer-events: none;
        }

        .uploaded-images-container .thumb .info {
            position: absolute;
            bottom: 6px;
            left: 0;
            width: 100%;
            background: rgba(255,255,255,.8);
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
                    this.cmWrapperElement = cmWrapperEl
                    this.cmElement = cmWrapperEl.querySelector('.CodeMirror')
                    this.cm = this.cmElement.CodeMirror
                    const uploaderWrapperEl = cmWrapperEl.querySelector('[data-uploader-wrapper]')

                    this.url = uploaderWrapperEl.getAttribute('data-uploader-url')
                    this.model_alias = uploaderWrapperEl.getAttribute('data-uploader-model-alias')
                    this.model_id = uploaderWrapperEl.getAttribute('data-uploader-model-id')

                    this.addBtnShowGalleryHandler(
                        cmWrapperEl.querySelector('.btn-get-uploaded-images'),
                        cmWrapperEl.querySelector('.uploaded-images-container')
                    )

                    this.dragThumb = null
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

                    // get
                    axios({
                        url: this.url,
                        method: 'post',
                        data: {
                            action: 'get',
                            model_alias: this.model_alias,
                            model_id: this.model_id,
                        },
                    })
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

                            // delete
                            axios({
                                url: this.url,
                                method: 'post',
                                data: {
                                    action: 'delete',
                                    model_alias: this.model_alias,
                                    model_id: this.model_id,
                                    files: [filename],
                                },
                            })
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
                /**
                 * @param {FormData} data
                 * @param {Function} callback
                 */
                uploadFile(data, callback) {

                    data.append('action', 'upload')
                    data.append('model_alias', this.model_alias)
                    data.append('model_id', this.model_id)

                    // upload
                    axios({
                        url: this.url,
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

                    // on dragstart: if dragged gallery thumb, keep them to drop later
                    this.cmWrapperElement.addEventListener('dragstart', e => {
                        if (e.target.classList.contains('thumb')) {
                            this.dragThumb = e.target
                        } else {
                            this.dragThumb = null
                        }
                    }, false)


                    // on dragover: set codemirror cursor position from mouse pointer
                    this.cmElement.addEventListener('dragover', e => {
                        e.preventDefault() // prevent open link (for some elements)
                        const xy = { left: e.x, top: e.y }
                        const pos = this.cm.coordsChar(xy, 'string')
                        this.cm.setCursor(pos)
                    }, false)


                    // on dragenter (adding on all document): add style
                    document.addEventListener('dragenter', e => {
                        const cmTarget = e.target.classList.contains('CodeMirror')
                            ? e.target
                            : e.target.closest('.CodeMirror')

                        if (cmTarget) {
                            cmTarget.classList.add('dragover') // add style only to specific cm element
                        } else {
                            this.cmElement.classList.remove('dragover') // remove style from all cm
                        }
                    }, false)


                    // on dragend (adding on all document): remove style
                    document.addEventListener('dragend', e => {
                        this.cmElement.classList.remove('dragover')
                    }, false)


                    /*document.addEventListener('dragleave', function(e) {}, false)*/


                    // on drop: if file -> upload file, if thumb -> add thumb url
                    this.cmElement.addEventListener('drop', e => {

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
                            } else if (this.dragThumb.nodeType) {

                                // insert image
                                const fileurl = this.dragThumb.querySelector('img').getAttribute('src')
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



<div class="mb-3" style="margin-top: -12px; position: relative;"
     data-uploader-wrapper
     data-uploader-model-alias="{{ $model_alias }}"
     data-uploader-model-id="{{ $model_id }}"
     data-uploader-url="{{ $route_url }}"
>
    <div class="btn-get-uploaded-images" title="Show uploaded images"></div>
    <div class="uploaded-images-container"></div>
</div>


@endif

