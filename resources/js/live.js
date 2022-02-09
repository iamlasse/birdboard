import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

console.log(process.env.MIX_PUSHER_APP_KEY)
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    // wsHost: window.location.hostname,
    // wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});


// window.Echo.private('projects.91')
//         .listen('ProjectUpdated', (e) => {
//             console.log(e.project);
//             // livewire.emit('projectUpdated', e.project)
//         });

//         window.onload = function() {
//             livewire.on('projectUpdated:91', projectId => {
//                 console.log('A project was updated with the id of: ', projectId);
//             })
//         }

        // window.Echo.private(`App.Models.User.${userId}`)
        //     .notification((notification) => {
        //          console.log(notification.type);
        //     });