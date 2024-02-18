/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { bytesToBase64 } from './base64';
import * as hash from 'hash.js';
import * as plupload from './plupload-2.3.9/js/plupload.full.min.js';
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({
    data() {
        return {
            showFileFormatDropdown: false,
        };
    },
    methods: {
        toggleFileFormatDropdown(event) {
            this.showFileFormatDropdown = event.target.files.length > 1;
        },
        handleFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('file-sending-form');
            if (form !== undefined && form !== null) {
                const formerAction = form.action;
                const contactUserIdElement = document.getElementById('contact_user_id');
        const contact_user_id = contactUserIdElement ? contactUserIdElement.value : null;
                if(formerAction.includes("http://localhost:8000/files/send/"))
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
                                form.submit();
                            }
                        } else {
                            form.action = "http://localhost:8000/files/multiple/send/" + contact_user_id;
                            
                            form.submit();
                        }
                    }
                }
                else if(formerAction == "http://localhost:8000/files/personal/store" || formerAction == "http://localhost:8000/global-files/store")
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
                    console.log('Cookie saving result:', response.data);
                }
                else {
                    let cookieVal = '';
                        const response = await axios.post('/api/update-canvas-cookie', {
                            canvasCookie: {
                                canvasId: cookieVal
                            },
                        });
                        console.log('Cookie updating result:', response.data);
                }
            } catch (error) {
                console.error('Error saving cookie:', error);
            }
        },
        uploadFiles()
        {
            if(!document.querySelector('#browse') )
            {
                return;
            }
            var uploader = new plupload.Uploader({
                browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
                url: 'upload.php'
              });
              uploader.init();
              uploader.bind('FilesAdded', function(up, files) {
                var html = '';
                plupload.each(files, function(file) {
                  html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
                });
                document.getElementById('filelist').innerHTML += html;
              });
               
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
