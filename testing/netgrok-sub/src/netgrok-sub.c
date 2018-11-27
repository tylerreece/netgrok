#include <zmq.h>

#include <string.h>
#include <assert.h>
#include <signal.h>

// #define ENDPOINT "tcp://[::1]:7188"
#define ENDPOINT "tcp://127.0.0.1:7188"
#define MSG_FILTER "{"

#define LINE_MAX_LEN 4096
#define NO_FLAGS 0

static void *context;
static void *subscriber_socket;
static int interrupted;

// clean up the ZMQ stuff if the program is stopped
/* -------------------------------------------------------------------------- */
void interruptHandler(int sig) {
	printf("\nsignal: %d; closing subscriber socket now\n", sig);
	if (subscriber_socket) zmq_close(subscriber_socket);
	if (context) zmq_ctx_destroy(context);
	interrupted = 1;
}
/* -------------------------------------------------------------------------- */

int main() {
	size_t sz;
	struct sigaction signal_action;
	char buf[LINE_MAX_LEN];

	// setup for interrupt handling
	/* ------------------------------------------------------------------------ */
	interrupted = 0;
	signal_action.sa_handler = interruptHandler;
	signal_action.sa_flags = NO_FLAGS;
	sigemptyset(&signal_action.sa_mask);
	sigaction(SIGINT, &signal_action, NULL);
	sigaction(SIGTERM, &signal_action, NULL);
	/* ------------------------------------------------------------------------ */

	context = zmq_ctx_new();
	assert(context);

	subscriber_socket = zmq_socket(context, ZMQ_SUB);
	assert(subscriber_socket);

	assert(zmq_connect(subscriber_socket, ENDPOINT) == 0);

	sz = strlen(MSG_FILTER);
	assert(zmq_setsockopt(subscriber_socket, ZMQ_SUBSCRIBE, MSG_FILTER, sz) == 0);

	while (!interrupted) {
		assert(zmq_recv(subscriber_socket, buf, LINE_MAX_LEN, NO_FLAGS) != -1);
		printf("%s\n", buf);
	}

	return 0;
}
