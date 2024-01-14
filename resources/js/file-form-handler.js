import { createApp } from "vue";

const filesApp = createApp({
    mounted() {
        let form = document.getElementById('file-sending-form');
        console.log(form);
        if (form !== undefined && form !== null) {
            this.mount(form);
        }
    },
    methods: {
        submitFiles(event) {
            event.preventDefault();
            // Access the input element and get its files property
            const filesInput = event.target.querySelector('#files');
            
            if (filesInput.files.length == 1) {
                console.log(filesInput.files.length);
            } else if (filesInput.files.length > 1) {
                console.log(filesInput.files.length);
            }
        }
    }
});


filesApp.mount('#app'); // Replace '#app' with the appropriate element ID for your application
