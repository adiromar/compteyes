  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyAiwrKX06j4HWH8sz1bo_50JkBpgeiav6w",
    authDomain: "myproject-f233c.firebaseapp.com",
    databaseURL: "https://myproject-f233c.firebaseio.com",
    projectId: "myproject-f233c",
    storageBucket: "myproject-f233c.appspot.com",
    messagingSenderId: "845696256657"
  };
  firebase.initializeApp(config);

  const messaging = firebase.messaging();
  messaging.requestPermission()
  .then(function()) {
    console.log('Have permission');
    return messaging.getToken();
  })
 . then(function(token){
  console.log(token);
 })
  .catch(function(err){
    console.log('Error occured');
  })



  // Get the list of device tokens.
  return admin.database().ref('fcmTokens').once('value').then(allTokens => {
    if (allTokens.val()) {
      // Listing all tokens.
      const tokens = Object.keys(allTokens.val());

      // Send notifications to all tokens.
      return admin.messaging().sendToDevice(tokens, payload).then(response => {
        // For each message check if there was an error.
        const tokensToRemove = [];
        response.results.forEach((result, index) => {
          const error = result.error;
          if (error) {
            console.error('Failure sending notification to', tokens[index], error);
            // Cleanup the tokens who are not registered anymore.
            if (error.code === 'messaging/invalid-registration-token' ||
                error.code === 'messaging/registration-token-not-registered') {
              tokensToRemove.push(allTokens.ref.child(tokens[index]).remove());
            }
          }
        });
        return Promise.all(tokensToRemove);
      });
    }
  });