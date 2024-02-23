/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { bytesToBase64 } from './base64';
import * as hash from 'hash.js';
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({
    data() {
        return {
            showFileFormatDropdown: false,
            normalUploadMode: true,
            bigFilesUploadMode: false,
            fileUploader: null, 
        };
    },
    methods: {
        toggleFileFormatDropdown(event) {
            this.showFileFormatDropdown = event.target.files.length > 1;
        },
        handleFileUploadModeChange(event){
            let mode = event.target.value;
            if(mode === "normal")
            {
                this.setNormalUploadMode();
            }
            if(mode === "bigSize")
            {
                this.setBigFilesUploadMode();
            }
        },
        setNormalUploadMode()
        {
            document.getElementById('normalUploadMode').removeAttribute('hidden');
            document.getElementById('bigFilesUploadMode').setAttribute('hidden', '');
            this.normalUploadMode = true;
            this.bigFilesUploadMode = false;
        },
        setBigFilesUploadMode()
        {
          document.getElementById('bigFilesUploadMode').removeAttribute('hidden'); 
          document.getElementById('normalUploadMode').setAttribute('hidden', ''); 
          this.normalUploadMode = false;
          this.bigFilesUploadMode = true;    
    },
        async handleFormSubmit(event) {
            const form = document.getElementById('file-sending-form');
            event.preventDefault();
            if(this.normalUploadMode){
                if (form !== undefined && form !== null) {
                    const formAction = form.action;
                    const contactUserIdElement = document.getElementById('contact_user_publicId');
                    const contact_user_id = contactUserIdElement ? contactUserIdElement.value : null;
                    if(formAction.includes("http://localhost:8000/files/send/"))
                    {
                        const filesInput = document.querySelector('#files');
                        
                        if (filesInput.files.length == 1) {
                        form.action = "http://localhost:8000/files/send/" + contact_user_id;
                        form.submit();
                        
                    } else if (filesInput.files.length > 1) {
                        const fileCompressionFormat = document.getElementById('fileCompressionFormat').value;
                        
                        if (fileCompressionFormat === "none") {
                            const confirmed = confirm("You chose no compression format, so your files won't be sent as an archive but separately. Still proceed?");
                            if (confirmed) {
                                // Continue with form submission
                                form.action = "http://localhost:8000/files/multiple/send/" + contact_user_id;
                                console.log('hi');
                                form.submit();
                            }
                        } else {
                            form.action = "http://localhost:8000/files/multiple/send/" + contact_user_id;
                            form.submit();
                        }
                    }
                }
                else if(formAction == "http://localhost:8000/files/personal/store" || formAction == "http://localhost:8000/global-files/store")
                {
                    // console.log(fileAccessibility.value);
                    const fileAccessibility = document.querySelector('#fileAccessibility').value;
                    if(fileAccessibility === "private"){
                        form.action = "http://localhost:8000/files/personal/store";
                        form.submit();
                    }
                    else if(fileAccessibility === "protected" || fileAccessibility === "public")
                    {
                        form.action = "http://localhost:8000/global-files/store";
                        form.submit();
                    }
                }
            }
        }
        else if(this.bigFilesUploadMode){
            try {
                this.fileUploader.start();
            } catch (error) {
                console.error('Error using uploader:', error);
            }
        }
        },
         getCookie(cookieName) {
            let cookie = {};
            document.cookie.split(';').forEach(function(el) {
              let [key,value] = el.split('=');
              cookie[key.trim()] = value;
            });
            return cookie[cookieName];
          },
         async saveCanvasCookie() {
            const ctx = document.getElementById('canvas').getContext('2d');
            ctx.font = '12px serif';
            ctx.fillText('Hail World', 10, 10);
            const imageData = ctx.getImageData(0, 0, 70, 30).data;
            const hashedImageData = hash.sha256().update(imageData).digest('hex');
        
            // Check if the cookie already exists
            try {
            if (!document.cookie.includes('canvasId')) {
                    const response = await axios.post('/api/save-canvas-cookie', {
                        canvasCookie: {
                            canvasId: hashedImageData,
                        },
                    });
                    // console.log('Cookie saving result:', response.data);
                }
                else {
                    let cookieVal = '';
                        const response = await axios.post('/api/update-canvas-cookie', {
                            canvasCookie: {
                                canvasId: cookieVal
                            },
                        });
                        // console.log('Cookie updating result:', response.data);
                }
            } catch (error) {
                console.error('Error saving cookie:', error);
            }
        },
        async uploadFiles() 
        {
            const currentUrl = window.location.href;
            const contactId = document.querySelector('#contact_user_publicId') ? document.querySelector('#contact_user_publicId').value : null;
            console.log(contactId);
            if(!document.querySelector('#browse') )
            {
                return;
            }
            let uploader;
            try {
                if(currentUrl === 'http://localhost:8000/files/create/' + contactId){
                uploader = new plupload.Uploader({
                        browse_button: 'browse', 
                        runtimes: 'html5,flash,silverlight,html4',
                        url: `/files/send/${contactId}`,
                        headers: {
                            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'multipart/form-data'
                        },
                        multipart: true,
                        multipart_params: {
                            'title' : document.getElementById('title').value,
                            'description': document.getElementById('description').value,
                            'category': document.getElementById('category').value
                        },
        
                        file_data_name: 'file',
                       
                    });
                }
            } catch (error) {
                console.error('Error initializing uploader:', error);
            }
            if(currentUrl === 'http://localhost:8000/global-files/protected/create'){
            uploader = new plupload.Uploader({
                    browse_button: 'browse', 
                    url: '/global-files/store'
                });
            }
            if(currentUrl === 'http://localhost:8000/global-files/public/create'){
            uploader = new plupload.Uploader({
                    browse_button: 'browse', 
                    url: '/global-files/store' 
                });
            }
            if(currentUrl === 'http://localhost:8000/files/personal/create'){
            uploader = new plupload.Uploader({
                        browse_button: 'browse', 
                        url: '/files/personal/store' 
                    });
                }
            
              uploader.init();
              uploader.bind('FilesAdded', function(up, files) {
                let html = '';
                plupload.each(files, function(file) {
                  html += '<li id="' + file.id + '" name="file">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
                  console.log(file);
                });
                document.getElementById('filelist').innerHTML += html;
              });
              uploader.bind('Error', function(up, err) {
                document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
              });
              uploader.bind('FilesUploaded', function (up, files) {
                // All files have been successfully uploaded
                // You can now submit the form
                console.log(files);
                form.submit();
            });
              uploader.bind('UploadProgress', function(up, file) {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
              });
              this.fileUploader = uploader;
        },
    },
    mounted() {
        this.saveCanvasCookie();
        this.uploadFiles();
    }
});


import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
