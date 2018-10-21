
// for node.js
var zmq = require('zmq')
var subscriber = zmq.socket('sub')

subscriber.on('message', function() {
  var msg = [];
    Array.prototype.slice.call(arguments).forEach(function(arg) {
        msg.push(arg.toString());
    });

    console.log(msg);
})

subscriber.connect('tcp://192.168.1.101:5563')
subscriber.subscribe('B')
