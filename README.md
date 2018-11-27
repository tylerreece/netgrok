NetGrok
================================================================================
NetGrok is a tool for understanding network traffic from the point-of-view of
the local network. It uses [SSLsplit] to monitor connections made to and from
local hosts, and then outputs information about the connections, such as
addresses, time, and size. NetGrok can run on any router with [OpenWRT]
firmware. It is a successor of the original [NetGrok project] created at the
University of Maryland.

[SSLsplit]: https://www.roe.ch/SSLsplit
[OpenWRT]: https://openwrt.org/
[NetGrok project]: https://github.com/codydunne/netgrok

Getting Started
--------------------------------------------------------------------------------
The examples below show how to get NetGrok running on a brand new router. First,
clone the repository,
```
git clone https://github.com/ghost-in-the-bash/netgrok.git netgrok
```
and then follow one of the OpenWRT examples for installing NetGrok.

### Prerequisites
Install [prerequisites for OpenWRT]. The following example command is for a
64-bit Ubuntu Linux machine:
```
sudo apt-get install build-essential subversion libncurses5-dev zlib1g-dev gawk gcc-multilib flex git-core gettext libssl-dev
```

[prerequisites for OpenWRT]: https://openwrt.org/docs/guide-developer/build-system/install-buildsystem


### OpenWRT

#### How to build an OpenWRT firmware image from scratch with NetGrok on it
Compiling a new OpenWRT image is not necessary if the router already has OpenWRT
firmware on it. Nevertheless, this guide will show you how to set up most of
what NetGrok needs before it can install and run on a router.

1. For reference, see [beginners build guide] and [how to include custom files].

2. Clone the [OpenWRT repository]:
```
git clone https://git.openwrt.org/openwrt/openwrt.git openwrt
```

3. Go into the OpenWRT source directory and remove any build artifacts:
```
cd openwrt
make distclean
```

4. (Optional) Check out the latest stable release of OpenWRT. This example uses
OpenWRT v18.06.1:
```
git tag
git checkout v18.06.1
```

5. Copy all files from the `netgrok/openwrt/` directory into the OpenWRT source
directory. The `netgrok/openwrt/` directory contains the following:
  - `feeds.conf`, a custom feed configuration file for OpenWRT packages;
  - `files/etc/firewall.user`, custom NAT rules for SSLsplit-NetGrok to work;
  - `files/etc/rc.local`, a custom startup script that automatically generates a
    NetGrok CA key and certificate pair if one does not already exist, and then
    runs SSLsplit-NetGrok.

  ```
  cd ..
  cp -r netgrok/openwrt/* openwrt
  ```

6. Update the package feeds and install the packages that NetGrok needs:
  - LuCI
  - musl-fts
  - ZeroMQ
  - NetGrok

  ```
  cd openwrt

  ./scripts/feeds update -a
  ./scripts/feeds update luci
  ./scripts/feeds update musl-fts
  ./scripts/feeds update zmq
  ./scripts/feeds update netgrok

  ./scripts/feeds install luci
  ./scripts/feeds install musl-fts
  ./scripts/feeds install zmq
  ./scripts/feeds install -a -p netgrok
  ```

7. Use the build system configuration interface to set build settings:
```
make menuconfig
```

Use the arrow keys to navigate the menu. If not already selected, select the
following packages:
- Libraries
  - libzmq-nc
  - musl-fts
- LuCI
  - Collections
    - luci
  - Applications
    - luci-app-netgrok
- Network
  - netgrok-sub
  - sslsplit-netgrok
- Utilities
  - openssl-util


8. Find your OpenWRT-compatible router in the [table of hardware]:
  - Under the 'Device Techdata' column, click 'View/Edit data'
  - Have this open for reference for the next step


9. Use `make menuconfig` to set the build settings for the target architecture
based on information from the previous step:
  - Target System
  - Subtarget
  - Target Profile (device model)

10. Build the cross-compilation toolchain. This might take almost an hour:
```
make toolchain/install
```

11. Build the OpenWRT image:
```
make download
make -j 5
```

[beginners build guide]: https://openwrt.org/docs/guide-user/additional-software/beginners-build-guide
[how to include custom files]: https://openwrt.org/docs/guide-developer/build-system/use-buildsystem#custom_files
[OpenWRT repository]: https://git.openwrt.org/openwrt/openwrt.git
[table of hardware]: https://openwrt.org/toh/start


#### How to cross-compile SSLsplit-NetGrok for an OpenWRT router
There are two main ways to cross-compile packages for OpenWRT:
1. [OpenWRT SDK]
2. [OpenWRT build system]

