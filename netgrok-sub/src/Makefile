netgrok-sub: netgrok-sub.o
	gcc netgrok-sub.c -lzmq -o netgrok-sub

netgrok-sub.o:
	gcc -o netgrok-sub.o -c netgrok-sub.c

clean:
	rm -f netgrok-sub.o
	rm -f netgrok-sub
