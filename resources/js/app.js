/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

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
                const filesInput = document.querySelector('#files');
const contact_user_id = document.getElementById('contact_user_id').value; 
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
                    } else  {
                        form.action = "http://localhost:8000/files/multiple/send/" + contact_user_id;

                        form.submit();
                    }
                }
            }
        },
    },
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
