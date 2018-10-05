# This Makefile is for compiling SSLsplit as a package for OpenWRT 18.06.0.
# It based off of the Makefile in https://github.com/adde88/sslsplit-openwrt

include $(TOPDIR)/rules.mk
include $(INCLUDE_DIR)/package.mk

PKG_NAME := sslsplit-netgrok
PKG_VERSION := 1.0
PKG_RELEASE := 1
PKG_BUILD_DIR := $(BUILD_DIR)/$(PKG_NAME)
# PKG_SOURCE_PROTO := git
# PKG_SOURCE_URL := git://github.com/ghost-in-the-bash/sslsplit-netgrok

# Use this variable if you want to have the package patched from a local dir:
# SOURCE_DIR := /home/<user>/sslsplit-netgrok

define Package/sslsplit-netgrok
	SECTION := net
	CATEGORY := Network
	TITLE := sslsplit-netgrok -- transparent SSL/TLS interception and analysis
	DEPENDS := \
		+libevent2 \
		+libevent2-openssl +libopenssl \
		+libevent2-pthreads +libpthread \
		+musl-fts +libzmq
endef

define Package/sslsplit-netgrok/description
	SSLsplit is a tool for man-in-the-middle attacks against SSL/TLS encrypted
	network connections. It is intended to be useful for network forensics,
	application security analysis and penetration testing.

	SSLsplit-NetGrok is merely a modification of SSLsplit that outputs info about
	the connections to be used in the NetGrok visualizer.
endef

define Build/Prepare
	mkdir -p $(PKG_BUILD_DIR)
	$(CP) -rf ./src/ $(PKG_BUILD_DIR)/
	# Use this only if you have the SOURCE_DIR variable set:
	# $(CP) -rf $(SOURCE_DIR)/src/ $(PKG_BUILD_DIR)/
endef

CONFIGURE_PATH := src/
MAKE_PATH := src/
TARGET_CFLAGS += $(TARGET_CPPFLAGS)

define Package/sslsplit-netgrok/install
	$(INSTALL_DIR) $(1)/usr/bin/
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/src/sslsplit $(1)/usr/bin/
endef

$(eval $(call BuildPackage,sslsplit-netgrok))
