var zmq = require('zeromq');
var sock = zmq.socket('sub');

sock.connect('tcp://127.0.0.1:7188');
sock.subscribe();
console.log('Subscriber connected to port 7188');

sock.on('message', function(topic, message) {
  console.log('received a message related to:', topic.toString(), 'containing message:', message.toString());
});