[OpenWRT SDK]: https://openwrt.org/docs/guide-developer/using_the_sdk
[OpenWRT build system]: https://openwrt.org/docs/guide-developer/build-system/start

This guide uses the build system. To get the build system ready, follow steps
1-10 in the previous guide on how to build an OpenWRT image from scratch. Then
enter the following command while in the OpenWRT source directory:

```
make package/sslsplit-netgrok/{clean,compile}
```

The package should be located in `bin/packages/<architecture>/netgrok/`.


#### Installing SSLsplit-NetGrok on an OpenWRT router
1. After cross-compiling the SSLsplit-NetGrok package for the router, copy the
`.ipk` file onto the router.

2. Additionally, copy the files listed in [step 5] of the guide on building an
OpenWRT firmware image to run SSLsplit-NetGrok automatically on boot.

3. SSH into the router.

4. Go to the directory of the `.ipk` file and enter `opkg install <package_filename.ipk>`.

[step 5]: ./README.md#L62

### Run SSLsplit-NetGrok manually
1. If a certificate authority key and certificate pair does not already exist,
generate one:
```
openssl genrsa -out /netgrok/netgrok.key 4096
printf "US\nNew York\nWest Point\nNetGrok CA\nNetGrok II\nNetGrok\n\n" | openssl req -new -x509 -days 1826 -key /netgrok/netgrok.key -out /netgrok/netgrok.crt
```

2. Make the certificate available to outside hosts by creating/updating the
symbolic link for the .crt at http://192.168.1.1/netgrok.crt:
```
ln -sf /netgrok/netgrok.crt /www/netgrok.crt
```

3. Modify the NAT. SSLsplit-NetGrok focuses on HTTP and HTTPS:
```
iptables -t nat -A PREROUTING -p tcp --dport   80 -j REDIRECT --to-ports 8080
iptables -t nat -A PREROUTING -p tcp --dport  443 -j REDIRECT --to-ports 8443
```
However, SSLsplit also works with the following rules:
```
iptables -t nat -A PREROUTING -p tcp --dport  465 -j REDIRECT --to-ports 8443
iptables -t nat -A PREROUTING -p tcp --dport  587 -j REDIRECT --to-ports 8443
iptables -t nat -A PREROUTING -p tcp --dport  993 -j REDIRECT --to-ports 8443
iptables -t nat -A PREROUTING -p tcp --dport 5222 -j REDIRECT --to-ports 8080
```

4. Run SSLsplit-NetGrok:
```
sslsplit -k /netgrok/netgrok.key -c /netgrok/netgrok.crt tcp 0.0.0.0 8080 ssl 0.0.0.0 8443
```

Submodules
--------------------------------------------------------------------------------
### SSLsplit-NetGrok
SSLsplit-NetGrok is merely a modification of [SSLsplit], tailored for use in
this project. It publishes JSON dumps of TCP connection info, using a [ZeroMQ]
publisher socket.

The following files of the original SSLsplit have been modified for
SSLsplit-NetGrok:
- [GNUmakefile]
- [pxyconn.c]

The following files have been added to the original SSLsplit source directory
for SSLsplit-NetGrok:
- [netgrok.c]
- [netgrok.h]
- [uthash.h]

[SSLsplit]: https://github.com/droe/sslsplit
[ZeroMQ]: https://github.com/zeromq
[GNUmakefile]: https://github.com/Ghost-in-the-Bash/sslsplit-netgrok/blob/master/GNUmakefile#L340
[pxyconn.c]: https://github.com/Ghost-in-the-Bash/sslsplit-netgrok/blob/master/pxyconn.c
[netgrok.c]: https://github.com/Ghost-in-the-Bash/sslsplit-netgrok/blob/master/netgrok.c
[netgrok.h]: https://github.com/Ghost-in-the-Bash/sslsplit-netgrok/blob/master/netgrok.h
[uthash.h]: https://github.com/troydhanson/uthash.git

### NetGrok-Subscriber
NetGrok-Subscriber is a simple command line tool that subscribes to a ZeroMQ
socket to receive messages from SSLsplit-NetGrok. Using the `netgrok-sub`
command prints the JSON dumps from SSLsplit-NetGrok to standard output. However,
it is only useful as long as SSLsplit-NetGrok is running.


### NetGrok LuCI application
The NetGrok application for LuCI is a work-in-progress tool for visualizing
network connections by using data given by SSLsplit-NetGrok. It is accessible by
web browser when connected to an OpenWRT router (that has the NetGrok package
installed).


## Deployment
NetGrok was designed to run on routers with OpenWRT firmware.

## Authors

- **Son Tran** - *initial work* - [Ghost-in-the-Bash]

[Ghost-in-the-Bash]: https://github.com/ghost-in-the-bash


## License

TODO