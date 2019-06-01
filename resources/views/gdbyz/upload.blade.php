@extends('gdbyz.base')

@section('title', '上传你的证件照了')



@section('content')
    <div class="byz-container">
        <div class="byz-form">
            <div class="form-item">
                <label class="form-upload" for="input-file" ref="upload">
                    <div class="upload-icon" v-show="icon_show">
                        <img class="upload-text" src="https://static.zjutjh.com/jxh/upload-text.png" alt="">
                        <div class="upload-add">
                            <img src="https://static.zjutjh.com/jxh/add.png" alt="">
                        </div>

                    </div>
                    <div class="upload-progress" v-show="progress">
                        <div class="svg">
                            <svg viewBox="0 0 100 100"><path d="
        M 50 50
        m 0 -47
        a 47 47 0 1 1 0 94
        a 47 47 0 1 1 0 -94
        " stroke="#e5e9f2" stroke-width="4.8" fill="none" class="el-progress-circle__track" style="stroke-dasharray: 295.31px, 295.31px; stroke-dashoffset: 0px;"></path><path ref="svg" d="
        M 50 50
        m 0 -47
        a 47 47 0 1 1 0 94
        a 47 47 0 1 1 0 -94
        " stroke="rgba(154, 255, 183, 0.5)" fill="none" stroke-linecap="round" stroke-width="4.8" class="el-progress-circle__path" :style="style" style="stroke-dasharray: 0px, 295.31px;stroke-dashoffset: 0px;transition: stroke-dasharray 0.6s ease 0s, stroke 0.6s ease 0s;"></path></svg>
                        </div>

                        <div class="progress">@{{progressCircle}}%</div>
                    </div>
                </label>
                <input id="input-file" type="file" @change="fileChange">

            </div>

            <div class="form-item">
                <input type="text" placeholder="姓  名" v-model="name">
            </div>

            <div class="form-item item-radio">
                <input id="male" type="radio" name="sex" v-model="sex" value="男">
                <label class="form-radio" for="male">男</label>
                <input id="female" type="radio" name="sex" v-model="sex" value="女">
                <label class="form-radio" for="female">女</label>

            </div>

            <div class="form-item">
                <input type="text" placeholder="专  业" v-model="major">
            </div>

            <div class="form-item">
                <div class="form-button" @click="showConfirm">生成我的毕业照</div>
            </div>

        </div>
        @include('gdbyz.footer')
    </div>

    <div class="confirm-container mask" v-show="show" style="display: none">
        <div class="confirm">
            <div class="title">提示</div>
            <p>你是否确认接受学校消息通知</p>
            <div class="cfm-btn">
                <div class="left-btn" @click="">确认</div>
                <div class="right-btn" @click="show=!show">取消</div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>

        // updated by cube-ui

        /*
        * Tencent is pleased to support the open source community by making WeUI.js available.
        *
        * Copyright (C) 2017 THL A29 Limited, a Tencent company. All rights reserved.
        *
        * Licensed under the MIT License (the "License"); you may not use this file except in compliance
        * with the License. You may obtain a copy of the License at
        *
        *       http://opensource.org/licenses/MIT
        *
        * Unless required by applicable law or agreed to in writing, software distributed under the License is
        * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
        * either express or implied. See the License for the specific language governing permissions and
        * limitations under the License.
        */

        /**
         * 检查图片是否有被压扁，如果有，返回比率
         * ref to http://stackoverflow.com/questions/11929099/html5-canvas-drawimage-ratio-bug-ios
         */
        function detectVerticalSquash(img) {
            // 拍照在IOS7或以下的机型会出现照片被压扁的bug
            var data;
            var ih = img.naturalHeight;
            var canvas = document.createElement('canvas');
            canvas.width = 1;
            canvas.height = ih;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            try {
                data = ctx.getImageData(0, 0, 1, ih).data;
            } catch (err) {
                console.log('Cannot check verticalSquash: CORS?');
                return 1;
            }
            var sy = 0;
            var ey = ih;
            var py = ih;
            while (py > sy) {
                var alpha = data[(py - 1) * 4 + 3];
                if (alpha === 0) {
                    ey = py;
                } else {
                    sy = py;
                }
                py = (ey + sy) >> 1; // py = parseInt((ey + sy) / 2)
            }
            var ratio = (py / ih);
            return (ratio === 0) ? 1 : ratio;
        }

        /**
         * dataURI to blob, ref to https://gist.github.com/fupslot/5015897
         * @param dataURI
         */
        function dataURItoBuffer(dataURI) {
            var byteString = atob(dataURI.split(',')[1]);
            var buffer = new ArrayBuffer(byteString.length);
            var view = new Uint8Array(buffer);
            for (var i = 0; i < byteString.length; i++) {
                view[i] = byteString.charCodeAt(i);
            }
            return buffer;
        }

        function dataURItoBlob(dataURI) {
            var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
            var buffer = dataURItoBuffer(dataURI);
            return new Blob([buffer], {type: mimeString});
        }

        /**
         * 获取图片的orientation
         * ref to http://stackoverflow.com/questions/7584794/accessing-jpeg-exif-rotation-data-in-javascript-on-the-client-side
         */
        function getOrientation(buffer) {
            var view = new DataView(buffer);
            if (view.getUint16(0, false) != 0xFFD8) return -2;
            var length = view.byteLength, offset = 2;
            while (offset < length) {
                var marker = view.getUint16(offset, false);
                offset += 2;
                if (marker == 0xFFE1) {
                    if (view.getUint32(offset += 2, false) != 0x45786966) return -1;
                    var little = view.getUint16(offset += 6, false) == 0x4949;
                    offset += view.getUint32(offset + 4, little);
                    var tags = view.getUint16(offset, little);
                    offset += 2;
                    for (var i = 0; i < tags; i++)
                        if (view.getUint16(offset + (i * 12), little) == 0x0112)
                            return view.getUint16(offset + (i * 12) + 8, little);
                } else if ((marker & 0xFF00) != 0xFF00) break;
                else offset += view.getUint16(offset, false);
            }
            return -1;
        }

        /**
         * 修正拍照时图片的方向
         * ref to http://stackoverflow.com/questions/19463126/how-to-draw-photo-with-correct-orientation-in-canvas-after-capture-photo-by-usin
         */
        function orientationHelper(canvas, ctx, orientation) {
            const w = canvas.width, h = canvas.height;
            if (orientation > 4) {
                canvas.width = h;
                canvas.height = w;
            }
            switch (orientation) {
                case 2:
                    ctx.translate(w, 0);
                    ctx.scale(-1, 1);
                    break;
                case 3:
                    ctx.translate(w, h);
                    ctx.rotate(Math.PI);
                    break;
                case 4:
                    ctx.translate(0, h);
                    ctx.scale(1, -1);
                    break;
                case 5:
                    ctx.rotate(0.5 * Math.PI);
                    ctx.scale(1, -1);
                    break;
                case 6:
                    ctx.rotate(0.5 * Math.PI);
                    ctx.translate(0, -h);
                    break;
                case 7:
                    ctx.rotate(0.5 * Math.PI);
                    ctx.translate(w, -h);
                    ctx.scale(-1, 1);
                    break;
                case 8:
                    ctx.rotate(-0.5 * Math.PI);
                    ctx.translate(-w, 0);
                    break;
            }
        }

        /**
         * 压缩图片
         */
        function compress(file, options, callback) {
            const reader = new FileReader();
            reader.onload = function (evt) {
                if (options.compress === false) {
                    // 不启用压缩 & base64上传 的分支，不做任何处理，直接返回文件的base64编码
                    file.base64 = evt.target.result;
                    callback(file);
                    return;
                }

                // 启用压缩的分支
                const img = new Image();
                img.onload = function () {
                    const ratio = detectVerticalSquash(img);
                    const orientation = getOrientation(dataURItoBuffer(img.src));
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    const maxW = options.compress.width;
                    const maxH = options.compress.height;
                    let w = img.width;
                    let h = img.height;
                    let dataURL;

                    if (w < h && h > maxH) {
                        w = parseInt(maxH * img.width / img.height);
                        h = maxH;
                    } else if (w >= h && w > maxW) {
                        h = parseInt(maxW * img.height / img.width);
                        w = maxW;
                    }

                    canvas.width = w;
                    canvas.height = h;

                    if (orientation > 0) {
                        orientationHelper(canvas, ctx, orientation);
                    }
                    ctx.drawImage(img, 0, 0, w, h / ratio);

                    if (/image\/jpeg/.test(file.type) || /image\/jpg/.test(file.type)) {
                        dataURL = canvas.toDataURL('image/jpeg', options.compress.quality);
                    } else {
                        dataURL = canvas.toDataURL(file.type, options.compress.qualify);
                    }

                    if (options.type == 'file') {
                        if (/;base64,null/.test(dataURL) || /;base64,$/.test(dataURL)) {
                            // 压缩出错，以文件方式上传的，采用原文件上传
                            console.warn('Compress fail, dataURL is ' + dataURL + '. Next will use origin file to upload.');
                            callback(file);
                        } else {
                            let blob = dataURItoBlob(dataURL);
                            blob.id = file.id;
                            blob.name = file.name;
                            blob.lastModified = file.lastModified;
                            blob.lastModifiedDate = file.lastModifiedDate;
                            callback(blob);
                        }
                    } else {
                        if (/;base64,null/.test(dataURL) || /;base64,$/.test(dataURL)) {
                            // 压缩失败，以base64上传的，直接报错不上传
                            options.onError(file, new Error('Compress fail, dataURL is ' + dataURL + '.'));
                            callback();
                        } else {
                            file.base64 = dataURL;
                            callback(file);
                        }
                    }
                };
                img.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        }

        const app = new Vue({
            el: '#app',
            data: ({
                show: false,
                name: '',
                major: '',
                sex: '',
                imgUrl: '',
                icon_show: true,
                progress: false,
                progressCircle: 0,
                style: {
                    'stroke-dasharray': '0px, 295.31px',
                },
                user: '{{$user}}'
            }),
            mounted: () => {
                // cube.Toast.$create({
                //     time: 1000,
                //     txt: 'Toast time 1s'
                // }).show()
            },
            watch: {
                progressCircle(next, last) {
                    let px = 295.31 * (next / 100)
                    console.log(px)
                    this.$set(this.style, 'stroke-dasharray', `${px}px, 295.31px`)
                }
            },

            methods: {
                showConfirm() {
                    if (!this.imgUrl) {
                        cube.Toast.$create({
                            txt: '请上传图片',
                            type: 'warn',
                            time: 700
                        }).show()
                        return
                    }

                    if (!this.name || !this.major || !this.sex) {
                        cube.Toast.$create({
                            txt: '请输入姓名、专业和性别',
                            type: 'warn',
                            time: 700
                        }).show()
                        return
                    }

                    cube.Dialog.$create({
                        type: 'confirm',
                        icon: 'cubeic-alert',
                        title: '确定提交嘛',
                        content: '提交生成毕业证哦，生成的毕业证是按你提交的照片的人脸进行融合照片的',
                        confirmBtn: {
                            text: '确定',
                            active: true,
                            disabled: false,
                            href: 'javascript:;'
                        },
                        cancelBtn: {
                            text: '取消',
                            active: false,
                            disabled: false,
                            href: 'javascript:;'
                        },
                        onConfirm: () => {
                            this.submit()
                        },
                    }).show()
                },

                submit: async function () {


                    const form = {
                        'img': this.imgUrl,
                        'name': this.name,
                        'major': this.major,
                        'sex': this.sex,
                        'user': this.user
                    }
                    await axios({
                        url: '/jxh/byz/submit',
                        method: 'post',
                        data: form
                    }).then(res => {
                        if (res.data.code > 0) {

                            window.location.href = '{{ url('jxh/byz/await') }}'
                            return
                        }
                        cube.Toast.$create({
                            txt: '出错了',
                            type: 'warn',
                            time: 700
                        }).show()

                    }).catch(err => {
                        cube.Toast.$create({
                            txt: '出错了',
                            type: 'warn',
                            time: 700
                        }).show()
                    })


                },

                fileChange(e) {
                    const file = e.target.files[0]
                    this.uploadFile(file)
                    this.icon_show = false
                    this.progress = true
                },

                uploadFile(file) {
                    const _this = this
                    compress(file, {
                        compress: {
                            width: 4096,
                            height: 4096,
                            qualify: 0.2
                        }
                    }, async function (blog) {
                        const fileForm = new FormData()
                        fileForm.append('file', blog, file.name)
                        await axios({
                            url: '/jxh/byz/upload',
                            method: 'post',
                            onUploadProgress: function (progressEvent) { //原生获取上传进度的事件
                                if (progressEvent.lengthComputable) {
                                    _this.progressCircle = Math.floor((progressEvent.loaded / progressEvent.total) * 100)
                                }
                            },
                            data: fileForm
                        }).then(res => {
                            if (res.data.code > 0) {
                                _this.imgUrl = res.data.data.url
                                _this.$refs.upload.style.backgroundImage = `url(${_this.imgUrl})`
                                _this.progress = false
                                return
                            }
                            cube.Toast.$create({
                                txt: '上传出错了',
                                type: 'warn',
                                time: 700
                            }).show()
                            _this.icon_show = true
                            _this.progress = false
                        }).catch(err => {
                            cube.Toast.$create({
                                txt: '上传出错了',
                                type: 'warn',
                                time: 700
                            }).show()
                            _this.icon_show = true
                            _this.progress = false
                        })

                    })
                }

            }

        })
    </script>
@endsection