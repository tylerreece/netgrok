include $(TOPDIR)/rules.mk
include $(INCLUDE_DIR)/package.mk

PKG_NAME := netgrok-sub
PKG_VERSION := 1.0
PKG_RELEASE := 1
PKG_BUILD_DIR := $(BUILD_DIR)/$(PKG_NAME)

# Use this variable if you want to have the package patched from a local dir:
# SOURCE_DIR := /home/<user>/netgrok-sub

define Package/netgrok-sub
	SECTION := net
	CATEGORY := Network
	TITLE := netgrok-sub -- SSLsplit-NetGrok Subscriber
	DEPENDS := +libzmq
endef

define Package/netgrok-sub/description
	NetGrok-Subscriber subscribes to a inter-process ZMQ socket. SSLsplit-NetGrok
	publishes JSON dumps to this socket, which NetGrok-Subscriber takes and prints
	to standard output.
endef

define Build/Prepare
	mkdir -p $(PKG_BUILD_DIR)
	$(CP) -rf src/ $(PKG_BUILD_DIR)/
	# Use this only if you have the SOURCE_DIR variable set:
	# $(CP) -rf $(SOURCE_DIR)/src/ $(PKG_BUILD_DIR)/
endef

CONFIGURE_PATH := src/
MAKE_PATH := src/

define Build/Compile
	$(TARGET_CC) $(TARGET_CFLAGS) -o $(PKG_BUILD_DIR)/src/netgrok-sub.o -c $(PKG_BUILD_DIR)/src/netgrok-sub.c
	$(TARGET_CC) $(TARGET_LDFLAGS) $(PKG_BUILD_DIR)/src/netgrok-sub.c -lzmq -o $(PKG_BUILD_DIR)/src/$1
endef

# Package install instructions;
# create a directory inside the package to hold our executable,
# and then copy the executable we built previously into the folder
define Package/netgrok-sub/install
	$(INSTALL_DIR) $(1)/usr/bin/
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/src/netgrok-sub $(1)/usr/bin/
endef

$(eval $(call BuildPackage,netgrok-sub))
