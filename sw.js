// [START initialize_firebase_in_sw]
// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/3.7.4/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.7.4/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
firebase.initializeApp({
  'messagingSenderId': '845696256657'
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
// [END initialize_firebase_in_sw]

// If you would like to customize notifications that are received in the
// background (Web app is closed or not in browser focus) then you should
// implement this optional method.
// [START background_handler]
 self.addEventListener('push', function(event) {
  console.log('Push message e', event);
  var title = 'Compton Eye Associates sdffdsssssss';
  event.waitUntil(
    self.registration.showNotification(title, {
      body: '1 New Appointment has been booked',
      icon: 'firebase-logo.png',
      tag: 'Appointment Booked'
  }));
  });
 self.addEventListener('notificationclick', function(event) {
  console.log('[Service Worker] Notification click Received.');

  event.notification.close();

  event.waitUntil(
    clients.openWindow('http://localhost/compteyes/wp-admin/admin.php')
  );
});

messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  // console.log(payload.notification.title);
  var notificationTitle='Appointment bookeddddddddddd';
  var notificationOptions = {
    body: '2 New appointment has been booked',
    icon: '/compteyes.jpg'
  };
  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});
// [END background_handler]
